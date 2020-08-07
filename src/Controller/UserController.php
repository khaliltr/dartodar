<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Entity\User;
use App\Form\CommentaireType;
use App\Form\PublicationType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $utilisateur=$this->get('security.token_storage')->getToken()->getUser();
       /* $repositoryUtilisateur = $this->getDoctrine()->getRepository(User::class);
        $utilisateur->$repositoryUtilisateur->find($utilisateur);*/


        $mdp = $utilisateur->getPassword();

        $form = $this->createForm(UserType::class, $utilisateur);
        $form->add('submit', SubmitType::class, ['label'=>'Modifier', 'attr'=>['class'=>'btn-primary']]);
        $form->handleRequest($request);
        $error=null;
        if ($form->isSubmitted()) {
            $motDePasse=$request->request->get('mdp');
                if (strlen($motDePasse)>=6 or $motDePasse=="")
                {
                    $entityManager = $this->getDoctrine()->getManager();

                    if ($motDePasse!=="")
                    {
                        $motDePasse = $passwordEncoder->encodePassword($utilisateur, $motDePasse);
                        $utilisateur->setPassword($motDePasse);
                    }
                    else
                    {
                        $utilisateur->setPassword($mdp);
                    }
                    $entityManager->persist($utilisateur);
                    $entityManager->flush();
                    $this->addFlash('success', 'Profil mis à jour avec succès!');
                }
                else
                {
                    $error="Mot de passe trop court. Il doit avoir au minimum 6 caractères.";
                }
        }

        return $this->render('user/index.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
            'mdp' => $mdp,
            'error' => $error
        ]);
    }


    /**
     * @Route("/mes-offres", name="mes-offres")
     */
    public function mes_offres(Request $request)
    {
        $utilisateur=$this->get('security.token_storage')->getToken()->getUser();
        $liste_publications = $utilisateur->getPublications();

        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {


            $form->getData();
            $publication = $form->getData();
            $publication->setSource($utilisateur);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->render('user/mes-offres.html.twig', [
                'form' => $form->createView(),
                'liste_publications' => $liste_publications,
            ]);
        }
        return $this->render('user/mes-offres.html.twig',[
            'form' => $form->createView(),
            'liste_publications' => $liste_publications,
        ]);
    }

    /**
     * @Route("/offre/{id}", name="offre", requirements={"page"="\d+"})
     */
    public function offre(int $id,Request $request)
    {
        $publication = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->find($id);
        $utilisateur=$this->get('security.token_storage')->getToken()->getUser();
        $commentaires= $publication->getCommentaires();
        $commentaire = new Commentaire();


        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $form->getData();
            $commentaire = $form->getData();
            $commentaire->setSource($utilisateur);
            $commentaire->setPublication($publication);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->render('user/offre.html.twig',[
                'publication' => $publication,
                'utilisateur' => $utilisateur,
                'commentaires' => $commentaires,
                'form' => $form->createView(),
            ]);
        }
        return $this->render('user/offre.html.twig',[
            'publication' => $publication,
            'utilisateur' => $utilisateur,
            'commentaires' => $commentaires,
            'form' => $form->createView(),
        ]);
    }
}
