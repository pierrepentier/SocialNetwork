<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publication;
use App\Entity\Commentary;
use App\Entity\Notification;
use App\Entity\NotifiedFriends;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentaryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use DateTime;

class CommentaryController extends AbstractController
{
    /**
     * @Route("homepage/Commentary/{id_publication}", name="create_commentary")
     * @Entity("user", expr="repository.find(id_user)")
     * @Entity("publication", expr="repository.find(id_publication)")
     */

    public function createCommentary(Publication $publication, Request $request){
        $commentary = new Commentary;

        $form = $this->createForm(CommentaryType::class, $commentary);    
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager=$this->getDoctrine()->getManager();
            $user = $this->getUser();
            $datetime = new DateTime;
            $commentary->setUser($user)
                       ->setDate($datetime)
                       ->setPublication($publication);
            $notification = new Notification;
            $datetime = new DateTime;
            $notification->setUser($user)->setPublication($publication)->setType('comment')->setDate($datetime);
            $notifiedFriends = new NotifiedFriends;
            $notifiedFriends->setFriend($publication->getUser())->setNotification($notification)->setFlag(0);
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($commentary);
            $manager->persist($notification);
            $manager->persist($notifiedFriends);
            $manager->flush();
            return $this->redirectToRoute('home_page');
        }

        return $this->render('commentary/index.html.twig', [
            'formCommentary' => $form->createView()
        ]);
    }
}
