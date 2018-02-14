<?php

namespace EspaceEtudeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocumentController extends Controller
{
    public function afficherDocumentsAction()
    {
        return $this->render('EspaceEtudeBundle:Document:afficher_documents.html.twig', array(
            // ...
        ));
    }

}
