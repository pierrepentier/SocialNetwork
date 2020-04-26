<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publication;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PublicationType;
use DateTime;

class PublicationController extends AbstractController
{
    /**
     * @Route("/publication/{id}", name="create_publication")
     * 
     * @return void
     */

    public function createPublication(Request $request){
        $publication = new Publication;

        $form = $this->createForm(PublicationType::class, $publication);    
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager=$this->getDoctrine()->getManager();
            $datetime = new DateTime;
            $publication->setDate($datetime);
            $user = $this ->getUser();
            $user->addPublication($publication);
            $manager->persist($publication);
            $manager->flush();
            return $this->redirectToRoute('home_page');
        }

        return $this->render('publication/index.html.twig', [
            'formPublication' => $form->createView()
        ]);
    }
}
