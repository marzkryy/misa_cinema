<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShowsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShowsTable Test Case
 */
class ShowsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ShowsTable
     */
    protected $Shows;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
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
        $config = $this->getTableLocator()->exists('Shows') ? [] : ['className' => ShowsTable::class];
        $this->Shows = $this->getTableLocator()->get('Shows', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Shows);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ShowsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
