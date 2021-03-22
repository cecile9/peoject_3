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
     * @param Utilisateur $utilisateur
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Utilisateur $utilisateur, Request  $request): Response
    {
        $doctrinePersistence = $this->getDoctrine();
        $entityManager = $doctrinePersistence->getManager();

        if ($request->getMethod() == "POST") {

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


        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur'=>$utilisateur
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

}
