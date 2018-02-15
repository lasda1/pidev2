<?php

namespace EspaceEtudeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MatieresController extends Controller
{
    public function afficherMatiereAction()
    {
        return $this->render('EspaceEtudeBundle:Matieres:afficher_matiere.html.twig', array(
            // ...
        ));
    }

}
