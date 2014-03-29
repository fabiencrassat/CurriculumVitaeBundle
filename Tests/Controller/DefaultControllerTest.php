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

        $crawler = $client->request('GET', '/cv');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("First Name Last Name")')->count());

        $crawler = $client->request('GET', '/cv/example');
		$this->assertGreaterThan(0, $crawler->filter('html:contains("First Name Last Name")')->count());

        $crawler = $client->request('GET', '/cv/example/fr');
		$this->assertGreaterThan(0, $crawler->filter('html:contains("First Name Last Name")')->count());

        $crawler = $client->request('GET', '/cv/doesnotexist/fr');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("There is no curriculum vitae file defined for doesnotexist. ")')->count());

        $crawler = $client->request('GET', '/cv/example/xx');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("There is no curriculum vitae defined for this language ")')->count());
    }
}
