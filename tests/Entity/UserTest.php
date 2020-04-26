<?php

    namespace App\Tests\Entity;

    use App\Entity\User;
    use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

    class UserTest extends KernelTestCase {
        private function createUser(){
            // return new User();
            return (new User())->setUsername("Pierrot59")->setPlainPassword("Mjf454151@")->setEmail("Michel@dylan.cul");
        }

        private function expectXErrorsForUser(int $nbrErrors, User $user){
            $errors = self::$container->get("validator")->validate($user);
            $this->assertCount($nbrErrors, $errors);
        }

        //$username;$roles;$password;$plainPassword;$email;$publications;$friends;
      

        //$username
        public function testIfUsernameOnlyIsValid(){
            self::bootKernel();
            $user = $this->createUser()->setUsername("Jean");
            $this->expectXErrorsForUser(0, $user);
        }
        public function testIfUsernameOnlyIsUnderThanThreeCharacters(){
            self::bootKernel();
            $user = $this->createUser()->setUsername("Da");
            $this->expectXErrorsForUser(1, $user);
        }
        public function testIfUsernameOnlyNumbers(){
            self::bootKernel();
            $user = $this->createUser()->setUsername("4545789");
            $this->expectXErrorsForUser(0, $user);
        }
        public function testIfUsernameTooLong(){
            self::bootKernel();
            $user = $this->createUser()->setUsername("too long 20 characters");
            $this->expectXErrorsForUser(1, $user);
        }
        public function testIfUsernameBlank(){
            self::bootKernel();
            $user = $this->createUser()->setUsername("");
            $this->expectXErrorsForUser(1, $user);
        }

        //$roles
        //regex [] [ROLE_ADMIN] [ROLE_USER]
        // a voir

        //$password
        //a voir
    
        //$plainPassword
        public function testIfPlainPasswordOnlyIsValid(){
            self::bootKernel();
            $user = $this->createUser()->setPlainPassword("Jean-59!^ù,éè&");
            $this->expectXErrorsForUser(0, $user);
        }
        public function testIfPlainPasswordOnlyIsUnderThanEighCharacters(){
            self::bootKernel();
            $user = $this->createUser()->setPlainPassword("Da787%");
            $this->expectXErrorsForUser(1, $user);
        }
        public function testIfPlainPasswordOnlyNumbers(){
            self::bootKernel();
            $user = $this->createUser()->setPlainPassword("4545789415151515");
            $this->expectXErrorsForUser(1, $user);
        }
        public function testIfPlainPasswordBlank(){
            self::bootKernel();
            $user = $this->createUser()->setPlainPassword("");
            $this->expectXErrorsForUser(1, $user);
        }

        //$email
        public function testIfEmailOnlyIsValid(){
            self::bootKernel();
            $user = $this->createUser()->setEmail("Jean-59@gmail.com");
            $this->expectXErrorsForUser(0, $user);
        }
        public function testIfEmailNotingBeforeArobas(){
            self::bootKernel();
            $user = $this->createUser()->setEmail("@gmail.com");
            $this->expectXErrorsForUser(1, $user);
        }

        public function testIfEmailOnlyNumbers(){
            self::bootKernel();
            $user = $this->createUser()->setEmail("4545789@4845.18");
            $this->expectXErrorsForUser(1, $user);
        }
        public function testIfEmailBlank(){
            self::bootKernel();
            $user = $this->createUser()->setEmail("");
            $this->expectXErrorsForUser(1, $user);
        }
        
        //$publications
        // Je sais pas
        
        //$friends
        // Idem
    }
?>