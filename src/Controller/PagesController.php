<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\View\Exception\MissingTemplateException When the view file could not
     *   be found and in debug mode.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found and not in debug mode.
     * @throws \Cake\View\Exception\MissingTemplateException In debug mode.
     */
    public function display(string ...$path): ?Response
    {
        if (!$path) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }

        // Redirect Admin to Dashboard from Home
        if ($page === 'home') {
            $user = $this->request->getSession()->read('Auth.User');
            if ($user && $user['role'] === 'admin') {
                return $this->redirect(['action' => 'dashboard']);
            }

            $this->loadModel('Shows');
            $nowDate = date('Y-m-d');
            $nowTime = date('H:i:s');
            
            $shows = $this->Shows->find('all')
                ->where([
                    'Shows.status' => 1,
                    'OR' => [
                        ['Shows.show_date >' => $nowDate],
                        [
                            'Shows.show_date' => $nowDate,
                            'Shows.show_time >' => $nowTime
                        ]
                    ]
                ])
                ->group(['show_title']) // Group by title to get unique movies
                ->order(['show_date' => 'ASC']) // Order by nearest date
                ->toArray();
            $this->set(compact('shows'));
        }

        $this->set(compact('page', 'subpage'));

        try {
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }
    public function dashboard()
    {
        // Enforce Admin Access
        $user = $this->request->getSession()->read('Auth.User');
        if (!$user || $user['role'] !== 'admin') {
            return $this->redirect('/');
        }

        $this->loadModel('Bookings');
        $this->loadModel('Customers');
        $this->loadModel('Shows');

        /** @var \App\Model\Table\BookingsTable $bookingsTable */
        $bookingsTable = $this->Bookings;
        /** @var \App\Model\Table\CustomersTable $customersTable */
        $customersTable = $this->Customers;
        /** @var \App\Model\Table\ShowsTable $showsTable */
        $showsTable = $this->Shows;

        // 1. Total Sales
        $query = $bookingsTable->find()->where(['status' => 1]);
        /** @var \Cake\ORM\Query $query */
        $totalSales = $query->select(['total' => $query->func()->sum('ticket_price')])
            ->first()->total ?? 0;

        // 2. Total Bookings
        $totalBookings = $bookingsTable->find()->count();

        // 3. Total Customers
        $totalCustomers = $customersTable->find()->count();

        // 4. Active Movies (Now Showing - Unique Titles)
        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');
        $activeMovies = $showsTable->find()
            ->where([
                'status' => 1,
                'OR' => [
                    ['show_date >' => $nowDate],
                    [
                        'show_date' => $nowDate,
                        'show_time >' => $nowTime
                    ]
                ]
            ])
            ->group(['show_title'])
            ->count();

        // 5. Recent Bookings with Search
    $search = $this->request->getQuery('search');
    $bookingsQuery = $bookingsTable->find()
        ->contain(['Customers', 'Shows'])
        ->order(['Bookings.id' => 'DESC']);

    if (!empty($search)) {
        $bookingsQuery->where([
            'OR' => [
                'Customers.name LIKE' => '%' . $search . '%',
                'Shows.show_title LIKE' => '%' . $search . '%',
                'Bookings.id' => (int)$search // Exact match for ID
            ]
        ]);
    }
    
    // Limit to 10 most recent bookings as requested.
    $bookingsQuery->limit(10); 

    $recentBookings = $bookingsQuery->all()->toArray();

    // 6. Analytics: 7-Day Revenue
    $days = [];
    $revenueData = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $days[] = date('D', strtotime($date));
        
        // Simple query for daily revenue
        $dayRevenue = $bookingsTable->find()
            ->where([
                'status' => 1,
                'DATE(book_date_time)' => $date
            ])
            ->select(['total' => $bookingsTable->find()->func()->sum('ticket_price')])
            ->first()->total ?? 0;
        
        $revenueData[] = (float)$dayRevenue;
    }

    // 7. Analytics: Movie Popularity (Top 5)
    $popQuery = $showsTable->find();
    $popularity = $popQuery->select([
            'title' => 'Shows.show_title',
            'count' => $popQuery->func()->count('Bookings.id')
        ])
        ->leftJoinWith('Bookings')
        ->group(['Shows.show_title'])
        ->order(['count' => 'DESC'])
        ->limit(5)
        ->all()
        ->toArray();
    
    $movieLabels = [];
    $movieCounts = [];
    foreach ($popularity as $p) {
        $movieLabels[] = $p->title;
        $movieCounts[] = (int)$p->count;
    }

    $this->set(compact(
        'totalSales', 
        'totalBookings', 
        'totalCustomers', 
        'activeMovies', 
        'recentBookings', 
        'search',
        'days',
        'revenueData',
        'movieLabels',
        'movieCounts'
    ));
    }

    public function contact()
    {
        $user = $this->request->getSession()->read('Auth.User');
        $this->set(compact('user'));

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Basic Validation
            if (empty($data['name']) || empty($data['email']) || empty($data['subject']) || empty($data['message'])) {
                $this->Flash->error(__('Please fill in all required fields.'));
                return;
            }

            try {
                // Email Logic
                $mailer = new \Cake\Mailer\Mailer('default');
                $mailer->setTransport('default'); 
                $mailer->setFrom(['misacinemaa@gmail.com' => 'MisaCinema System'])
                       ->setTo('misacinemaa@gmail.com')
                       ->setSubject('New Report: ' . $data['subject'])
                       ->setEmailFormat('html')
                       ->viewBuilder()
                           ->setTemplate('contact_form');
                
                $mailer->setViewVars(['data' => $data]);
                $mailer->send();
    
                $this->Flash->success(__('Your report has been sent successfully! We will get back to you soon.'));
                return $this->redirect('/');
            } catch (\Exception $e) {
                \Cake\Log\Log::error("Contact Form Email Error: " . $e->getMessage());
                $this->Flash->error(__('Could not send email. Please try again later.'));
            }
        }
    }
}
