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

    public function testArraysAreNotSimilarWithArrayInArray0() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array('b' => array('c'))),
            array('a' => array('b' => array('d')))
        ));
    }

    public function testArraysAreNotSimilarWithArrayInArray1() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array(
                'b' => array('1'),
                'c' => array('2'),
                'd' => array('3'),
                'e' => array('4'),
                'f' => array('5'),
                'g' => array('6'),
                'h' => array('7'),
                'i' => array('8'),
                'j' => array('9'),
            )),
            array('a' => array(
                'b' => array('0'),
            ))
        ));
    }

    public function testArraysAreNotSimilarWithArrayInArray2() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array(
                'b' => array('0'),
            )),
            array('a' => array(
                'b' => array('1'),
                'c' => array('2'),
                'd' => array('3'),
                'e' => array('4'),
                'f' => array('5'),
                'g' => array('6'),
                'h' => array('7'),
                'i' => array('8'),
                'j' => array('9'),
            ))
        ));
    }

    public function testArraysAreNotSimilarWithArrayInArray3() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array(
                'b' => array('c' => array('0')),
            )),
            array('a' => array(
                'b' => array('c' => array('1'))
            ))
        ));
    }

    public function testArraysAreNotSimilarWithArrayInArray4() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array('b' => array(
                'c' => array('d' => array('0'))
            ))),
            array('a' => array('b' => array(
                'c' => array('d' => array('1'))
            )))
        ));
    }

    public function testArraysAreNotSimilarWithArrayInArray5() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array('b' => array('c' => array(
                'd' => array('0'),
                'e' => array('0')
            )))),
            array('a' => array('b' => array('c' => array(
                'd' => array('1'),
                'e' => array('1')
            ))))
        ));
    }

    public function testArraysAreNotSimilarWithArrayInArray6() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array('b' => array('c' => array(
                'd' => array('0'),
                'e' => array('f' => array('0'))
            )))),
            array('a' => array('b' => array('c' => array(
                'd' => array('1'),
                'e' => array('f' => array('1'))
            ))))
        ));
    }

    public function testArraysAreNotSimilarWithArrayInArray7() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array('b' => array('c' => array(
                'd' => array('0'),
                'e' => array(
                    'f' => array('0'),
                    'g' => array('0')
                )
            )))),
            array('a' => array('b' => array('c' => array(
                'd' => array('1'),
                'e' => array(
                    'f' => array('1'),
                    'g' => array('1')
                )
            ))))
        ));
    }

    public function testArraysAreNotSimilarWithArrayInArray8() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array('b' => array('c' => array(
                'd' => array('0'),
                'e' => array(
                    'f' => array('0'),
                    'g' => array(
                        'h' => 0,
                        1,
                        2
                    )
                )
            )))),
            array('a' => array('b' => array('c' => array(
                'd' => array('1'),
                'e' => array(
                    'f' => array('1'),
                    'g' => array(
                        'h' => 1,
                        2,
                        3
                    )
                )
            ))))
        ));
    }

    public function testArraysAreNotSimilarWithArrayInArray9() {
        $this->assertNotEquals(0, $this->tools->arraysAreSimilar(
            array('a' => array('b' => array('c' => array(
                'd' => array('0'),
                'e' => array(
                    'f' => array('0'),
                    'g' => array(
                        'h' => 0,
                        1,
                        2
                    )
                )
            )))),
            array('a' => array('b' => array('c' => array(
                'd' => array('1'),
                'e' => array(
                    'f' => array('1'),
                    'g' => array(
                        'h' => 1,
                        2,
                        array()
                    )
                )
            ))))
        ));
    }
}
