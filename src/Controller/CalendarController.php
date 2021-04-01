<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CalendarController extends AbstractController
{
    /**
     * @Route("/calendars", name="calendar_index", methods={"GET"})
     * @param CalendarRepository $calendarRepository
     * @return Response
     */
    public function index(CalendarRepository $calendarRepository): Response
    {
        return $this->render('calendar/index.html.twig', [
            'calendars' => $calendarRepository->findBy(['user_id'=>$this->getUser()->getId()]),
        ]);
    }

    /**
     * @Route("/calendar/new", name="calendar_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendar->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($calendar);
            $entityManager->flush();

            return $this->redirectToRoute('user-calendar-dashboard');
        }

        return $this->render('calendar/new.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/calendar/{id}/show", name="calendar_show", methods={"GET"})
     * @param Calendar|null $calendar
     * @return Response
     */
    public function show(?Calendar $calendar): Response
    {
        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    /**
     * @Route("/calendar/{id}/editar", name="calendar_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Calendar $calendar
     * @return Response
     */
    public function edit(Request $request, Calendar $calendar): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user-calendar-dashboard');
        }

        return $this->render('calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/calendar/{id}/delete", name="calendar_delete", methods={"DELETE"})
     * @param Request $request
     * @param Calendar $calendar
     * @return Response
     */
    public function delete(Request $request, Calendar $calendar): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user-calendar-dashboard');
    }


    /**
     * @Route("/calendar/dashboard", name="user-calendar-dashboard", methods={"GET"})
     */
    public function dashboard(): Response
    {
        $doctrinePersistence = $this->getDoctrine();
        $entityManager = $doctrinePersistence->getManager();
        $calendarRepository = $doctrinePersistence->getRepository(Calendar::class);
        $doctrineConnection = $doctrinePersistence->getConnection('default');
        //Que les events de cet utilisateur
        $calendars = $calendarRepository->findAllByUser($this->getUser()->getId());
        $calendar_events = [];
        foreach ($calendars as $calendar) {
            $calendar_events[] = [
                "id"=>$calendar->getId(),
                'title'=> $calendar->getTitle(),
                'start'=> $calendar->getStart()->format('Y-m-d H:i'),//2018-09-01 18:02
                'end'=> $calendar->getEnd()->format('Y-m-d H:i'),
                'description'=> $calendar->getDescription(),
                'backgroundColor'=>$calendar->getBackgroundColor()
            ];
        }

        //A FAIRE - Con
        return $this->render('event/dashboard.html.twig' , [
            'events' => json_encode($calendar_events),
            'calendars' => $calendars,
            'user'=>$this->getUser()
        ]);
    }

}
