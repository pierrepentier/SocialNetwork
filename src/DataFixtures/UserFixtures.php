<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;
 
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
 
class UserFixtures extends Fixture
{
 
    private $encoder;
 
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
 
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername("name-" . $i);
            $password = $this->encoder->encodePassword($user, '1234');
            $user->setPassword($password);
            $user->setEmail("mail-" . $i . "@test.fr");
            $manager->persist($user);
        }
 
        $manager->flush();
    }
 
}