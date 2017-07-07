<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Tests for PMA\libraries\navigation\nodes\NodeFunctionContainer class
 *
 * @package PhpMyAdmin-test
 */

use PMA\libraries\navigation\NodeFactory;
use PhpMyAdmin\Theme;

require_once 'libraries/navigation/NodeFactory.php';
require_once 'test/PMATestCase.php';

/**
 * Tests for PMA\libraries\navigation\nodes\NodeFunctionContainer class
 *
 * @package PhpMyAdmin-test
 */
class NodeFunctionContainerTest extends PMATestCase
{
    /**
     * SetUp for test cases
     *
     * @return void
     */
    public function setup()
    {
        $GLOBALS['server'] = 0;
    }

    /**
     * Test for __construct
     *
     * @return void
     */
    public function testConstructor()
    {
        $parent = NodeFactory::getInstance('NodeFunctionContainer');
        $this->assertArrayHasKey(
            'text',
            $parent->links
        );
        $this->assertContains(
            'db_routines.php',
            $parent->links['text']
        );
        $this->assertEquals('functions', $parent->real_name);
    }
}
