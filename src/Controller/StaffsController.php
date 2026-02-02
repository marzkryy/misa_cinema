<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Staffs Controller
 *
 * @property \App\Model\Table\StaffsTable $Staffs
 * @method \App\Model\Entity\Staff[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StaffsController extends AppController
{
    use \Cake\Mailer\MailerAwareTrait;
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Staffs->find();
        $q = $this->request->getQuery('q');
        
        if (!empty($q)) {
            $query->where([
                'OR' => [
                    'name LIKE' => '%' . $q . '%',
                    'email LIKE' => '%' . $q . '%',
                ]
            ]);
        }
        
        $staffs = $this->paginate($query);

        $this->set(compact('staffs', 'q'));
    }

    /**
     * View method
     *
     * @param string|null $id Staff id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $staff = $this->Staffs->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('staff'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $staff = $this->Staffs->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['status'] = 1;
            $data['is_verified'] = 0;
            $data['verification_token'] = bin2hex(random_bytes(32));

            $staff = $this->Staffs->patchEntity($staff, $data);
            if ($this->Staffs->save($staff)) {
                // Send Verification Email
                $this->getMailer('User')->send('verificationEmail', [$staff, 'admin']);

                $this->Flash->success(__('The staff has been saved. A verification email has been sent.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The staff could not be saved. Please, try again.'));
        }
        $this->set(compact('staff'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Staff id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $staff = $this->Staffs->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $error = false;

            // Password Change Logic
            if (!empty($data['new_password'])) {
                if (empty($data['current_password'])) {
                    $this->Flash->error(__('Please provide your current password to change it.'));
                    $error = true;
                } else {
                    $hasher = new \Cake\Auth\DefaultPasswordHasher();
                    if (!$hasher->check($data['current_password'], $staff->password)) {
                        $this->Flash->error(__('Incorrect current password.'));
                        $error = true;
                    } elseif ($data['new_password'] !== $data['confirm_new_password']) {
                        $this->Flash->error(__('New passwords do not match.'));
                        $error = true;
                    } elseif (strlen($data['new_password']) < 6) {
                        $this->Flash->error(__('New password must be at least 6 characters long.'));
                        $error = true;
                    } else {
                        $data['password'] = $data['new_password'];
                    }
                }
            }

            if (!$error) {
                $emailChanged = false;
                $newEmail = $data['email'] ?? null;
                
                if ($newEmail && $newEmail !== $staff->email) {
                    $emailChanged = true;
                    $data['temp_email'] = $newEmail;
                    unset($data['email']); // Don't update the real email yet
                    $data['verification_token'] = bin2hex(random_bytes(32));
                    $data['is_verified'] = 0;
                }

                $staff = $this->Staffs->patchEntity($staff, $data);
                if ($this->Staffs->save($staff)) {
                    if ($emailChanged) {
                        $this->getMailer('User')->send('staffEmailChangeLink', [$staff, $staff->verification_token]);
                        $this->Flash->success(__('The staff has been saved. A verification link has been sent to the new email address. The email will update once verified.'));
                    } else {
                        $this->Flash->success(__('The staff has been saved.'));
                        
                        // Update session if user is editing their own profile (and name changed)
                        $authUser = $this->request->getSession()->read('Auth.User');
                        if ($authUser && $authUser['id'] == $staff->id) {
                            $authUser['name'] = $staff->name;
                            // Email doesn't update here because it requires verification for staff
                            $this->request->getSession()->write('Auth.User', $authUser);
                        }
                    }
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The staff could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('staff'));
    }

    /**
     * Verify Staff Email Change via Link
     */
    public function verifyEmailChange($token = null)
    {
        if (!$token) {
            $this->Flash->error(__('Invalid verification link.'));
            return $this->redirect(['controller' => 'Customers', 'action' => 'login']);
        }

        $staff = $this->Staffs->find()->where(['verification_token' => $token])->first();
        if ($staff) {
            $staff->email = $staff->temp_email;
            $staff->temp_email = null;
            $staff->verification_token = null;
            $staff->is_verified = 1;

            if ($this->Staffs->save($staff)) {
                // If the logged in user is this staff, update their session
                $authUser = $this->request->getSession()->read('Auth.User');
                if ($authUser && $authUser['id'] == $staff->id && $authUser['role'] === 'staff') {
                    $authUser['email'] = $staff->email;
                    $this->request->getSession()->write('Auth.User', $authUser);
                }
                
                return $this->redirect(['action' => 'verificationSuccess']);
            }
        }

        $this->Flash->error(__('Invalid or expired verification link.'));
        return $this->redirect(['controller' => 'Customers', 'action' => 'login']);
    }

    /**
     * Verification Success Page for Staff
     */
    public function verificationSuccess()
    {
        // Just render the template: templates/Staffs/verification_success.php
    }

    /**
     * Delete method
     *
     * @param string|null $id Staff id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $staff = $this->Staffs->get($id);
        if ($this->Staffs->delete($staff)) {
            $this->Flash->success(__('The staff has been deleted.'));
        } else {
            $this->Flash->error(__('The staff could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Verify Email method
     */
    public function verifyEmail($token = null)
    {
        if (!$token) {
            $this->Flash->error(__('Invalid verification token.'));
            return $this->redirect(['controller' => 'Customers', 'action' => 'login']);
        }

        $staff = $this->Staffs->find()
            ->where(['verification_token' => $token])
            ->first();

        if (!$staff) {
            $this->Flash->error(__('Invalid or expired verification link.'));
            return $this->redirect(['controller' => 'Customers', 'action' => 'login']);
        }

        $staff->is_verified = 1;
        $staff->verification_token = null;

        if ($this->Staffs->save($staff)) {
            $this->Flash->success(__('Your email has been verified successfully. You can now login.'));
        } else {
            $this->Flash->error(__('Verification failed. Please contact support.'));
        }

        return $this->redirect(['controller' => 'Customers', 'action' => 'login']);
    }
}
