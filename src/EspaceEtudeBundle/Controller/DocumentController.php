<?php

namespace EspaceEtudeBundle\Controller;

use EspaceEtudeBundle\Entity\Documents;
use EspaceEtudeBundle\Entity\Matiere;
use EspaceEtudeBundle\Entity\Section;
use EspaceEtudeBundle\Enum\Niveau;
use EspaceEtudeBundle\Form\DocumentsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Org_Heigl\Ghostscript\Ghostscript;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

            $user = $this->getUser();
            $documents->setUser($user);
            $test="/documents/".$withoutExt.".jpeg";
            $documents->setImg($test);
            $matiere = $em->getRepository(Matiere::class)->find($request->attributes->get('id'));
            $documents->setMatiere($matiere);

            $documents->setDate($date = date('m/d/Y h:i:s a', time()));
            $documents->setSize($file->getClientSize());

            $type =$file->getClientOriginalExtension ();
            $documents->setTypeDocument($type);
            $documents->setPath($fileName);
            $em->persist($documents);
            $em->flush();
        }

        $niveau=new Niveau();
        $documents=$em->getRepository(Documents::class)->findAll();
        $niveau=$niveau->getAvailableTypes();
        $section=$em->getRepository(Section::class)->findAll();
        return $this->render('EspaceEtudeBundle:Document:afficher_documents.html.twig', array(
            'niveaux'=>$niveau,'sections'=>$section,'form' => $form->createView(),'id'=>$id,'docs'=>$documents,
        ));
    }


}
