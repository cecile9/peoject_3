<?php


namespace App\Controller;


use App\Entity\Contacts;
use App\Form\ContactType;
use App\Repository\ContactsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    /**
     * @Route("/contact", name="contact_index")
     * @param ContactsRepository $contactsRepository
     * @return Response
     */
    public function index(ContactsRepository $contactsRepository): Response
    {
        $contacts = $contactsRepository->findAll() ;
        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("/contact/create", name="contact_create", methods={"GET", "POST"})
     * @param ContactsRepository $contactsRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function create(
        ContactsRepository $contactsRepository,
        Request $request,
    EntityManagerInterface $entityManager): Response
    {
        $contact= new Contacts();
        $form   = $this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();
            $this->addFlash("success", "Ajout avec succes");

            return $this->redirectToRoute("contact_index");
        }
        return $this->render('contact/create.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/contact/edit/{id}", name="contact_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Contacts $contact
     * @return Response
     */
    public function edit(Request $request, Contacts $contact): Response
    {
        $form   = $this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Modification avec succes");

            return $this->redirectToRoute("contact_index");
        }
        return $this->render('contact/edit.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/contact/{id}/delete", name="contact_delete", methods={"DELETE"})
     * @param Request $request
     * @param Contacts $calendar
     * @return Response
     */
    public function delete(Request $request, Contacts $calendar): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_index');

    }


}