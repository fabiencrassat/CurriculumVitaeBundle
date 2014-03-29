<?php

/*
 * This file is part of the Nimbusletruand\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimbusletruand\CurriculumVitaeBundle\Test\Entity;

use Nimbusletruand\CurriculumVitaeBundle\Entity\CurriculumVitae;

class CurriculumVitaeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDropDownLanguages()
    {
        $pathToFile = __DIR__.'/../Resources/data/test.xml';
        $CV = new CurriculumVitae($pathToFile);
        $dropdown = $CV->getDropDownLanguages();

        // vérifie que le fichier contient au moins la DropDown en!
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
}
