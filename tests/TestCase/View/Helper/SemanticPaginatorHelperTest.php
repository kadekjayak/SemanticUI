<?php
namespace SemanticUI\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use SemanticUI\View\Helper\SemanticPaginatorHelper;

/**
 * SemanticUI\View\Helper\SemanticPaginatorHelper Test Case
 */
class SemanticPaginatorHelperTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->SemanticPaginator = new SemanticPaginatorHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SemanticPaginator);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
