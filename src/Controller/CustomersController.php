<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @method \App\Model\Entity\Customer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CustomersController extends AppController
{
    use \Cake\Mailer\MailerAwareTrait;
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Customers->find();
        $q = $this->request->getQuery('q');
        
        if (!empty($q)) {
            $query->where([
                'OR' => [
                    'name LIKE' => '%' . $q . '%',
                    'email LIKE' => '%' . $q . '%',
                ]
            ]);
        }

        $customers = $this->paginate($query);

        $this->set(compact('customers', 'q'));
    }

    /**
     * View method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customer = $this->Customers->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('customer'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customer = $this->Customers->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            // Trim whitespace from email
            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $data['status'] = 1; // Default status for new registers
            $data['is_verified'] = 0; // Requires email verification
            $data['verification_token'] = bin2hex(random_bytes(32));

            $customer = $this->Customers->patchEntity($customer, $data);
            if ($this->Customers->save($customer)) {
                // Send Verification Email
                $this->getMailer('User')->send('verificationEmail', [$customer, 'customer']);

                $this->Flash->success(__('Registration successful. Please check your email to verify your account.'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('Registration failed. Please try again.'));
        }
        $this->set(compact('customer'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customer = $this->Customers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $error = false;

            // Password Change Logic (Similar to Staffs)
            if (!empty($data['new_password'])) {
                if (empty($data['current_password'])) {
                    $this->Flash->error(__('Please provide your current password to change it.'));
                    $error = true;
                } else {
                    $hasher = new \Cake\Auth\DefaultPasswordHasher();
                    if (!$hasher->check($data['current_password'], $customer->password)) {
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
                $customer = $this->Customers->patchEntity($customer, $data);
                if ($this->Customers->save($customer)) {
                    $this->Flash->success(__('The customer has been saved.'));

                    $authUser = $this->request->getSession()->read('Auth.User');
                    
                    // Update session if user is editing their own profile
                    if ($authUser && $authUser['id'] == $customer->id) {
                        $authUser['name'] = $customer->name;
                        $authUser['email'] = $customer->email;
                        $this->request->getSession()->write('Auth.User', $authUser);
                    }

                    if (isset($authUser['role']) && $authUser['role'] === 'admin') {
                        return $this->redirect(['action' => 'index']);
                    }
                    
                    return $this->redirect(['action' => 'view', $customer->id]);
                }
                $this->Flash->error(__('The customer could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('customer'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    /**
     * Delete method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customer = $this->Customers->get($id);
        if ($this->Customers->delete($customer)) {
            $this->Flash->success(__('The customer has been deleted.'));
        } else {
            $this->Flash->error(__('The customer could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login method
     */
    public function login()
    {
        if ($this->request->getSession()->check('Auth.User')) {
            return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }

        if ($this->request->is('post')) {
            $email = trim($this->request->getData('email'));
            $password = $this->request->getData('password');

            $selectedRole = $this->request->getData('role'); // Get selected role
            $user = null;
            $redirectUrl = ['controller' => 'Pages', 'action' => 'display', 'home'];

            // 1. Admin Login Logic
            if ($selectedRole === 'admin') {
                $staffsTable = $this->fetchTable('Staffs');
                $staff = $staffsTable->find()
                    ->where(['email' => $email, 'role' => 'admin'])
                    ->first();

                if ($staff && password_verify($password, $staff->password)) {
                    if (!$staff->is_verified) {
                        $this->Flash->error(__('Your account is not verified. Please check your email.'));
                        return $this->redirect(['action' => 'login']);
                    }
                    $redirectUrl = ['controller' => 'Pages', 'action' => 'dashboard'];
                    $user = $staff->toArray();
                    $user['role'] = 'admin';
                }
            } 
            // 2. Customer Login Logic
            elseif ($selectedRole === 'customer') {
                $customer = $this->Customers->find()
                    ->where(['email' => $email])
                    ->first();

                if ($customer && password_verify($password, $customer->password)) {
                    if (!$customer->is_verified) {
                        $this->Flash->error(__('Your account is not verified. Please check your email.'));
                        return $this->redirect(['action' => 'login']);
                    }
                    $user = $customer->toArray();
                    $user['role'] = 'customer';
                }
            }

            if ($user) {
                // Determine User Type for Session
                $this->request->getSession()->write('Auth.User', $user); // Generic Auth User Key
                $this->Flash->success(__('Welcome back, ' . $user['name']));

                return $this->redirect($redirectUrl);
            }
            $this->Flash->error(__('Invalid email or password.'));
        }
    }

    /**
     * Guest login method
     */
    public function guest()
    {
        $this->request->getSession()->delete('Auth.Customer');
        $this->request->getSession()->delete('Auth.User');
        $this->Flash->success(__('Welcome, Guest! You are browsing in view-only mode.'));
        return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
    }

    /**
     * Forgot Password method
     */
    public function forgotPassword()
    {
        if ($this->request->is('post')) {
            $email = trim($this->request->getData('email'));
            $role = $this->request->getData('role');
            $user = null;

            if ($role === 'admin') {
                $user = $this->fetchTable('Staffs')->find()->where(['email' => $email])->first();
            } else {
                $user = $this->Customers->find()->where(['email' => $email])->first();
            }

            if ($user) {
                $token = \Cake\Utility\Security::hash(\Cake\Utility\Text::uuid(), 'sha256', true);
                $user->reset_token = $token;
                $user->reset_expiry = \Cake\I18n\FrozenTime::now()->addHours(1);
                
                $table = ($role === 'admin') ? $this->fetchTable('Staffs') : $this->Customers;
                if ($table->save($user)) {
                    $this->getMailer('User')->send('forgotPassword', [$user, $role]);
                    $this->Flash->success(__('We have sent a reset link to your email.'));
                    return $this->redirect(['action' => 'login']);
                }
            } else {
                if ($role === 'admin') {
                    $this->Flash->error(__('Email address not found. Please contact the system administrator for staff or admin registration.'));
                    return $this->redirect(['action' => 'login']);
                } else {
                    $this->Flash->error(__('Email address not found. Please create a new account if you haven\'t registered yet.'));
                    return $this->redirect(['action' => 'add']);
                }
            }
        }
    }

    /**
     * Reset Password method
     */
    public function resetPassword($token = null)
    {
        if (!$token) {
            $this->Flash->error(__('Invalid reset token.'));
            return $this->redirect(['action' => 'login']);
        }

        $role = $this->request->getQuery('role');
        $table = ($role === 'admin') ? $this->fetchTable('Staffs') : $this->Customers;
        
        $user = $table->find()
            ->where([
                'reset_token' => $token,
                'reset_expiry >' => \Cake\I18n\FrozenTime::now()
            ])
            ->first();

        if (!$user) {
            $this->Flash->error(__('Link expired or invalid.'));
            return $this->redirect(['action' => 'login']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $table->patchEntity($user, $this->request->getData(), [
                'validate' => 'default' // Use default validation which includes password matching
            ]);
            
            if (!$user->getErrors()) {
                $user->reset_token = null;
                $user->reset_expiry = null;
                if ($table->save($user)) {
                    $this->Flash->success(__('Password has been reset successfully.'));
                    return $this->redirect(['action' => 'login']);
                }
            }
            $this->Flash->error(__('Please fix the errors below.'));
        }

        $this->set(compact('user', 'role'));
    }

    /**
     * Logout method
     */
    public function logout()
    {
        $this->request->getSession()->delete('Auth.Customer'); // Legacy cleanup
        $this->request->getSession()->delete('Auth.User');
        $this->Flash->success(__('You have been logged out.'));
        return $this->redirect(['controller' => 'Customers', 'action' => 'login']);
    }

    /**
     * Request Email Change - Starts the 2-step verification process
     */
    public function requestEmailChange($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $customer = $this->Customers->get($id);

        $authUser = $this->request->getSession()->read('Auth.User');
        if ($authUser['id'] != $customer->id) {
            $this->Flash->error(__('Unauthorized action.'));
            return $this->redirect(['action' => 'view', $id]);
        }

        $code = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $customer->email_code = $code;
        $customer->email_code_expiry = \Cake\I18n\FrozenTime::now()->addMinutes(15);

        if ($this->Customers->save($customer)) {
            $this->getMailer('User')->send('emailChangeCode', [$customer, $code, false]);
            $this->Flash->success(__('A verification code has been sent to your current email.'));
            return $this->redirect(['action' => 'verifyOldEmailCode', $id]);
        }
        
        $this->Flash->error(__('Could not initiate email change. Please try again.'));
        return $this->redirect(['action' => 'edit', $id]);
    }

    /**
     * Step 1: Verify OTP from OLD email
     */
    public function verifyOldEmailCode($id = null)
    {
        $customer = $this->Customers->get($id);
        if ($this->request->is(['post', 'put'])) {
            $code = $this->request->getData('code');
            if ($customer->email_code === $code && $customer->email_code_expiry->isFuture()) {
                $customer->email_code = null;
                $customer->email_code_expiry = null;
                $this->Customers->save($customer);
                
                $this->request->getSession()->write('EmailChange.old_verified', true);
                return $this->redirect(['action' => 'enterNewEmail', $id]);
            }
            $this->Flash->error(__('Invalid or expired verification code.'));
        }
        $this->set(compact('customer'));
    }

    /**
     * Step 2: Enter new email
     */
    public function enterNewEmail($id = null)
    {
        if (!$this->request->getSession()->read('EmailChange.old_verified')) {
            return $this->redirect(['action' => 'edit', $id]);
        }

        $customer = $this->Customers->get($id);
        if ($this->request->is(['post', 'put'])) {
            $newEmail = trim($this->request->getData('new_email'));
            $exists = $this->Customers->find()->where(['email' => $newEmail])->first();
            if ($exists) {
                $this->Flash->error(__('This email is already in use.'));
            } else {
                $customer->temp_email = $newEmail;
                $code = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                $customer->email_code = $code;
                $customer->email_code_expiry = \Cake\I18n\FrozenTime::now()->addMinutes(15);
                
                if ($this->Customers->save($customer)) {
                    $this->getMailer('User')->send('emailChangeCode', [$customer, $code, true]);
                    $this->Flash->success(__('A verification code has been sent to your new email.'));
                    return $this->redirect(['action' => 'verifyNewEmailCode', $id]);
                }
            }
        }
        $this->set(compact('customer'));
    }

    /**
     * Step 3: Verify OTP from NEW email and update
     */
    public function verifyNewEmailCode($id = null)
    {
        if (!$this->request->getSession()->read('EmailChange.old_verified')) {
            return $this->redirect(['action' => 'edit', $id]);
        }

        $customer = $this->Customers->get($id);
        if ($this->request->is(['post', 'put'])) {
            $code = $this->request->getData('code');
            if ($customer->email_code === $code && $customer->email_code_expiry->isFuture()) {
                $customer->email = $customer->temp_email;
                $customer->temp_email = null;
                $customer->email_code = null;
                $customer->email_code_expiry = null;
                
                if ($this->Customers->save($customer)) {
                    $this->request->getSession()->delete('EmailChange');
                    $authUser = $this->request->getSession()->read('Auth.User');
                    $authUser['email'] = $customer->email;
                    $this->request->getSession()->write('Auth.User', $authUser);

                    // We'll use a specific key for the SweetAlert in the template
                    $this->Flash->success(__('Your email has been updated successfully.'), ['params' => ['alert' => 'success', 'title' => 'Email Updated!']]);
                    return $this->redirect(['action' => 'edit', $id]);
                }
            }
            $this->Flash->error(__('Invalid or expired verification code.'));
        }
        $this->set(compact('customer'));
    }

    /**
     * Verify Email method
     */
    public function verifyEmail($token = null)
    {
        if (!$token) {
            $this->Flash->error(__('Invalid verification token.'));
            return $this->redirect(['action' => 'login']);
        }

        $customer = $this->Customers->find()
            ->where(['verification_token' => $token])
            ->first();

        if (!$customer) {
            $this->Flash->error(__('Invalid or expired verification link.'));
            return $this->redirect(['action' => 'login']);
        }

        $customer->is_verified = 1;
        $customer->verification_token = null;

        if ($this->Customers->save($customer)) {
            $this->Flash->success(__('Your email has been verified successfully. You can now login.'));
        } else {
            $this->Flash->error(__('Verification failed. Please contact support.'));
        }

        return $this->redirect(['action' => 'login']);
    }
}
