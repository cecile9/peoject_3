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
     * @param ContactsRepository $contactsRepository
     * @return Response
     */
    public function edit(ContactsRepository $contactsRepository): Response
    {
        $contacts = $contactsRepository->findAll() ;
        return $this->render('contact/edit.html.twig', [
            'contacts' => $contacts,
        ]);

    }
}