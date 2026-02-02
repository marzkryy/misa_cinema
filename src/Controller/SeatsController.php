<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Seats Controller
 *
 * @property \App\Model\Table\SeatsTable $Seats
 * @method \App\Model\Entity\Seat[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SeatsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $hallId = $this->request->getQuery('hall_id');
        $halls = $this->Seats->Halls->find('list')->all()->toArray();
        
        // Default to first hall if none selected
        if (!$hallId && !empty($halls)) {
            $hallId = array_key_first($halls);
        }

        $query = $this->Seats->find()
            ->where(['hall_id' => $hallId])
            ->order(['seat_row' => 'ASC', 'LENGTH(seat_number)' => 'ASC', 'seat_number' => 'ASC']);
        
        $seats = $query->all();
        
        // Group seats by row for the grid
        $groupedSeats = [];
        foreach ($seats as $seat) {
            $groupedSeats[$seat->seat_row][$seat->seat_number] = $seat;
        }

        $this->set(compact('groupedSeats', 'halls', 'hallId'));
    }

    /**
     * AJAX Toggle Status method
     */
    public function ajaxToggleStatus($id = null)
    {
        $this->request->allowMethod(['post']);
        $seat = $this->Seats->get($id);
        
        $seat->status = ($seat->status == 1) ? 0 : 1;
        
        if ($this->Seats->save($seat)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'success', 'new_status' => $seat->status]));
        }

        return $this->response->withStatus(400)
            ->withType('application/json')
            ->withStringBody(json_encode(['status' => 'error', 'message' => 'Could not update seat status']));
    }

    /**
     * AJAX Toggle Row Status method
     */
    public function ajaxToggleRowStatus()
    {
        $this->request->allowMethod(['post']);
        $hallId = $this->request->getData('hall_id');
        $rowLabel = $this->request->getData('row_label');
        
        // Find current status of first seat to toggle all
        $firstSeat = $this->Seats->find()
            ->where(['hall_id' => $hallId, 'seat_row' => $rowLabel])
            ->first();
            
        if ($firstSeat) {
            $newStatus = ($firstSeat->status == 1) ? 0 : 1;
            $this->Seats->updateAll(
                ['status' => $newStatus],
                ['hall_id' => $hallId, 'seat_row' => $rowLabel]
            );
            
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'success', 'new_status' => $newStatus]));
        }

        return $this->response->withStatus(400)
            ->withType('application/json')
                ->withStringBody(json_encode(['status' => 'error', 'message' => 'Row not found']));
    }

    /**
     * AJAX Update Row Details method
     */
    public function ajaxUpdateRowDetails()
    {
        $this->request->allowMethod(['post']);
        $hallId = $this->request->getData('hall_id');
        $rowLabel = $this->request->getData('row_label');
        $seatType = $this->request->getData('seat_type');
        $seatPrice = $this->request->getData('seat_price');
        $updateGlobal = $this->request->getData('update_global') === 'true';

        if ($hallId && $rowLabel && $seatType && $seatPrice) {
            if ($updateGlobal) {
                // Update all seats of THIS type in THIS hall
                $updatedCount = $this->Seats->updateAll(
                    ['seat_price' => $seatPrice],
                    ['hall_id' => $hallId, 'seat_type' => $seatType]
                );
                $message = __('GLOBAL SYNC: All {0} seats in this hall have been updated to RM {1}.', h($seatType), $seatPrice);
            } else {
                // Update only this specific row
                $updatedCount = $this->Seats->updateAll(
                    ['seat_type' => $seatType, 'seat_price' => $seatPrice],
                    ['hall_id' => $hallId, 'seat_row' => $rowLabel]
                );
                $message = __('{0} seats in Row {1} have been updated.', $updatedCount, $rowLabel);
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'success', 'message' => $message]));
        }

        return $this->response->withStatus(400)
            ->withType('application/json')
            ->withStringBody(json_encode(['status' => 'error', 'message' => 'Invalid data provided']));
    }

    /**
     * View method
     *
     * @param string|null $id Seat id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $seat = $this->Seats->get($id, [
            'contain' => ['Halls', 'Tickets'],
        ]);

        $this->set(compact('seat'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $seat = $this->Seats->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $seatNumberInput = $data['seat_number'] ?? '';
            
            // Logic to parse multiple seat numbers (e.g., "1, 2, 5" or "1-10")
            $numbers = [];
            $parts = explode(',', $seatNumberInput);
            foreach ($parts as $part) {
                $part = trim($part);
                if (empty($part)) continue;

                if (strpos($part, '-') !== false) {
                    $rangeParts = explode('-', $part);
                    if (count($rangeParts) == 2) {
                        $start = (int)$rangeParts[0];
                        $end = (int)$rangeParts[1];
                        // Ensure logical range
                        if ($start <= $end) {
                            for ($i = $start; $i <= $end; $i++) {
                                $numbers[] = (string)$i;
                            }
                        } else {
                            $numbers[] = $part; // Treat as literal if range is invalid
                        }
                    } else {
                        $numbers[] = $part;
                    }
                } else {
                    $numbers[] = $part;
                }
            }

            // Deduplicate
            $numbers = array_unique($numbers);

            if (count($numbers) > 1) {
                // Bulk Save
                $entities = [];
                foreach ($numbers as $num) {
                    $newSeat = $this->Seats->newEmptyEntity();
                    $newSeat = $this->Seats->patchEntity($newSeat, $data);
                    $newSeat->seat_number = $num;
                    $entities[] = $newSeat;
                }
                if ($this->Seats->saveMany($entities)) {
                    $this->Flash->success(__('{0} seats have been created successfully.', count($entities)));
                    return $this->redirect(['action' => 'index', '?' => ['hall_id' => $data['hall_id']]]);
                }
            } else {
                // Single Save (Original behavior if only 1 number or simple text)
                $seat = $this->Seats->patchEntity($seat, $data);
                if ($this->Seats->save($seat)) {
                    $this->Flash->success(__('The seat has been saved.'));
                    return $this->redirect(['action' => 'index', '?' => ['hall_id' => $data['hall_id']]]);
                }
            }
            $this->Flash->error(__('The seat could not be saved. Please, try again.'));
        }
        $halls = $this->Seats->Halls->find('list', ['limit' => 200])->all();
        $this->set(compact('seat', 'halls'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Seat id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $seat = $this->Seats->get($id, [
            'contain' => ['Halls'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $seat = $this->Seats->patchEntity($seat, $data);
            
            if ($this->Seats->save($seat)) {
                // Synchronization Logic
                $syncScope = $data['sync_scope'] ?? 'none';
                
                if ($syncScope !== 'none') {
                    $conditions = [
                        'seat_type' => $seat->seat_type,
                        'id !=' => $seat->id
                    ];

                    if ($syncScope === 'hall') {
                        $conditions['hall_id'] = $seat->hall_id;
                        $msg = __('The seat and all other {0} seats in THIS hall have been updated.', h($seat->seat_type));
                    } else {
                        $msg = __('GLOBAL UPDATE SUCCESS: All {0} seats across ALL halls have been updated to RM {1}.', strtoupper(h($seat->seat_type)), $seat->seat_price);
                    }

                    $this->Seats->updateAll(
                        ['seat_price' => $seat->seat_price],
                        $conditions
                    );
                    $this->Flash->success($msg);
                } else {
                    $this->Flash->success(__('The seat has been saved.'));
                }

                return $this->redirect(['action' => 'index', '?' => ['hall_id' => $seat->hall_id]]);
            }
            $this->Flash->error(__('The seat could not be saved. Please, try again.'));
        }
        $halls = $this->Seats->Halls->find('list', ['limit' => 200])->all();
        $this->set(compact('seat', 'halls'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Seat id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $seat = $this->Seats->get($id);
        $hallId = $seat->hall_id;
        try {
            // Master Control: Set linked tickets seat_id to null before deleting the master seat
            $this->getTableLocator()->get('Tickets')->updateAll(
                ['seat_id' => null],
                ['seat_id' => $id]
            );

            if ($this->Seats->delete($seat)) {
                $this->Flash->success(__('The master seat template has been deleted. Historical bookings are preserved via ShowSeats.'));
            } else {
                $this->Flash->error(__('The seat could not be deleted. Please, try again.'));
            }
        } catch (\Exception $e) {
            $this->Flash->error(__('Error during Master Control deletion: ' . $e->getMessage()));
        }

        return $this->redirect(['action' => 'index', '?' => ['hall_id' => $hallId]]);
    }

    /**
     * Bulk Delete method
     *
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function bulkDelete()
    {
        $this->request->allowMethod(['post', 'delete']);
        $ids = $this->request->getData('ids');
        
        if (!empty($ids) && is_array($ids)) {
            $deletedCount = 0;
            // Master Control: Decouple Tickets
            $this->getTableLocator()->get('Tickets')->updateAll(
                ['seat_id' => null],
                ['seat_id IN' => $ids]
            );

            foreach ($ids as $id) {
                try {
                    $seat = $this->Seats->get($id);
                    if ($this->Seats->delete($seat)) {
                        $deletedCount++;
                    }
                } catch (\Exception $e) {
                    // Fail silently or log if needed
                }
            }
            
            if ($deletedCount > 0) {
                $this->Flash->success(__('{0} master seats have been successfully deleted.', $deletedCount));
            } else {
                $this->Flash->error(__('No seats could be deleted.'));
            }
        } else {
            $this->Flash->error(__('No seats were selected for deletion.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete All In Hall method
     */
    public function deleteAllInHall()
    {
        $this->request->allowMethod(['post', 'delete']);
        $hallId = $this->request->getQuery('hall_id');
        
        if ($hallId) {
            try {
                // Master Control: Decouple Tickets for all seats in this hall
                $seatIds = $this->Seats->find()->where(['hall_id' => $hallId])->extract('id')->toArray();
                if (!empty($seatIds)) {
                    $this->getTableLocator()->get('Tickets')->updateAll(
                        ['seat_id' => null],
                        ['seat_id IN' => $seatIds]
                    );
                }

                $deleted = $this->Seats->deleteAll(['hall_id' => $hallId]);
                if ($deleted > 0) {
                    $this->Flash->success(__('Hall reset SUCCESS! {0} master seats have been deleted. You can now generate a new layout.', $deleted));
                } else {
                    $this->Flash->info(__('No seats found to delete in this hall.'));
                }
            } catch (\Exception $e) {
                $this->Flash->error(__('Master Control Error: ' . $e->getMessage()));
            }
        } else {
            $this->Flash->error(__('Invalid Hall selection.'));
        }

        return $this->redirect(['action' => 'index', '?' => ['hall_id' => $hallId]]);
    }

    /**
     * Delete Row method
     *
     * @param string|null $hallId Hall id.
     * @param string|null $rowLabel Row label.
     */
    public function deleteRow($hallId = null, $rowLabel = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        if ($hallId && $rowLabel) {
            try {
                // Master Control: Decouple Tickets
                $seatIds = $this->Seats->find()
                    ->where(['hall_id' => $hallId, 'seat_row' => $rowLabel])
                    ->extract('id')
                    ->toArray();
                
                if (!empty($seatIds)) {
                    $this->getTableLocator()->get('Tickets')->updateAll(
                        ['seat_id' => null],
                        ['seat_id IN' => $seatIds]
                    );
                }

                $deleted = $this->Seats->deleteAll([
                    'hall_id' => $hallId,
                    'seat_row' => $rowLabel
                ]);
                
                if ($deleted > 0) {
                    $this->Flash->success(__('Master Row {0} ({1} seats) has been deleted.', $rowLabel, $deleted));
                } else {
                    $this->Flash->error(__('Could not delete seats in Row {0}.', $rowLabel));
                }
            } catch (\Exception $e) {
                $this->Flash->error(__('Master Control Error: ' . $e->getMessage()));
            }
        } else {
            $this->Flash->error(__('Invalid request parameters.'));
        }

        return $this->redirect(['action' => 'index', '?' => ['hall_id' => $hallId]]);
    }

    /**
     * Generate method
     */
    public function generate()
    {
        if ($this->request->is('post')) {
            $hallId = $this->request->getData('hall_id');
            $rows = (int)$this->request->getData('total_rows');
            $cols = (int)$this->request->getData('seats_per_row');
            $type = $this->request->getData('seat_type');
            $price = $this->request->getData('seat_price');
            $clear = $this->request->getData('clear_existing');

            if ($clear) {
                $this->Seats->deleteAll(['hall_id' => $hallId]);
            }

            $seats = [];
            $rowLabels = range('A', 'Z');
            
            $count = 0;
            for ($r = 0; $r < $rows; $r++) {
                if (!isset($rowLabels[$r])) break; // Limit to 26 rows for now
                $rowLabel = $rowLabels[$r];
                
                for ($c = 1; $c <= $cols; $c++) {
                    $seat = $this->Seats->newEmptyEntity();
                    $seat->hall_id = $hallId;
                    $seat->seat_row = $rowLabel;
                    $seat->seat_number = (string)$c;
                    $seat->seat_type = $type;
                    $seat->seat_price = $price;
                    $seat->status = 1; // Active default
                    $seats[] = $seat;
                    $count++;
                }
            }

            if ($this->Seats->saveMany($seats)) {
                $this->Flash->success(__('{0} seats generated successfully.', $count));
                return $this->redirect(['action' => 'index', '?' => ['hall_id' => $hallId]]);
            }
            $this->Flash->error(__('Could not generate seats. Please try again.'));
        }

        $defaultHallId = $this->request->getQuery('hall_id');
        $halls = $this->Seats->Halls->find('list')->all();
        $this->set(compact('halls', 'defaultHallId'));
    }
}
