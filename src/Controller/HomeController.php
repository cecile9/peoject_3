<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

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
                    return $this->redirectToRoute('calendar-dashboard', ["id"=>$utilisateur->getId()]);
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

}
