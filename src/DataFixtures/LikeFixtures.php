<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Like;
use App\Entity\User;
use App\Entity\Publication;

class LikeFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager){
        $user = $this->createUser(999);
        $manager->persist($user);
        $publication = $this->createPublication($user);
        $manager->persist($publication);
        for($i = 0; $i < 10; $i++){
            $userLiker = $this->createUser($i);
            $manager->persist($userLiker);
            $like = new Like();
            $like->setUser($userLiker)
                 ->setPublication($publication);
            $manager->persist($like);
        }
        $manager->flush();
    }

    private function createUser($i){
        $user= new User();
        $user->setUsername("liker-$i")
             ->setPassword($this->encoder->encodePassword($user, '12345'))
             ->setEmail("mail-$i@mail.com");
        return $user;
    }

    private function createPublication($user){
            $publication = new Publication();
            $publication->setMessage("message");
            $user->addPublication($publication);
            $publication->setUser($user);
            return $publication;
    }
}