<?php

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
