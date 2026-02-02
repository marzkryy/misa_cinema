<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Halls Controller
 *
 * @property \App\Model\Table\HallsTable $Halls
 * @method \App\Model\Entity\Hall[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HallsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $halls = $this->paginate($this->Halls, [
            'fields' => ['id', 'hall_type', 'status', 'created', 'modified']
        ]);

        $this->set(compact('halls'));
    }

    /**
     * View method
     *
     * @param string|null $id Hall id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $hall = $this->Halls->get($id, [
            'contain' => ['Seats', 'Tickets'],
        ]);

        $this->set(compact('hall'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $hall = $this->Halls->newEmptyEntity();
        if ($this->request->is('post')) {
            $hall = $this->Halls->patchEntity($hall, $this->request->getData());
            if ($this->Halls->save($hall)) {
                $this->Flash->success(__('The hall has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The hall could not be saved. Please, try again.'));
        }
        $this->set(compact('hall'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Hall id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $hall = $this->Halls->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $hall = $this->Halls->patchEntity($hall, $this->request->getData());
            if ($this->Halls->save($hall)) {
                $this->Flash->success(__('The hall has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The hall could not be saved. Please, try again.'));
        }
        $this->set(compact('hall'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Hall id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $hall = $this->Halls->get($id);
        if ($this->Halls->delete($hall)) {
            $this->Flash->success(__('The hall has been deleted.'));
        } else {
            $this->Flash->error(__('The hall could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
