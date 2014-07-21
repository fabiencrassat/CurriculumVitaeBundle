<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Tests\Entity;

use FabienCrassat\CurriculumVitaeBundle\Entity\CurriculumVitae;

class CurriculumVitaeTest extends \PHPUnit_Framework_TestCase
{
    private $CV;

    public function testNoLanguage()
    {
        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../Resources/data/core.xml';
        $this->CV = new CurriculumVitae($pathToFile);
        $language = $this->CV->getDropDownLanguages();
        if (is_array($language)) {
            $this->assertTrue($this->arrays_are_similar(array('en' => 'en'), $language));
        }
    }

    public function testNullReturnWithNoDeclarationInCurriculumVitaeTag()
    {
        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../Resources/data/core.xml';
        $this->CV = new CurriculumVitae($pathToFile);
        $identity = $this->CV->getIdentity();
        $this->assertNull($identity);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithBadCurriculumVitaeFile()
    {
        $this->CV = new CurriculumVitae("abd file");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithTooHighRecursivity()
    {
        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../Resources/data/test.xml';
        $this->CV = new CurriculumVitae($pathToFile);
        $this->CV->getSociety();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithNoValidXMLFile()
    {
        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../Resources/data/empty.xml';
        $this->CV = new CurriculumVitae($pathToFile);
        $this->CV->getDropDownLanguages();
    }

    /**
     * Determine if two associative arrays are similar
     *
     * Both arrays must have the same indexes with identical values
     * without respect to key ordering 
     * 
     * @param array $a
     * @param array $b
     * @return bool
     */
    private function arrays_are_similar($a, $b)
    {
        // if the indexes don't match, return immediately
        if (count(array_diff_assoc($a, $b))) {
            return FALSE;
        }
        // we know that the indexes, but maybe not values, match.
        // compare the values between the two arrays
        foreach($a as $k => $v) {
            if ($v !== $b[$k]) {
                return FALSE;
            }
        }
        // we have identical indexes, and no unequal values
        return TRUE;
    }
}
