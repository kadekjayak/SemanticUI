<?php
namespace SemanticUI\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use SemanticUI\View\Helper\SemanticFormHelper;

/**
 * SemanticUI\View\Helper\SemanticFormHelper Test Case
 */
class SemanticFormHelperTest extends TestCase
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
        $this->SemanticForm = new SemanticFormHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SemanticForm);

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
