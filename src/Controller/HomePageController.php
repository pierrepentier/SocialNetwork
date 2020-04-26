<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publication;
use App\Entity\Like;
use App\Entity\Notification;
use App\Entity\NotifiedFriends;
use App\Repository\LikeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\PublicationType;

class HomePageController extends AbstractController
{
    /**
     * @Route("/homepage", name="home_page")
     * @Route("/", name="home_page_second")
     */
    public function displayAll(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Publication::class);
        $user = $this->getUser();
        $arrayOfFriendships = $user->getFriends()->toArray();
        $myFriendsAndMe=[];
        $myFriends=[];
        for($i=0; $i<count($arrayOfFriendships); $i++){
            array_push($myFriendsAndMe, $arrayOfFriendships[$i]->getFriend()->getId());
        }
        for($i=0; $i<count($arrayOfFriendships); $i++){
            array_push($myFriends, $arrayOfFriendships[$i]->getFriend());
        }
        array_push($myFriendsAndMe, $user->getId());
        $publications=$repo->findAllFriendsAndMePublications($myFriendsAndMe);
        $repoNotifiedFriends = $this->getDoctrine()->getRepository(NotifiedFriends::class);
        $myNotifications = $repoNotifiedFriends->findBy(['friend' => $user, 'flag' => 0 ]); 
        $myNotificationsHistory = $repoNotifiedFriends->findByNotificationsClassedByDate($user);
        dump($myNotificationsHistory);
        

        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);    
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager=$this->getDoctrine()->getManager();
            $datetime = new DateTime;
            $publication->setDate($datetime);
            $user = $this ->getUser();
            $user->addPublication($publication);

            $notification = new Notification();
            $datetime = new DateTime;
            $notification->setUser($user)->setPublication($publication)->setType('publication')->setDate($datetime);
            $notifiedFriends = new NotifiedFriends();
            $manager=$this->getDoctrine()->getManager();
            for($i=0; $i<count($myFriends); $i++){
                $notifiedFriends->setFriend($myFriends[$i])->setNotification($notification)->setFlag(0);
                $manager->persist($notifiedFriends);
            }
        
            $manager->persist($notification);
            $manager->persist($publication);
            $manager->flush();
            return $this->redirectToRoute('home_page');
        }
        return $this->render('home_page/index.html.twig', [
            'myNotifications' => $myNotifications,
            'publications' => $publications,
            'myNotificationsHistory' => $myNotificationsHistory,
            'formPublication' => $form->createView()
        ]);
    }
    
    public function checkIfCurrentUserLikedPost(Publication $publication){
        $repository = $this->getDoctrine()->getRepository(Like::class);
        $likes = $repository->findBy(["publication" => $publication]);
        foreach($likes as $like){
            if($like->getUser() == $this->getUser()){
                return $like;
            }
        }
    }

    /**
     * @Route("/homepage/{id_publication}/like", name="like_publication")
     * @Entity("publication", expr="repository.find(id_publication)")
     * 
     * @return void
     */

    public function likePublication(Publication $publication, LikeRepository $likeRepo):Response{
        $verifiedLiked = $this->checkIfCurrentUserLikedPost($publication);
        $user = $this->getUser();
                
        if($verifiedLiked){
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($verifiedLiked);

        }
        else{
        $like = new Like();
        $like->setUser($user)->setPublication($publication);
        $notification = new Notification;
        $datetime = new DateTime;
        $notification->setUser($user)->setPublication($publication)->setType('like')->setDate($datetime);
        $notifiedFriends = new NotifiedFriends;
        $notifiedFriends->setFriend($publication->getUser())->setNotification($notification)->setFlag(0);
        $manager=$this->getDoctrine()->getManager();
        $manager->persist($like);
        $manager->persist($notification);
        $manager->persist($notifiedFriends);
        }
        $manager->flush();

        return $this->json([
            'likes'=> $likeRepo->count(['publication'=>$publication])
        ]);

        return $this->redirectToRoute('home_page');
    }


    /**
     * @Route("/remove/notifs", name="remove_notifs")
     */
    public function removeNotifications(){
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(NotifiedFriends::class);
        $notifiedFriends = new NotifiedFriends();
        $notifiedFriends = $repository->findBy(['friend' => $user, 'flag' => 0]);
        $manager = $this->getDoctrine()->getManager();
        for($i=0; $i<count($notifiedFriends); $i++){
            $notifiedFriends[$i]->setFlag(1);
            $manager->persist($notifiedFriends[$i]);
        }
        $manager->flush();
        return $this->redirectToRoute('home_page');
    }

}