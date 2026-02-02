<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Shows Controller
 *
 * @property \App\Model\Table\ShowsTable $Shows
 * @method \App\Model\Entity\Show[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ShowsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $selectedDate = $this->request->getQuery('date');

        // Fetch all available dates for the header tabs
        $user = $this->request->getSession()->read('Auth.User');
        $isAdmin = $user && isset($user['role']) && $user['role'] === 'admin';

        $conditions = ['Shows.status' => 1];
        if (!$isAdmin) {
            $conditions['Shows.show_date >='] = date('Y-m-d');
        }

        $availableDates = $this->Shows->find()
            ->select(['show_date'])
            ->distinct(['show_date'])
            ->where($conditions)
            ->order(['show_date' => 'ASC'])
            ->all()
            ->extract('show_date')
            ->toArray();

        // Separate logic for separated lists (admin use)
        $activeDates = [];
        $historyDates = [];
        
        if ($isAdmin) {
             // Fetch ALL unique dates first to separate them in PHP
             foreach ($availableDates as $d) {
                 $dOb = ($d instanceof \DateTimeInterface) ? $d : new \DateTime($d);
                 $today = new \DateTime('today');
                 
                 // Reset time components
                 $dOb->setTime(0, 0, 0);

                 if ($dOb < $today) {
                     $historyDates[] = $d;
                 } elseif ($dOb == $today) {
                     // It's Today. Check both Past and Future availability.
                     $currentTime = date('H:i:s');
                     
                     $hasUpcoming = $this->Shows->exists([
                         'show_date' => $dOb->format('Y-m-d'),
                         'show_time >=' => $currentTime,
                         'status' => 1
                     ]);
                     
                     $hasPast = $this->Shows->exists([
                         'show_date' => $dOb->format('Y-m-d'),
                         'show_time <' => $currentTime,
                         'status' => 1
                     ]);

                     if ($hasUpcoming) {
                         $activeDates[] = $d;
                     }
                     if ($hasPast) {
                         $historyDates[] = $d;
                     }
                 } else {
                     $activeDates[] = $d;
                 }
             }
             // Sort history DESC
             rsort($historyDates);
        } else {
            // Customer: Check Today to ensure we don't show empty Today if all passed
             foreach ($availableDates as $d) {
                 $dOb = ($d instanceof \DateTimeInterface) ? $d : new \DateTime($d);
                 $today = new \DateTime('today');
                 $dOb->setTime(0, 0, 0);
                 
                 if ($dOb == $today) {
                     $currentTime = date('H:i:s');
                     $hasUpcoming = $this->Shows->exists([
                         'show_date' => $dOb->format('Y-m-d'),
                         'show_time >=' => $currentTime,
                         'status' => 1
                     ]);
                     if ($hasUpcoming) {
                         $activeDates[] = $d;
                     }
                 } elseif ($dOb > $today) {
                     $activeDates[] = $d;
                 }
             }
        }

        $viewType = $this->request->getQuery('view');
        
        // Default selection logic
        if (!$selectedDate) {
             // Prefer Active defaults
             if (!empty($activeDates)) {
                 $selectedDate = ($activeDates[0] instanceof \DateTimeInterface) ? $activeDates[0]->format('Y-m-d') : $activeDates[0];
                 $viewType = 'active'; 
             } elseif (!empty($historyDates) && $isAdmin) {
                 $selectedDate = ($historyDates[0] instanceof \DateTimeInterface) ? $historyDates[0]->format('Y-m-d') : $historyDates[0];
                 $viewType = 'history';
             } else {
                 $selectedDate = date('Y-m-d');
             }
        }

        // Fetch shows for selected date
        $query = $this->Shows->find()
            ->contain(['Halls'])
            ->where([
                'Shows.show_date' => $selectedDate,
                'Shows.status' => 1
            ])
            ->order(['show_time' => 'ASC']);
            
        // Filter Time for "Today" based on View Context
        if ($selectedDate == date('Y-m-d')) {
            $currentTime = date('H:i:s');
            if ($viewType === 'history') {
                $query->where(['Shows.show_time <' => $currentTime]);
            } else {
                // Default / Active view: Hide past shows
                $query->where(['Shows.show_time >=' => $currentTime]);
            }
        }

        $shows = $query->all();

        // Group shows by Movie Title for the UI
        $groupedShows = [];
        foreach ($shows as $show) {
            $groupedShows[$show->show_title][] = $show;
        }

        $this->set(compact('groupedShows', 'availableDates', 'activeDates', 'historyDates', 'selectedDate', 'isAdmin', 'viewType'));
    }

    /**
     * View method
     *
     * @param string|null $id Show id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $show = $this->Shows->get($id, [
            'contain' => ['Bookings', 'Tickets'],
        ]);

        $this->set(compact('show'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $show = $this->Shows->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Patch movie metadata into the main entity so it stays in the form on failure
            // Fix: Exclude avatar object from patchEntity to prevent conversion error
            $patchData = $data;
            if (isset($patchData['avatar']) && is_object($patchData['avatar'])) {
                unset($patchData['avatar']);
            }
            $show = $this->Shows->patchEntity($show, $patchData);

            // Handle Movie Poster Upload
            $avatarFile = $this->request->getData('avatar');
            $fileName = $data['existing_avatar'] ?? '';

            if ($avatarFile && is_object($avatarFile) && $avatarFile->getError() === 0) {
                // New file uploaded
                $fileName = $avatarFile->getClientFilename();
                $targetPath = WWW_ROOT . 'img' . DS . 'shows' . DS . $fileName;
                try {
                    $avatarFile->moveTo($targetPath);
                    // Important: Update the entity and request data so it persists on error
                    $show->avatar = $fileName;
                    
                    // Inject into request data so FormHelper picks it up for the hidden field
                    $data['existing_avatar'] = $fileName;
                    $this->request = $this->request->withParsedBody($data);
                    
                    $this->request->getSession()->write('last_uploaded_avatar', $fileName); // Backup
                } catch (\Exception $e) {
                    $this->Flash->error(__('Failed to upload poster: {0}', $e->getMessage()));
                }
            }

            $finalAvatar = !empty($fileName) ? $fileName : ($data['existing_avatar'] ?? null);
            $finalAvatarDir = 'webroot/img/shows';

            $scheduleData = $data['schedule'] ?? [];
            $savedCount = 0;
            $errors = [];

            if (!empty($scheduleData) && is_array($scheduleData)) {
                foreach ($scheduleData as $dateGroup) {
                    $date = $dateGroup['show_date'] ?? null;
                    $sessions = $dateGroup['sessions'] ?? [];

                    if (!$date || empty($sessions))
                        continue;

                    foreach ($sessions as $session) {
                        $time = $session['show_time'] ?? null;
                        $hallId = $session['hall_id'] ?? null;
                        $duration = $data['duration'] ?? 120; // Default 2 hours if not set

                        if (empty($time))
                            continue;

                        // Check for Overlap
                        $newStart = new \DateTime($date . ' ' . $time);
                        $newEnd = clone $newStart;
                        $newEnd->modify('+' . $duration . ' minutes');

                        $overlaps = $this->Shows->find()
                            ->where([
                                'show_date' => $date,
                                'hall_id' => $hallId,
                                'status' => 1
                            ])
                            ->all();

                        $isOverlap = false;
                        foreach ($overlaps as $existing) {
                            // Handle both String and Object types safely
                            $dVal = $existing->show_date;
                            if ($dVal instanceof \DateTimeInterface) {
                                $dVal = $dVal->format('Y-m-d');
                            }
                            
                            $tVal = $existing->show_time;
                            if ($tVal instanceof \DateTimeInterface) {
                                $tVal = $tVal->format('H:i:s');
                            }
                            
                            $exStart = new \DateTime($dVal . ' ' . $tVal);
                            $exDuration = $existing->duration ?? 120; // Default if null
                            $exEnd = clone $exStart;
                            $exEnd->modify('+' . $exDuration . ' minutes');

                            // Overlap Logic: (StartA < EndB) && (EndA > StartB)
                            if ($newStart < $exEnd && $newEnd > $exStart) {
                                $isOverlap = true;
                                $conflictTime = (new \DateTime($time))->format('h:i A');
                                $errors[] = "Session at " . $conflictTime . " overlaps with '" . $existing->show_title . "' which ends at " . $exEnd->format('h:i A');
                                break;
                            }
                        }

                        if ($isOverlap) {
                            continue;
                        }

                        $newShow = $this->Shows->newEmptyEntity();
                        $showEntry = [
                            'show_title' => $data['show_title'],
                            'genre' => $data['genre'],
                            'avatar' => $finalAvatar,
                            'avatar_dir' => $finalAvatarDir,
                            'show_date' => $date,
                            'show_time' => $time,
                            'hall_id' => $hallId,
                            'duration' => $duration,
                            'status' => $data['status']
                        ];

                        $newShow = $this->Shows->patchEntity($newShow, $showEntry);
                        if ($this->Shows->save($newShow)) {
                            // --- CLONE SEATS FROM MASTER HALL TO ShowSeats ---
                            $seatsTable = $this->fetchTable('Seats');
                            $showSeatsTable = $this->fetchTable('ShowSeats');
                            
                            $masterSeats = $seatsTable->find()
                                ->where(['hall_id' => $hallId])
                                ->all();
                                
                            $clonedSeats = [];
                            foreach ($masterSeats as $mSeat) {
                                $clonedSeats[] = $showSeatsTable->newEntity([
                                    'show_id' => $newShow->id,
                                    'seat_row' => $mSeat->seat_row,
                                    'seat_number' => $mSeat->seat_number,
                                    'seat_type' => $mSeat->seat_type,
                                    'seat_price' => $mSeat->seat_price,
                                    'status' => $mSeat->status // Clones maintenance status as well
                                ]);
                            }
                            
                            if (!empty($clonedSeats)) {
                                $showSeatsTable->saveMany($clonedSeats);
                            }

                            $savedCount++;
                        } else {
                            $errors[] = $newShow->getErrors();
                        }
                    }
                }

                if ($savedCount > 0 && empty($errors)) {
                    $this->Flash->success(__('{0} movie sessions have been successfully scheduled.', $savedCount));
                    return $this->redirect(['action' => 'index']);
                }
            }

            if (!empty($errors)) {
                $detailedErrors = [];
                foreach ($errors as $err) {
                    if (is_string($err)) {
                        $detailedErrors[] = $err;
                    } elseif (is_array($err)) {
                        // Flatten CakePHP validation errors
                        foreach ($err as $field => $messages) {
                            if (is_array($messages)) {
                                $detailedErrors[] = ucfirst($field) . ': ' . implode(', ', array_values($messages));
                            } else {
                                $detailedErrors[] = ucfirst($field) . ': ' . $messages;
                            }
                        }
                    }
                }
                
                $msg = __('The sessions could not be saved.');
                if ($savedCount > 0) {
                    $msg = __('{0} sessions were saved, but some failed.', $savedCount);
                }
                
                $this->Flash->error($msg . ' ' . implode(' | ', array_unique($detailedErrors)));
            } else {
                if ($savedCount === 0) {
                    $this->Flash->error(__('No sessions were added. Please check your input.'));
                }
            }
        }

        // Fetch existing movies metadata
        // Fetch existing movies metadata (Latest version per movie title)
        $results = $this->Shows->find()
            ->select(['show_title', 'genre', 'avatar', 'avatar_dir', 'duration'])
            ->order(['modified' => 'DESC'])
            ->all();

        // Manual filtering to ensure unique show_title
        $uniqueMovies = [];
        foreach ($results as $movie) {
            if (!isset($uniqueMovies[$movie->show_title])) {
                $uniqueMovies[$movie->show_title] = $movie;
            }
        }
        $existingMovies = array_values($uniqueMovies);

        // Fetch all show history for the "Schedule Reference" feature
        $historyData = $this->Shows->find()
            ->contain(['Halls'])
            ->select(['show_title', 'show_date', 'show_time', 'duration', 'Halls.hall_type'])
            ->order(['show_date' => 'DESC', 'show_time' => 'ASC'])
            ->all();

        $historyGrouped = [];
        $globalDailyData = [];
        foreach ($historyData as $h) {
            $dateStr = (new \Cake\I18n\FrozenDate($h->show_date))->format('d-m-Y');
            $startTime = new \Cake\I18n\FrozenTime($h->show_time);
            $duration = $h->duration ?? 120;
            $endTime = $startTime->addMinutes($duration);
            
            $timeStr = $startTime->format('h:i A');
            $endStr = $endTime->format('h:i A');
            $hallStr = $h->has('hall') ? $h->hall->hall_type : 'N/A';
            
            // By Movie View
            $historyGrouped[$h->show_title][$dateStr][] = [
                'time' => $timeStr,
                'end' => $endStr,
                'hall' => $hallStr
            ];

            // Daily Hall View
            $globalDailyData[$dateStr][$hallStr][] = [
                'title' => $h->show_title,
                'time' => $timeStr,
                'end' => $endStr
            ];
        }

        // Fetch Halls for dropdown
        $halls = $this->Shows->Halls->find('list', ['limit' => 200])->all();

        $this->set(compact('show', 'existingMovies', 'historyGrouped', 'globalDailyData', 'halls'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Show id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $show = $this->Shows->get($id, [
            'contain' => ['Halls'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Handle Movie Poster Upload
            $avatarFile = $data['avatar'] ?? null;
            if ($avatarFile && is_object($avatarFile) && $avatarFile->getError() === 0) {
                $fileName = $avatarFile->getClientFilename();
                $targetPath = WWW_ROOT . 'img' . DS . 'shows' . DS . $fileName;
                try {
                    $avatarFile->moveTo($targetPath);
                    $data['avatar'] = $fileName;
                    $data['avatar_dir'] = 'webroot/img/shows';
                } catch (\Exception $e) {
                    $this->Flash->error(__('Failed to upload poster: {0}', $e->getMessage()));
                }
            } else {
                // If no new file uploaded, remove from data so it doesn't overwrite with object/null
                unset($data['avatar']);
            }

            // --- Validation: Check for Overlap ---
            $checkDate = $data['show_date'] ?? $show->show_date;
            $checkTime = $data['show_time'] ?? $show->show_time;
            $checkHall = $data['hall_id'] ?? $show->hall_id;
            $checkDuration = !empty($data['duration']) ? $data['duration'] : ($show->duration ?: 120);

            // Normalize Date/Time if they are arrays (CakePHP default) or objects
            if (is_array($checkDate)) {
                 $checkDate = $checkDate['year'] . '-' . $checkDate['month'] . '-' . $checkDate['day'];
            } elseif ($checkDate instanceof \DateTimeInterface) {
                 $checkDate = $checkDate->format('Y-m-d');
            }

            if (is_array($checkTime)) {
                 // Simple concatenation for hour/minute defaults if standard Form helper is used without flatpickr
                 $h = $checkTime['hour'] ?? '00';
                 $m = $checkTime['minute'] ?? '00';
                 $checkTime = "$h:$m:00";
            } elseif ($checkTime instanceof \DateTimeInterface) {
                 $checkTime = $checkTime->format('H:i:s');
            }

            $validStart = new \DateTime($checkDate . ' ' . $checkTime);
            $validEnd = clone $validStart;
            $validEnd->modify('+' . (int)$checkDuration . ' minutes');

            $overlaps = $this->Shows->find()
                ->where([
                    'show_date' => $checkDate,
                    'hall_id' => $checkHall,
                    'status' => 1,
                    'id !=' => $show->id // Exclude current show
                ])
                ->all();

            $isOverlap = false;
            foreach ($overlaps as $existing) {
                $dVal = $existing->show_date instanceof \DateTimeInterface ? $existing->show_date->format('Y-m-d') : $existing->show_date;
                $tVal = $existing->show_time instanceof \DateTimeInterface ? $existing->show_time->format('H:i:s') : $existing->show_time;
                
                $exStart = new \DateTime($dVal . ' ' . $tVal);
                $exDuration = $existing->duration ?? 120;
                $exEnd = clone $exStart;
                $exEnd->modify('+' . (int)$exDuration . ' minutes');

                // Strict Overlap Check
                if ($validStart < $exEnd && $validEnd > $exStart) {
                    $isOverlap = true;
                    // Format for error message
                    $conflictStart = $exStart->format('h:i A');
                    $conflictEnd = $exEnd->format('h:i A');
                    $this->Flash->error(__('Cannot schedule this session. It overlaps with "{0}" in Hall {1} ({2} - {3}).', $existing->show_title, $checkHall, $conflictStart, $conflictEnd));
                    break;
                }
            }

            if (!$isOverlap) {
                $show = $this->Shows->patchEntity($show, $data);
                if ($this->Shows->save($show)) {
                    // If "Apply to all today" is checked, update other sessions too
                    if (!empty($data['apply_to_all_day'])) {
                        $updateFields = [
                            'show_title' => $show->show_title,
                            'genre' => $show->genre,
                            'duration' => $show->duration,
                            'avatar' => $show->avatar,
                            'avatar_dir' => $show->avatar_dir
                        ];
                        
                        // Only add hall_id if it was provided in the post
                        if (!empty($data['hall_id'])) {
                            $updateFields['hall_id'] = $data['hall_id'];
                        }

                        $this->Shows->updateAll(
                            $updateFields,
                            [
                                'show_title' => $show->getOriginal('show_title') ?? $show->show_title,
                                'show_date' => $show->show_date,
                                'id !=' => $show->id
                            ]
                        );
                        $this->Flash->success(__('The show and all related sessions for today have been synchronized.'));
                    } else {
                        $this->Flash->success(__('The show has been updated.'));
                    }

                    if (!empty($data['redirect_to_session'])) {
                        return $this->redirect(['action' => 'edit', $data['redirect_to_session'], '?' => ['view' => $this->request->getQuery('view')]]);
                    }

                    return $this->redirect(['action' => 'index', '?' => ['date' => $show->show_date instanceof \DateTimeInterface ? $show->show_date->format('Y-m-d') : $show->show_date, 'view' => $this->request->getQuery('view')]]);
                }
                $this->Flash->error(__('The show could not be saved. Please, try again.'));
            }
        }

        $halls = $this->Shows->Halls->find('list')->all();

        // Fetch other sessions for the same movie and date for easy switching
        $relatedSessions = $this->Shows->find()
            ->where([
                'show_title' => $show->show_title,
                'show_date' => $show->show_date,
                'id !=' => $show->id
            ])
            ->order(['show_time' => 'ASC'])
            ->all();

        $this->set(compact('show', 'halls', 'relatedSessions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Show id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $show = $this->Shows->get($id);
        $date = $show->show_date;
        $viewContext = $this->request->getQuery('view');

        try {
            if ($this->Shows->delete($show)) {
                $this->Flash->success(__('The show has been deleted.'));
            } else {
                $this->Flash->error(__('The show could not be deleted. Please, try again.'));
            }
        } catch (\PDOException $e) {
            if (strpos($e->getMessage(), 'Constraint violation') !== false || $e->getCode() == '23000') {
                $this->Flash->error(__('Cannot delete this show because there are active bookings associated with it. Please cancel the bookings first.'));
            } else {
                $this->Flash->error(__('An error occurred: ' . $e->getMessage()));
            }
        }

        return $this->_smartRedirect($date, $viewContext);
    }

    /**
     * Delete Group method ("Delete All for this Movie on this Date")
     *
     * @param string|null $id Show id (used to identify title/date).
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function deleteGroup($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $show = $this->Shows->get($id);
        
        $title = $show->show_title;
        $date = $show->show_date;
        $viewContext = $this->request->getQuery('view');

        try {
            $conditions = [
                'show_title' => $title,
                'show_date' => $date
            ];

            // If it's today, we respect the Active/History split
            $dateStr = ($date instanceof \DateTimeInterface) ? $date->format('Y-m-d') : $date;
            if ($dateStr == date('Y-m-d')) {
                $currentTime = date('H:i:s');
                if ($viewContext === 'history') {
                    $conditions['show_time <'] = $currentTime;
                } elseif ($viewContext === 'active') {
                    $conditions['show_time >='] = $currentTime;
                }
            }

            // Delete all matching sessions
            $count = $this->Shows->deleteAll($conditions);

            if ($count > 0) {
                $friendlyDate = ($date instanceof \DateTimeInterface) ? $date->format('d M Y') : $date;
                $contextLabel = ($dateStr == date('Y-m-d') && $viewContext) ? " ($viewContext)" : "";
                $this->Flash->success(__('{0} "{1}" sessions on {2}{3} have been removed.', $count, $title, $friendlyDate, $contextLabel));
            } else {
                $this->Flash->error(__('No sessions were deleted.'));
            }

        } catch (\PDOException $e) {
            if (strpos($e->getMessage(), 'Constraint violation') !== false || $e->getCode() == '23000') {
                $this->Flash->error(__('Cannot delete this movie group because active bookings exist for one or more sessions.'));
            } else {
                $this->Flash->error(__('An error occurred: ' . $e->getMessage()));
            }
        }

        return $this->_smartRedirect($date, $viewContext);
    }

    /**
     * Helper for intelligent redirection after deletion.
     */
    private function _smartRedirect($date, $viewContext)
    {
        $dateStr = ($date instanceof \DateTimeInterface) ? $date->format('Y-m-d') : $date;
        $currentTime = date('H:i:s');

        // 1. Check current context
        $conditions = ['show_date' => $dateStr, 'status' => 1];
        if ($dateStr == date('Y-m-d')) {
            if ($viewContext === 'history') {
                $conditions['show_time <'] = $currentTime;
            } else {
                $conditions['show_time >='] = $currentTime;
            }
        }

        if ($this->Shows->exists($conditions)) {
            return $this->redirect(['action' => 'index', '?' => ['date' => $dateStr, 'view' => $viewContext]]);
        }

        // 2. If current context is empty, check alternate context for today
        if ($dateStr == date('Y-m-d')) {
            $altView = ($viewContext === 'history') ? 'active' : 'history';
            $altConditions = ['show_date' => $dateStr, 'status' => 1];
            if ($altView === 'history') {
                $altConditions['show_time <'] = $currentTime;
            } else {
                $altConditions['show_time >='] = $currentTime;
            }

            if ($this->Shows->exists($altConditions)) {
                return $this->redirect(['action' => 'index', '?' => ['date' => $dateStr, 'view' => $altView]]);
            }
        }

        // 3. Fallback to default (next available date)
        return $this->redirect(['action' => 'index']);
    }
    /**
     * Sync with Master method
     * Refreshes the show-specific seat layout from the Master Hall Template.
     */
    public function syncWithMaster($id = null)
    {
        $this->request->allowMethod(['post']);
        $show = $this->Shows->get($id);
        
        // Check if tickets sold
        $ticketsTable = $this->getTableLocator()->get('Tickets');
        $hasSold = $ticketsTable->exists(['show_id' => $id, 'status' => 1]);
        
        if ($hasSold) {
            $this->Flash->error(__('Cannot sync layout because tickets have already been sold for this session. Modifying the layout now would corrupt existing bookings.'));
            return $this->redirect(['action' => 'edit', $id]);
        }

        try {
            $showSeatsTable = $this->getTableLocator()->get('ShowSeats');
            $seatsTable = $this->getTableLocator()->get('Seats');

            // 1. Delete existing ShowSeats
            $showSeatsTable->deleteAll(['show_id' => $id]);

            // 2. Clone from Master
            $masterSeats = $seatsTable->find()
                ->where(['hall_id' => $show->hall_id])
                ->all();

            $clonedSeats = [];
            foreach ($masterSeats as $mSeat) {
                $clonedSeats[] = $showSeatsTable->newEntity([
                    'show_id' => $id,
                    'seat_row' => $mSeat->seat_row,
                    'seat_number' => $mSeat->seat_number,
                    'seat_type' => $mSeat->seat_type,
                    'seat_price' => $mSeat->seat_price,
                    'status' => $mSeat->status
                ]);
            }

            if (!empty($clonedSeats)) {
                $showSeatsTable->saveMany($clonedSeats);
                $this->Flash->success(__('Layout synchronized with Master Template successfully!'));
            } else {
                $this->Flash->warning(__('No master seats found for this hall to clone.'));
            }
        } catch (\Exception $e) {
            $this->Flash->error(__('Error during synchronization: ' . $e->getMessage()));
        }

        return $this->redirect(['action' => 'edit', $id]);
    }
}
