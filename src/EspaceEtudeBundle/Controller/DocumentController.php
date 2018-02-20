<?php

namespace EspaceEtudeBundle\Controller;

use EspaceEtudeBundle\Entity\Documents;
use EspaceEtudeBundle\Entity\Matiere;
use EspaceEtudeBundle\Entity\Section;
use EspaceEtudeBundle\Enum\Niveau;
use EspaceEtudeBundle\Form\DocumentsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Org_Heigl\Ghostscript\Ghostscript;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Date;
use UserBundle\Entity\User;

class DocumentController extends Controller
{
    public function afficherDocumentsAction(Request $request)
    {

        $b=null;
        $id=$request->attributes->get('id');
        $documents=new Documents();
        $em=$this->getDoctrine()->getManager();
        $form = $this->createForm(DocumentsType::class, $documents);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $documents->getPath();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $path = "C:/xampp/htdocs/EspritEntreAide/web/Documents";
            $file->move(
                $path,
                $fileName
            );
            if($file->getClientOriginalExtension ()=="pdf"){
                $b=$path."/".$fileName;
                $b=str_replace('/','\\',$b);
                $gs = new Ghostscript ();
                $gs->setGsPath("C:/Program Files/gs/gs9.22/bin/gswin64c.exe");
                $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileName);
                $gs->setDevice('jpeg')
                    // Set the input file
                    ->setInputFile($b)
                    // Set the output file that will be created in the same directory as the input
                    ->setOutputFile($withoutExt)
                    // Set the resolution to 96 pixel per inch
                    ->setResolution(96)
                    // Set Text-antialiasing to the highest level
                    ->setTextAntiAliasing(Ghostscript::ANTIALIASING_HIGH);
                // convert the input file to an image
                $gs->render();
                $test="/documents/".$withoutExt.".jpeg";
                $documents->setImg($test);
            }
            elseif($file->getClientOriginalExtension ()=="docx"){
                $test="documents/telechargement.jpeg";
                $documents->setImg($test);
            }else
                $documents->setImg(" ");
            $user = $this->getUser();
            $documents->setUser($user);

            $matiere = $em->getRepository(Matiere::class)->find($request->attributes->get('id'));
            $documents->setMatiere($matiere);

            $documents->setDate($date = date('m/d/Y h:i:s a', time()));
            $documents->setSize($file->getClientSize());

            $type =$file->getClientOriginalExtension ();
            $documents->setTypeDocument($type);
            $documents->setPath("/Documents/".$fileName);
            $em->persist($documents);
            $em->flush();
        }

        $niveau=new Niveau();
        $documents=$em->getRepository(Documents::class)->findBy( ['matiere' => $id]);
        $niveau=$niveau->getAvailableTypes();
        $section=$em->getRepository(Section::class)->findAll();
        $countAll=$em->getRepository(Documents::class)->countAll();
        return $this->render('EspaceEtudeBundle:Document:afficher_documents.html.twig', array(
            'niveaux'=>$niveau,'sections'=>$section,'form' => $form->createView(),'id'=>$id,'docs'=>$documents,'all'=>$countAll,
        ));
    }
    public function supprimerDocumentAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $doc=$em->getRepository(Documents::class)->find($request->attributes->get('idDoc'));
        $em->remove($doc);
        $em->flush();
        return $this->redirectToRoute("afficher_documents",array('id'=>$request->attributes->get('idMatiere')));
    }
    public function modifierDocumentAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $doc=$em->getRepository(Documents::class)->find($request->attributes->get('idDoc'));


            $doc->setLibelle($request->request->get('libelle'));
            $doc->setLanguage($request->request->get('language'));
            $em->persist($doc);
            $em->flush();
        return $this->redirectToRoute("afficher_documents",array('id'=>$request->attributes->get('idMatiere')));
    }

    public function rechercheAction(Request $request){

        if($request->isXmlHttpRequest()){
            $docName=$request->get('docName');
            $em=$this->getDoctrine()->getManager();
            $docs=$em->getRepository('EspaceEtudeBundle\Entity\Documents')->findBy(['libelle'=>$docName]);
            //initialisation du serialisable

            $serializer=new Serializer(array(new ObjectNormalizer()));
            //n7otou el data
            $data=$serializer->normalize($docs);
            $response=new Response(json_encode($data));
            $response->headers->set("content-type","application/json");
            return $response;
/*

            $tabDocs = array();
            $i = 0;

            foreach ($docs as $doc)
            {
                $tabDocs[$i]['path'] = $doc->getPath();
                $tabDocs[$i]['libelle'] = $doc->getLibelle();
                $tabDocs[$i]['img'] = $doc->getImg();
                $tabDocs[$i]['id'] = $doc->getId();
                $tabDocs[$i]['user'] = $doc->getUser();
                $i++;
            }

            $response = new Response();
            $docs = json_encode(array('docs' => $tabDocs));
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($docs);
            return $this->render('EspaceEtudeBundle:Document:afficher_documents.html.twig',array('docs'=>$response));

*/
        }
        return $this->render('EspaceEtudeBundle:Document:afficher_documents.html.twig');
    }
}
