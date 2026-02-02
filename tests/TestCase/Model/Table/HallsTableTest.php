<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HallsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HallsTable Test Case
 */
class HallsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HallsTable
     */
    protected $Halls;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Halls',
        'app.Seats',
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
        $config = $this->getTableLocator()->exists('Halls') ? [] : ['className' => HallsTable::class];
        $this->Halls = $this->getTableLocator()->get('Halls', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Halls);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\HallsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
