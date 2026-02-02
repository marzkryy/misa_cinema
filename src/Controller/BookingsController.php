<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Bookings Controller
 *
 * @property \App\Model\Table\BookingsTable $Bookings
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BookingsController extends AppController
{
    use \Cake\Mailer\MailerAwareTrait;
    /**
     * Index method
     */
    public function index()
    {
        $authUser = $this->request->getSession()->read('Auth.User');
        $isAdmin = isset($authUser['role']) && $authUser['role'] === 'admin';
        $query = $this->Bookings->find();

        // If not admin, only show your own bookings
        // If not admin, only show your own bookings
        $activeBookings = [];
        $historyBookings = [];
        $searchDate = null;
        $searchQuery = null;
        $searchCustomer = null;

        if (isset($authUser['role']) && $authUser['role'] !== 'admin') {
            // Customer: Split into Active and History
            $commonOptions = [
                'contain' => ['Customers', 'Shows', 'Halls', 'Seats', 'Tickets' => ['Seats', 'ShowSeats']],
                'order' => ['Bookings.id' => 'DESC']
            ];

            $nowDate = date('Y-m-d');
            $nowTime = date('H:i:s');

            $activeBookings = $this->Bookings->find('all', $commonOptions)
                ->where([
                    'cust_id' => $authUser['id'],
                    'OR' => [
                        ['Shows.show_date >' => $nowDate],
                        [
                            'Shows.show_date' => $nowDate,
                            'Shows.show_time >' => $nowTime
                        ]
                    ]
                ])
                ->all();

            $historyBookings = $this->Bookings->find('all', $commonOptions)
                ->where([
                    'cust_id' => $authUser['id'],
                    'OR' => [
                        ['Shows.show_date <' => $nowDate],
                        [
                            'Shows.show_date' => $nowDate,
                            'Shows.show_time <=' => $nowTime
                        ]
                    ]
                ])
                ->all();
            
            // For customers, we don't use the main pagination query variable
            $bookings = []; 
        } else {
            // Admin: Standard Pagination with Filters
            $searchDate = $this->request->getQuery('show_date');
            $searchQuery = $this->request->getQuery('search_query');
            $searchCustomer = $this->request->getQuery('search_customer');

            if ($searchDate) {
                $query->where(['Shows.show_date' => $searchDate]);
            }

            if ($searchQuery) {
                $query->where(['Shows.show_title LIKE' => '%' . $searchQuery . '%']);
            }

            if ($searchCustomer) {
                $query->innerJoinWith('Customers')->where([
                    'OR' => [
                        'Customers.name LIKE' => '%' . $searchCustomer . '%',
                        'Customers.email LIKE' => '%' . $searchCustomer . '%',
                    ]
                ]);
            }

            $this->paginate = [
                'contain' => ['Customers', 'Shows', 'Halls', 'Seats', 'Tickets' => ['Seats', 'ShowSeats']],
                'order' => ['Bookings.id' => 'DESC']
            ];
            $bookings = $this->paginate($query);
        }

        // Fetch ALL tickets and HALL LAYOUTS for the shows displayed on this page
        $allTickets = [];
        $groupedSeatsByShow = [];
        if ($isAdmin && !$bookings->isEmpty()) {
            $showIds = array_unique(array_map(function ($b) {
                return $b->show_id;
            }, $bookings->toArray()));
            if (!empty($showIds)) {
                // Fetch Sold Tickets
                $allTickets = $this->getTableLocator()->get('Tickets')->find()
                    ->where(['Tickets.show_id IN' => $showIds, 'Tickets.status' => 1])
                    ->contain(['ShowSeats', 'Bookings.Customers'])
                    ->all();

                // Fetch Show-Specific Seats (Dynamic Layout)
                $allShowSeats = $this->getTableLocator()->get('ShowSeats')->find()
                    ->where(['show_id IN' => $showIds])
                    ->order(['seat_row' => 'ASC', 'LENGTH(seat_number)' => 'ASC', 'seat_number' => 'ASC'])
                    ->all();
                
                foreach ($allShowSeats as $seat) {
                    $groupedSeatsByShow[$seat->show_id][$seat->seat_row][$seat->seat_number] = $seat;
                }
            }
        }

        $this->set(compact('bookings', 'activeBookings', 'historyBookings', 'isAdmin', 'allTickets', 'groupedSeatsByShow', 'searchDate', 'searchQuery', 'searchCustomer'));
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        $booking = $this->Bookings->get($id, [
            'contain' => ['Customers', 'Shows', 'Payments', 'Tickets' => ['Seats', 'ShowSeats'], 'Halls', 'Seats'],
        ]);
        $this->set(compact('booking'));
    }

    /**
     * Add method (Admin side)
     */
    public function add()
    {
        $booking = $this->Bookings->newEmptyEntity();
        $authUser = $this->request->getSession()->read('Auth.User');
        $booking->cust_id = $authUser['id'] ?? null;

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (isset($authUser['id'])) {
                $data['cust_id'] = $authUser['id'];
            }
            $booking = $this->Bookings->patchEntity($booking, $data);
            if ($this->Bookings->save($booking)) {
                $this->Flash->success(__('The booking has been saved. Please complete your payment.'));
                return $this->redirect(['action' => 'payment', $booking->id]);
            }
            $this->Flash->error(__('The booking could not be saved.'));
        }

        $halls = $this->getTableLocator()->get('Halls')->find('list', ['keyField' => 'id', 'valueField' => 'hall_type'])->all();
        $seatsData = $this->getTableLocator()->get('Seats')->find('all')->toArray();
        $seats = $this->getTableLocator()->get('Seats')->find('list', ['keyField' => 'id', 'valueField' => 'seat_type'])->all();
        $customers = $this->Bookings->Customers->find('list')->all();
        $shows = $this->Bookings->Shows->find('list')->all();
        $seatsJson = json_encode($seatsData);

        $this->set(compact('booking', 'customers', 'shows', 'halls', 'seats', 'seatsJson'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        $booking = $this->Bookings->get($id, ['contain' => []]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $booking = $this->Bookings->patchEntity($booking, $this->request->getData());
            if ($this->Bookings->save($booking)) {
                $this->Flash->success(__('The booking has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The booking could not be saved. Please, try again.'));
        }
        $customers = $this->Bookings->Customers->find('list', ['limit' => 200])->all();
        $shows = $this->Bookings->Shows->find('list', ['limit' => 200])->all();
        $halls = $this->Bookings->Halls->find('list', ['limit' => 200])->all();
        $seats = $this->Bookings->Seats->find('list', ['limit' => 200])->all();
        $this->set(compact('booking', 'customers', 'shows', 'halls', 'seats'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $booking = $this->Bookings->get($id);
        if ($this->Bookings->delete($booking)) {
            $this->Flash->success(__('The booking has been deleted.'));
        } else {
            $this->Flash->error(__('The booking could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Choose Seat method (Customer side)
     */
    public function chooseSeat($showId = null)
    {
        // Require Login for Booking
        if (!$this->request->getSession()->check('Auth.User')) {
            $this->Flash->error(__('Please login to book tickets.'));
            return $this->redirect(['controller' => 'Customers', 'action' => 'login']);
        }

        try {
            $show = $this->Bookings->Shows->get($showId, ['contain' => ['Halls']]);
            
            // Clean up old locks
            $locksTable = $this->getTableLocator()->get('SeatLocks');
            $locksTable->deleteAll(['expires_at <' => \Cake\I18n\FrozenTime::now()]);

            $soldTickets = $this->getTableLocator()->get('Tickets')->find()
                ->where(['Tickets.show_id' => $showId])
                ->contain(['ShowSeats'])
                ->all();

            $soldSeats = [];
            foreach ($soldTickets as $ticket) {
                if ($ticket->show_seat) {
                    $soldSeats[] = $ticket->show_seat->seat_row . $ticket->show_seat->seat_number;
                }
            }

            // Fetch active locks by others
            $userId = $this->request->getSession()->read('Auth.User.id');
            $now = \Cake\I18n\FrozenTime::now();

            $lockedByOthers = $locksTable->find()
                ->where([
                    'show_id' => $showId,
                    'user_id !=' => $userId,
                    'expires_at >' => $now
                ])
                ->extract('seat_label')
                ->toArray();

            // Fetch active locks by current user to restore them on refresh
            $lockedMeQuery = $locksTable->find()
                ->where([
                    'show_id' => $showId,
                    'user_id' => $userId,
                    'expires_at >' => $now
                ]);
            
            $lockedByMe = $lockedMeQuery->extract('seat_label')->toArray();
            
            // Get earliest expiry for the timer
            $earliestExpiry = $lockedMeQuery->select(['earliest' => 'MIN(expires_at)'])->first();
            $timerSeconds = 0;
            if ($earliestExpiry && $earliestExpiry->earliest) {
                $expiryTime = new \Cake\I18n\FrozenTime($earliestExpiry->earliest);
                $timerSeconds = max(0, $expiryTime->getTimestamp() - $now->getTimestamp());
            }

            // Fetch all seats for this SPECIFIC SHOW to build the map dynamically
            $showSeatsTable = $this->getTableLocator()->get('ShowSeats');
            $hallSeats = $showSeatsTable->find()
                ->where(['show_id' => $showId])
                ->order(['seat_row' => 'ASC', 'LENGTH(seat_number)' => 'ASC', 'seat_number' => 'ASC'])
                ->all();

            $groupedSeats = [];
            foreach ($hallSeats as $seat) {
                // Compatibility: Ensure keys match expected structure in template
                $groupedSeats[$seat->seat_row][$seat->seat_number] = $seat;
            }
            $this->set(compact('show', 'soldSeats', 'lockedByOthers', 'lockedByMe', 'timerSeconds', 'groupedSeats'));
        } catch (\Exception $e) {
            $this->Flash->error(__('The selected movie showtime could not be found.'));
            return $this->redirect('/');
        }
    }

    /**
     * AJAX Lock Seat
     */
    public function ajaxLockSeat()
    {
        $this->request->allowMethod(['post']);
        $showId = $this->request->getData('show_id');
        $seatLabel = $this->request->getData('seat_label');
        $action = $this->request->getData('lock_action'); // 'lock' or 'unlock'
        $userId = $this->request->getSession()->read('Auth.User.id');
        $sessionId = $this->request->getSession()->id();

        if (!$showId || !$seatLabel) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'error', 'message' => 'Missing parameters']));
        }

        $locksTable = $this->getTableLocator()->get('SeatLocks');

        if ($action === 'unlock') {
            $locksTable->deleteAll(['show_id' => $showId, 'seat_label' => $seatLabel, 'user_id' => $userId]);
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'success', 'message' => 'Seat unlocked']));
        }

        // Check if sold
        preg_match('/([A-Z]+)(\d+)/', $seatLabel, $matches);
        if (count($matches) === 3) {
            $row = $matches[1];
            $num = $matches[2];
            $ticketsTable = $this->getTableLocator()->get('Tickets');
            $isSold = $ticketsTable->find()
                ->innerJoinWith('Seats')
                ->where([
                    'Tickets.show_id' => $showId,
                    'Seats.seat_row' => $row,
                    'Seats.seat_number' => $num,
                    'Tickets.status' => 1
                ])
                ->count() > 0;

            if ($isSold) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode(['status' => 'locked', 'message' => 'Seat has just been sold.']));
            }
        }

        // Check if locked by someone else
        $existingLock = $locksTable->find()
            ->where([
                'show_id' => $showId,
                'seat_label' => $seatLabel,
                'user_id !=' => $userId,
                'expires_at >' => \Cake\I18n\FrozenTime::now()
            ])
            ->first();

        if ($existingLock) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'locked', 'message' => 'Seat is currently reserved by another user']));
        }

        // Create or Update Lock
        $lock = $locksTable->find()
            ->where(['show_id' => $showId, 'seat_label' => $seatLabel, 'user_id' => $userId])
            ->first() ?: $locksTable->newEmptyEntity();

        $lock->show_id = $showId;
        $lock->user_id = $userId;
        $lock->seat_label = $seatLabel;
        $lock->session_id = $sessionId;
        $lock->expires_at = \Cake\I18n\FrozenTime::now()->addMinutes(10);

        if ($locksTable->save($lock)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'success', 'expires_at' => $lock->expires_at->format('Y-m-d H:i:s')]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['status' => 'error', 'message' => 'Failed to lock seat']));
    }

    /**
     * AJAX Refresh Locks
     */
    public function ajaxRefreshLocks($showId)
    {
        $this->request->allowMethod(['get']);
        $userId = $this->request->getSession()->read('Auth.User.id');
        $locksTable = $this->getTableLocator()->get('SeatLocks');
        
        // Clean up
        $locksTable->deleteAll(['expires_at <' => \Cake\I18n\FrozenTime::now()]);

        $locks = $locksTable->find()
            ->where([
                'show_id' => $showId,
                'user_id !=' => $userId,
                'expires_at >' => \Cake\I18n\FrozenTime::now()
            ])
            ->extract('seat_label')
            ->toArray();

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['status' => 'success', 'locked_seats' => $locks]));
    }

    /**
     * Process Booking method
     */
    public function processBooking()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $showId = $data['show_id'] ?? null;
            $selectedSeats = explode(',', $data['selected_seats'] ?? '');

            if (!$showId || empty($data['selected_seats'])) {
                $this->Flash->error(__('Please select at least one seat.'));
                return $this->redirect(['action' => 'chooseSeat', $showId]);
            }

            // --- CHECK AVAILABILITY ---
            $locksTable = $this->getTableLocator()->get('SeatLocks');
            $ticketsTable = $this->getTableLocator()->get('Tickets');
            $seatsTable = $this->getTableLocator()->get('Seats');
            $sessionId = $this->request->getSession()->id();

            foreach ($selectedSeats as $seatLabel) {
                preg_match('/([A-Z]+)(\d+)/', $seatLabel, $matches);
                if (count($matches) === 3) {
                    $row = $matches[1];
                    $num = $matches[2];

                    // Check if sold
                    $isSold = $ticketsTable->find()
                        ->innerJoinWith('ShowSeats')
                        ->where([
                            'Tickets.show_id' => $showId,
                            'ShowSeats.seat_row' => $row,
                            'ShowSeats.seat_number' => $num,
                            'Tickets.status' => 1
                        ])->count() > 0;

                    if ($isSold) {
                        $this->Flash->error(__("Seat {0} was just sold. Please select another seat.", $seatLabel));
                        return $this->redirect(['action' => 'chooseSeat', $showId]);
                    }

                    // Check if locked by others
                    $isLockedByOthers = $locksTable->find()
                        ->where([
                            'show_id' => $showId,
                            'seat_label' => $seatLabel,
                            'user_id !=' => $this->request->getSession()->read('Auth.User.id'),
                            'expires_at >' => \Cake\I18n\FrozenTime::now()
                        ])->count() > 0;

                    if ($isLockedByOthers) {
                        $this->Flash->error(__("Seat {0} is currently reserved by another user.", $seatLabel));
                        return $this->redirect(['action' => 'chooseSeat', $showId]);
                    }
                }
            }

            // Create Booking - BUT FIRST Check if user already has a PENDING booking for this exact selection
        $custId = $this->request->getSession()->read('Auth.User.id');
        if (!$custId) {
            $firstCust = $this->Bookings->Customers->find()->select(['id'])->first();
            $custId = $firstCust ? $firstCust->id : null;
        }

        $existingBooking = $this->Bookings->find()
            ->where([
                'cust_id' => $custId,
                'show_id' => $showId,
                'seat_selection' => $data['selected_seats'],
                'status' => 0
            ])->first();

        if ($existingBooking) {
            // Already has a pending booking for this, just redirect to confirm it
            return $this->redirect(['action' => 'confirm', $existingBooking->id]);
        }

        $booking = $this->Bookings->newEmptyEntity();

            if (!$custId) {
                $this->Flash->error(__('Error: No customer account found. Please register or login first.'));
                return $this->redirect(['action' => 'chooseSeat', $showId]);
            }

            $booking->cust_id = $custId;
            $booking->show_id = $showId;

            // Fetch Show to get its Hall ID
            $show = $this->Bookings->Shows->get($showId, ['contain' => ['Halls']]);

            // Fallbacks for integrity
            $firstHall = $this->getTableLocator()->get('Halls')->find()->select(['id'])->first();
            $firstSeat = $this->getTableLocator()->get('Seats')->find()->select(['id'])->first();

            $booking->hall_id = $show->hall_id ?: ($firstHall ? $firstHall->id : null);
            $booking->seat_id = $firstSeat ? $firstSeat->id : null;

            if (!$booking->hall_id || !$booking->seat_id) {
                $this->Flash->error(__('System Error: Halls or Seats are not configured in the database.'));
                return $this->redirect(['action' => 'chooseSeat', $showId]);
            }

            // --- CALCULATE TOTAL PRICE BASED ON INDIVIDUAL SEAT PRICES ---
            $totalPrice = 0;
            foreach ($selectedSeats as $seatLabel) {
                preg_match('/([A-Z]+)(\d+)/', $seatLabel, $matches);
                if (count($matches) === 3) {
                    $row = $matches[1];
                    $num = $matches[2];
                    $seatRecord = $this->getTableLocator()->get('ShowSeats')->find()
                        ->where([
                            'show_id' => $showId,
                            'seat_row' => $row,
                            'seat_number' => $num
                        ])->first();
                    
                    if ($seatRecord) {
                        $totalPrice += (float)$seatRecord->seat_price;
                    } else {
                        // Safe fallback
                        $totalPrice += 15.00; 
                    }
                }
            }

            $booking->quantity = count($selectedSeats);
            $booking->ticket_price = (string)$totalPrice;
            $booking->book_date_time = \Cake\I18n\FrozenTime::now();
            $booking->status = 0; // Pending Payment
            $booking->seat_selection = $data['selected_seats']; // Persist seat selection

            if ($this->Bookings->save($booking)) {
                // Session backup still useful but DB is primary
                $this->request->getSession()->write('Booking.pending_seats', $selectedSeats);
                return $this->redirect(['action' => 'confirm', $booking->id]);
            }

            $errors = $booking->getErrors();
            $msg = 'Save failed. ';
            if ($errors)
                $msg .= json_encode($errors);
            $this->Flash->error(__($msg));
            return $this->redirect(['action' => 'chooseSeat', $showId]);
        }
        return $this->redirect('/');
    }

    /**
     * Confirm Booking method
     */
    public function confirm($id = null)
    {
        $booking = $this->Bookings->get($id, ['contain' => ['Shows', 'Halls', 'Seats']]);

        // If already paid, redirect to receipt
        if ($booking->status == 1) {
            return $this->redirect(['action' => 'receipt', $booking->id]);
        }

        // Check Student Promo Eligibility
        $showTime = ($booking->show->show_time instanceof \DateTimeInterface) ? $booking->show->show_time : new \DateTime($booking->show->show_time);
        $showDate = ($booking->show->show_date instanceof \DateTimeInterface) ? $booking->show->show_date : new \DateTime($booking->show->show_date);
        
        // Combine date and time to check day of week correctly if needed, but show_date usually suffices
        $dayOfWeek = $showDate->format('N'); // 1 (Mon) to 7 (Sun)
        $hour = (int)$showTime->format('H');

        $isStudentEligible = ($dayOfWeek >= 1 && $dayOfWeek <= 5) && ($hour < 18);

        // EXTRA CHECK: Seat Type
        // If any selected seat is 'Couple' or 'Gold', disable discount
        if ($isStudentEligible && !empty($booking->seat_selection)) {
             $selectedSeats = explode(',', $booking->seat_selection);
             $showSeatsTable = $this->getTableLocator()->get('ShowSeats');
             
             foreach ($selectedSeats as $seatLabel) {
                preg_match('/([A-Z]+)(\d+)/', $seatLabel, $matches);
                if (count($matches) === 3) {
                    $row = $matches[1];
                    $num = $matches[2];
                    $seat = $showSeatsTable->find()
                        ->where(['show_id' => $booking->show_id, 'seat_row' => $row, 'seat_number' => $num])
                        ->first();
                    
                    // ALLOW ONLY STANDARD
                    if ($seat && strtolower($seat->seat_type) !== 'standard') {
                        $isStudentEligible = false;
                        break;
                    }
                }
             }
        }

        if ($this->request->is('post')) {
            $params = [];
            if ($this->request->getData('is_student') == '1' && $isStudentEligible) {
                $params['student'] = 1;
            }
            return $this->redirect(['action' => 'payment', $id, '?' => $params]);
        }
        $this->set(compact('booking', 'isStudentEligible'));
    }

    public function payment($id = null)
    {
        $booking = $this->Bookings->get($id, ['contain' => ['Shows', 'Customers']]);

        // If already paid, redirect to receipt
        if ($booking->status == 1) {
            return $this->redirect(['action' => 'receipt', $booking->id]);
        }

        // --- APPLY STUDENT DISCOUNT IF VALID (FOR BOTH VIEW AND SAVE) ---
        if ($this->request->getQuery('student')) {
            // Re-verify eligibility for security
            $showTime = ($booking->show->show_time instanceof \DateTimeInterface) ? $booking->show->show_time : new \DateTime($booking->show->show_time);
            $showDate = ($booking->show->show_date instanceof \DateTimeInterface) ? $booking->show->show_date : new \DateTime($booking->show->show_date);
            $dayOfWeek = $showDate->format('N');
            $hour = (int)$showTime->format('H');

            if (($dayOfWeek >= 1 && $dayOfWeek <= 5) && ($hour < 18)) {
                // EXTRA SECURITY CHECK: Seat Type
                $validSeatType = true;
                if (!empty($booking->seat_selection)) {
                    $selectedSeats = explode(',', $booking->seat_selection);
                    $showSeatsTable = $this->getTableLocator()->get('ShowSeats');

                    foreach ($selectedSeats as $seatLabel) {
                        preg_match('/([A-Z]+)(\d+)/', $seatLabel, $matches);
                        if (count($matches) === 3) {
                            $row = $matches[1];
                            $num = $matches[2];
                            $seat = $showSeatsTable->find()
                                ->where(['show_id' => $booking->show_id, 'seat_row' => $row, 'seat_number' => $num])
                                ->first();

                            // ALLOW ONLY STANDARD
                            if ($seat && strtolower($seat->seat_type) !== 'standard') {
                                $validSeatType = false;
                                break;
                            }
                        }
                    }
                }

                if ($validSeatType) {
                    $originalPrice = (float)$booking->ticket_price;
                    $discountedPrice = $originalPrice * 0.80; // 20% Off
                    $booking->ticket_price = (string)$discountedPrice;
                    $booking->is_student = true; // Set flag
                }
            }
        }

        if ($this->request->is('post')) {
            $ticketsSavedCount = 0;
            // Set status to confirmed temporarily to try saving tickets
            $booking->status = 1;

            if ($this->Bookings->save($booking)) {
                // 1. Try to get seats from Database (PRIMARY)
                $seatsString = $booking->seat_selection;
                $pendingSeats = [];

                if (!empty($seatsString)) {
                    $pendingSeats = explode(',', $seatsString);
                } else {
                    // 2. Fallback to Session (LEGACY)
                    $pendingSeats = $this->request->getSession()->read('Booking.pending_seats');
                }

                if ($pendingSeats) {
                    $ticketsTable = $this->getTableLocator()->get('Tickets');
                    $showSeatsTable = $this->getTableLocator()->get('ShowSeats');
                    foreach ($pendingSeats as $seatLabel) {
                        preg_match('/([A-Z]+)(\d+)/', $seatLabel, $matches);
                        if (count($matches) === 3) {
                            $row = $matches[1];
                            $num = $matches[2];
                            // Fetch from Show-Specific Seats
                            $showSeat = $showSeatsTable->find()
                                ->where(['show_id' => $booking->show_id, 'seat_row' => $row, 'seat_number' => $num])
                                ->first();
                                
                            if ($showSeat) {
                                // 1. Prevent duplicate tickets for same booking/seat
                                $existsInThisBooking = $ticketsTable->exists([
                                    'booking_id' => $booking->id,
                                    'show_id' => $booking->show_id,
                                    'show_seat_id' => $showSeat->id
                                ]);

                                // 2. CRITICAL: Prevent duplicate tickets across ALL bookings (Global Availability Check)
                                $isAlreadySoldGlobally = $ticketsTable->exists([
                                    'show_id' => $booking->show_id,
                                    'show_seat_id' => $showSeat->id,
                                    'status' => 1
                                ]);

                                if (!$existsInThisBooking && !$isAlreadySoldGlobally) {
                                    $ticket = $ticketsTable->newEmptyEntity();
                                    $ticket->booking_id = $booking->id;
                                    $ticket->show_id = $booking->show_id;
                                    $ticket->hall_id = $booking->hall_id;
                                    $ticket->show_seat_id = $showSeat->id;
                                    // Fallback: try to find master seat for legacy seat_id if possible
                                    $masterSeat = $this->getTableLocator()->get('Seats')->find()
                                        ->where(['hall_id' => $booking->hall_id, 'seat_row' => $row, 'seat_number' => $num])
                                        ->first();
                                    if ($masterSeat) {
                                        $ticket->seat_id = $masterSeat->id;
                                    }
                                    
                                    $ticket->status = 1;
                                    if ($ticketsTable->save($ticket)) {
                                        $ticketsSavedCount++;
                                    }
                                }
                            }
                        }
                    }

                    if ($ticketsSavedCount === 0) {
                        // NO TICKETS CREATED (likely already sold globally)
                        $booking->status = 0; // Revert to pending
                        $this->Bookings->save($booking);
                        $this->Flash->error(__('Payment failed: One or more selected seats were just sold to another customer. Please select different seats.'));
                        return $this->redirect(['action' => 'chooseSeat', $booking->show_id]);
                    }

                    // 3. Save Payment Record
                    $payment_method = $this->request->getData('payment_method') ?: 'Online Banking (FPX)';
                    $paymentsTable = $this->getTableLocator()->get('Payments');
                    $payment = $paymentsTable->newEmptyEntity();
                    $payment->booking_id = $booking->id;
                    $payment->payment_date_time = new \Cake\I18n\FrozenTime();
                    $payment->payment_method = $payment_method;
                    $payment->total_price = $booking->ticket_price;
                    $payment->status = 1; // Success
                    $paymentsTable->save($payment);

                    // Cleanup
                    $this->request->getSession()->delete('Booking.pending_seats');
                    
                    // Cleanup Locks
                    $this->getTableLocator()->get('SeatLocks')->deleteAll([
                        'show_id' => $booking->show_id,
                        'user_id' => $this->request->getSession()->read('Auth.User.id')
                    ]);

                    // Send Receipt Email
                    try {
                        // Reload booking with necessary associations for the email
                        $bookingWithData = $this->Bookings->get($booking->id, [
                            'contain' => ['Shows', 'Customers', 'Halls', 'Payments', 'Tickets' => ['ShowSeats']]
                        ]);
                        $this->getMailer('User')->send('bookingReceipt', [$bookingWithData]);
                    } catch (\Exception $e) {
                        // Log error but don't stop the flow
                        $this->log('Email receipt failed: ' . $e->getMessage());
                        $this->log($e->getTraceAsString());
                    }
                }
                $this->Flash->success(__('Payment successful! A copy of your receipt has been sent to your email.'));
                return $this->redirect(['action' => 'receipt', $booking->id]);
            }
            $this->Flash->error(__('Payment failed.'));
        }
        $this->set(compact('booking'));
    }

    public function receipt($id = null)
    {
        $booking = $this->Bookings->get($id, ['contain' => ['Shows', 'Customers', 'Halls', 'Payments', 'Tickets' => ['ShowSeats', 'Seats', 'Halls']]]);
        
        // Security check: Only allow the owner or admin to see the receipt
        $authUser = $this->request->getSession()->read('Auth.User');
        if ($authUser['role'] !== 'admin' && $booking->cust_id !== $authUser['id']) {
            $this->Flash->error(__('You are not authorized to view this receipt.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }

        $this->set(compact('booking'));
    }
}
