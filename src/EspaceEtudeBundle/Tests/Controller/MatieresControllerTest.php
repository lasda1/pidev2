<?php

namespace EspaceEtudeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MatieresControllerTest extends WebTestCase
{
    public function testAffichermatiere()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/afficherMatiere');
    }

}
