<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShowSeatsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShowSeatsTable Test Case
 */
class ShowSeatsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ShowSeatsTable
     */
    protected $ShowSeats;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ShowSeats',
        'app.Shows',
        'app.Bookings',
        'app.Tickets',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ShowSeats') ? [] : ['className' => ShowSeatsTable::class];
        $this->ShowSeats = $this->getTableLocator()->get('ShowSeats', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ShowSeats);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ShowSeatsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ShowSeatsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
