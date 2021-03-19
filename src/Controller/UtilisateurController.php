<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utilisateur-login", name="utilisateur-login" , methods={"POST","GET"})
     */
    public function login(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $error = "";
        if ($request->getMethod() == "POST" ) {
            $email = $request->request->get('email');
            $password= $request->request->get('password');
            $utilisateurs= $utilisateurRepository->findAll();
            $user = null;
            foreach ($utilisateurs as $utilisateur)
            {
                if($email == $utilisateur->getMail()&& $password == $utilisateur->getPassword()){
                    return $this->redirectToRoute('utilisateur-dashboard', ["id"=>$utilisateur->getId()]);
                }
            }
            $error = "Mot de passe et/ou email incorrect";
        }

        //1. aller chgercher les users de la base de données
        //2. renvoyer à la vue
        return $this->render('utilisateur/login.html.twig',[
            'error'=>$error
        ]);
    }

    /**
     * @Route("/utilisateur-create", name="utilisateur-create" , methods={"GET","POST"}))
     * @param Request $request
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->getMethod() == "POST") {
            //1. créer un utilisateur
            $utilisateur = new Utilisateur();

            //2. hydrater les informations
            $utilisateur->setName($request->request->get('name'));
            $utilisateur->setDisplayName($request->request->get('displayName'));
            $utilisateur->setGivenName($request->request->get('givenName'));
            $utilisateur->setCompany($request->request->get('company'));
            $utilisateur->setTelephoneNumber($request->request->get('telephoneNumber'));
            $utilisateur->setMail($request->request->get('email'));
            $utilisateur->setPassword($request->request->get('password'));

            //3. Sauvegarer à la base de données
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('utilisateur-login');
        }
        $utilisateur = new Utilisateur();
        return $this->render('utilisateur/create.html.twig');
    }
    /**
     * @Route("/utilisateur-edit/{id}", name="utilisateur-edit" , methods={"GET","POST"}))
     */
    public function edit(): Response
    {
        dd("utilisateur-edit");

        return $this->render('utilisateur/edit.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Route("/utilisateur-delete/{id}", name="utilisateur-delete", methods="POST")
     */
    public function delete(Request $request, int $id): Response
    {
        if ($request->getMethod() == "POST") {

            $doctrinePersistence = $this->getDoctrine();
          $entityManager = $doctrinePersistence->getManager();
    //    $eventRepository = $doctrinePersistence->getRepository(Event::class);
          $utilisateurRepository = $doctrinePersistence->getRepository(Utilisateur::class);
    //    $doctrineConnection = $doctrinePersistence->getConnection('default');
          $utilisateur = $utilisateurRepository->find($id);
          $entityManager ->remove($utilisateur);
          $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/utilisateur-search", name="utilisateur-search", methods="GET")
     */
    public function search(): Response
    {
        dd("Method search" );
        return $this->render('event/search.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Route("/utilisateur-dashboard/{id}", name="utilisateur-dashboard", methods="GET")
     */
    public function dashboard(int $id): Response
    {
        $doctrinePersistence = $this->getDoctrine();
        $entityManager = $doctrinePersistence->getManager();
        $eventRepository = $doctrinePersistence->getRepository(Event::class);
        $utilisateurRepository = $doctrinePersistence->getRepository(Utilisateur::class);
        $doctrineConnection = $doctrinePersistence->getConnection('default');
        $utilisateur = $utilisateurRepository->find($id);
        return $this->render('event/dashboard.html.twig' , [
            'events' =>$eventRepository->findAll(),
            'user'=>$utilisateur
        ]);
    }
}
