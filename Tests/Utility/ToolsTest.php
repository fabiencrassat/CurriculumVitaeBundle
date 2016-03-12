<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Tests\Utility;

use FabienCrassat\CurriculumVitaeBundle\Utility\Tools;

class ToolsTest extends \PHPUnit_Framework_TestCase
{

    private $tools;

    public function __construct() {
        $this->tools = new Tools();
    }

    public function testArraysAreSimilar() {
        $this->assertEquals(0, $this->tools->arraysAreSimilar(array(), array()));
    }

    public function testArraysAreNotSimilar() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(array("a"), array("b")));
    }

    public function testArraysAreNotSimilarWithArray() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(array('a' => array("a")), array()));
    }

    public function testArraysAreNotSimilarWithNotArray() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array("a")),
            array('a' => "a")
        ));
    }

    public function testArraysAreNotSimilarWithArrayInArray() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array('b' => array('c'))),
            array('a' => array('b' => array('d')))
        ));
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('top' => array('a' => array('b' => array('c')))),
            array('a' => array('b' => array('d')))
        ));
    }
}
