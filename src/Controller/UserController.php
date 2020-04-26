<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PersonType;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/addUser", name="user_create")
     * 
     * @return void
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $user=new User();
        $form = $this->createForm(UserType::class, $user);    
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $manager=$this->getDoctrine()->getManager();            
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/form-user.html.twig', [
            'formUser' => $form->createView()
        ]);
    }



}