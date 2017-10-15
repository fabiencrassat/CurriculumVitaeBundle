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

class CurriculumVitaeGetIdentityFromExampleXMLFileTest extends \PHPUnit\Framework\TestCase
{
    private $curriculumVitae;

    public function testGetIdentityWithEnglishLanguage() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../../Resources/data/example.xml');

        $identity = $this->curriculumVitae->getIdentity();
        // We remove the values because of travisci and scrutinizer (depending of date)
        unset($identity['myself']['birthday']);
        unset($identity['myself']['age']);
        $this->assertEquals([
            'myself' => [
                'name' => 'First Name Last Name',
                'nationality' => 'French Citizenship',
                'picture' => 'bundles/fabiencrassatcurriculumvitae/img/example.png'],
            'address' => [
                'street' => 'Street',
                'postalcode' => 'PostalCode',
                'city' => 'City',
                'country' => 'Country',
                'googlemap' => 'http://maps.google.com'],
            'contact' => [
                'mobile' => 'Telephone',
                'email' => 'email_arobase_site_dot_com'],
            'social' => [
                'drivelicences' => 'French driving licence']
        ], $identity);
    }

    public function testGetIdentityWithFrenchLanguage() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../../Resources/data/example.xml', 'fr');

        $identity = $this->curriculumVitae->getIdentity();
        // We remove the format birthday because of travisci and scrutinizer
        unset($identity['myself']['birthday']);
        $this->assertEquals([
            'myself' => [
                'name'       => 'First Name Last Name',
                'birthplace' => 'Paris',
                'picture'    => 'bundles/fabiencrassatcurriculumvitae/img/example.png'
            ],
            'address' => [
                'street'     => 'Street',
                'postalcode' => 'PostalCode',
                'city'       => 'City',
                'country'    => 'Country',
                'googlemap'  => 'http://maps.google.com'
            ],
            'contact' => [
                'mobile' => 'Telephone',
                'email'  => 'email_arobase_site_dot_com'
            ],
            'social' => [
                'marital'       => 'Célibataire',
                'military'      => 'Dégagé des obligations militaires',
                'drivelicences' => 'Titulaire du permis B'
            ]
        ], $identity);
    }
}
