<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/events", name="events")
     */
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/events-create", name="events-create", methods={"GET","POST"})
     */
    public function create(): Response
    {
        dd("Method create");
        return $this->render('event/create.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/events-edit/{id}", name="events-edit", methods={"GET","POST"})
     */
    public function edit(int $id): Response
    {
        dd("Method edit", $id);
        return $this->render('event/edit.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/events-delete/{id}", name="events-delete", methods="POST")
     */
    public function delete(int $id): Response
    {
        dd("Method delete", $id);
        return $this->render('event/delete.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/events-search", name="events-search", methods="GET")
     */
    public function search(): Response
    {
        dd("Method search" );
        return $this->render('event/search.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }


}
