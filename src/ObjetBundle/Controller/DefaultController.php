<?php

namespace ObjetBundle\Controller;

use Ob\HighchartsBundle\Highcharts\Highchart;
use ObjetBundle\Entity\Objet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ObjetBundle:Default:index.html.twig');
    }

    public function chartLineAction()
    {
        $em = $this->getDoctrine()->getManager();
        $objets = $em->getRepository(Objet::class)->findAll();
        $tab = array();
        $categories = array();
        foreach ($objets as $objet)
        {
            array_push($tab, $objet->getDate());
            array_push($categories, $objet->getType());
        }
        // Chart
        $series = array( array("name" => "Les Objets", "data" => $tab) );
        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        #id du div où afficher le graphe
        $ob->title->text('ObjectList');
        $ob->xAxis->title(array('text' => "Objets"));
        $ob->yAxis->title(array('text' => "Type"));
        $ob->xAxis->categories($categories);
        $ob->series($series);
        return $this->render('ObjetBundle:Objet:linechart.html.twig', array( 'chart' => $ob ));

    }
}
