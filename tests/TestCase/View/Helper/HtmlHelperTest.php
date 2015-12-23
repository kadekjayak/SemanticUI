<?php
namespace SemanticUI\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use SemanticUI\View\Helper\HtmlHelper;

/**
 * SemanticUI\View\Helper\HtmlHelper Test Case
 */
class HtmlHelperTest extends TestCase
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
        $this->Html = new HtmlHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Html);

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
