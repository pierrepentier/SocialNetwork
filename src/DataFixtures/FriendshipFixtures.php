<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Friendship;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FriendshipFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
               
        for ($i = 0; $i < 9; $i++) {
            $friendship = new Friendship();
            $user=$this->createUser($i);
            $friendship->setUser($user);
            $manager->persist($user);
            $friend=$this->createFriend($i);
            $friendship->setfriend($friend);
            $manager->persist($friend);
            $manager->persist($friendship);
            $friendship2 = new Friendship(); 
            $friendship2->setUser($friend);          
            $friendship2->setfriend($user);
            $manager->persist($friendship2);
        }

        $manager->flush();
    }

    private function createUser($i){
        $user= new User();
        $user->setUsername("User-$i");
        $user->setPassword($this->encoder->encodePassword($user, '55555'));
        $user->setEmail("mail-$i@yahoo.fr");
        return $user; 
    }
    private function createFriend($i){
        $user= new User();
        $user->setUsername("Friend-$i");
        $user->setPassword($this->encoder->encodePassword($user, '66666'));
        $user->setEmail("friendMail-$i@test.fr");
        return $user; 
    }

}
