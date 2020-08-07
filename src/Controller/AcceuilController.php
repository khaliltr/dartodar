<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Publication;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    /**
     * @Route("/acceuil", name="acceuil")
     * @Route("/")
     */
    public function index()
    {
        return $this->render('acceuil/index.html.twig', [
            'controller_name' => 'AcceuilController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function Contact(Request $request)
    {

        $Contact = new Contact();

        $form = $this->createForm(ContactType::class, $Contact);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $repository = $this->getDoctrine()->getRepository(User::class);
            $destination = $repository->findOneBy(['email' => 'khalil@gmail.com']);

            $form->getData();
            $Contact = $form->getData();
            $Contact->setDestination($destination);
            $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($Contact);
           $entityManager->flush();

            return $this->render('acceuil/contact.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->render('acceuil/contact.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/recherche", name="recherche")
     */
    public function Recherche(Request $request)
    {
        $publications=null;
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);
        $titre="";
        $type="";
        $ville="";
        if ($form->isSubmitted()) {
            $repository = $this->getDoctrine()->getRepository(Publication::class);
            $form->getData();
            $publication = $form->getData();
            $titre=$publication->getTitre();
            $type=$publication->getType();
            $ville=$publication->getVille();
            if ($titre=="" && $type=="" && $ville=="")
            {

            }
            else
            {
                $publications = $repository->findListByFilter(
                    $titre,
                    $type,
                    $ville);
            }


            return $this->render('acceuil/recherche.html.twig', [
                'form' => $form->createView(),
                'publications' => $publications,
            ]);
        }
        return $this->render('acceuil/recherche.html.twig',[
            'form' => $form->createView(),
            'publications' => $publications,
        ]);

    }
}
