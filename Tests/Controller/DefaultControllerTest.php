<?php

/*
 * This file is part of the Nimbusletruand\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimbusletruand\CurriculumVitaeBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // Test passed //
        $crawler = $client->request('GET', '/example');
		$this->assertGreaterThan(0, $crawler->filter('html:contains("First Name Last Name")')->count());

        // Test passed //
        $crawler = $client->request('GET', '/example/fr');
		$this->assertGreaterThan(0, $crawler->filter('html:contains("First Name Last Name")')->count());

        // Test failed //
        $crawler = $client->request('GET', '/doesnotexist/fr');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("There is no curriculum vitae file defined for doesnotexist")')->count());

        // Test failed //
        $crawler = $client->request('GET', '/example/xx');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("There is no curriculum vitae defined for this language")')->count());
    }

    public function testExportPDFAction()
    {
        $client = static::createClient();

        // Test failed //
        $crawler = $client->request('GET', '/example/en/pdf');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("First Name Last Name")')->count());
    }
}
