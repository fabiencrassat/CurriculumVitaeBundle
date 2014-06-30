<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Test\Entity;

use FabienCrassat\CurriculumVitaeBundle\Entity\CurriculumVitae;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class CurriculumVitaeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDropDownLanguages()
    {
        $pathToFile = __DIR__.'/../Resources/data/test.xml';
        $CV = new CurriculumVitae($pathToFile);
        $dropdown = $CV->getDropDownLanguages();

        // Check if the file contains at least the Dropdown 'en'!
        // Test passed //
        $this->assertArrayHasKey('en', $dropdown);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testFileDoesNotExist()
    {
        try {
            $pathToFile = __DIR__.'/../Resources/data/no_file.xml';
            $CV = new CurriculumVitae($pathToFile);
        }

        // Test failed //
        catch (InvalidArgumentException $expected) {
            return;
        }
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testMaxRecursivity()
    {
        try {
            $pathToFile = __DIR__.'/../Resources/data/test.xml';
            $CV = new CurriculumVitae($pathToFile);
            $CV->getSociety();
        }

        // Test failed //
        catch (InvalidArgumentException $expected) {
            return;
        }
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testNoNode()
    {
        try {
            $pathToFile = __DIR__.'/../Resources/data/empty.xml';
            $CV = new CurriculumVitae($pathToFile);
            $CV->getSociety();
        }

        // Test failed //
        catch (InvalidArgumentException $expected) {
            return;
        }
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testGetAgeWithBadFormat()
    {
        try {
            $pathToFile = __DIR__.'/../Resources/data/test.xml';
            $CV = new CurriculumVitae($pathToFile);
            $CV->getIdentity();
        }

        // Test failed //
        catch (InvalidArgumentException $expected) {
            return;
        }
    }

    public function testGetAgeBeforeBirthday()
    {
        $pathToFile = __DIR__.'/../Resources/data/getAge.xml';
        $CV = new CurriculumVitae($pathToFile);
        $CV->getIdentity();
    }
    public function testGetAgeInBirthdayMonth()
    {
        // The month of birthday in the xml must be the month of today
        $pathToFile = __DIR__.'/../Resources/data/getAgeInBirthdayMonth.xml';
        $CV = new CurriculumVitae($pathToFile);
        $CV->getIdentity();
    }
}