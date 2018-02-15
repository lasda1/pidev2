<?php

namespace EspaceEtudeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DocumentControllerTest extends WebTestCase
{
    public function testAfficherdocuments()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/afficherDocuments');
    }

}
