<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Publication;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PublicationFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        
        $user= $this->createUser();
        $manager->persist($user);

        for ($i = 0; $i < 10; $i++) {
            $publication = new Publication();
            $publication->setMessage("message-" . $i);
            $user->addPublication($publication);
            $publication->setUser($user);
            $manager->persist($publication);
        }

        $manager->flush();
    }

    private function createUser(){
        $user= new User();
        $user->setUsername("Pierre");
        $password = $this->encoder->encodePassword($user, '12345');
        $user->setPassword($password);
        $user->setEmail("pentier.pierre@yahoo.fr");
        return $user; 
    }

}
