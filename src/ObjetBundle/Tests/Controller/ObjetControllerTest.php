<?php

namespace ObjetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ObjetControllerTest extends WebTestCase
{
    public function testAjoutobj()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajoutobj');
    }

    public function testModifobj()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifobj');
    }

    public function testDeleteobj()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deleteobj');
    }

    public function testUpdateobj()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/updateobj');
    }

}
