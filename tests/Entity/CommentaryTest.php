<?php

    namespace App\Tests\Entity;

    use App\Entity\Commentary;
    use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

    class CommentaryTest extends KernelTestCase {
        private function createCommentary(){
            // return new User();
            return (new Commentary())->setComment("Trop bien la publication, psartek!");
        }

        private function expectXErrorsForCommentary(int $nbrErrors, Commentary $commentary){
            $errors = self::$container->get("validator")->validate($commentary);
            $this->assertCount($nbrErrors, $errors);
        }
      

        //$username

        public function testIfCommentOnlyIsValid(){
            self::bootKernel();
            $commentary = $this->createCommentary();
            $this->expectXErrorsForCommentary(0, $commentary);
        }
        public function testIfCommentBlankIsValid(){
            self::bootKernel();
            $commentary = $this->createCommentary()->setComment("");
            $this->expectXErrorsForCommentary(1, $commentary);
        }
        public function testIfCommentMoreThan500CharactersIsValid(){
            self::bootKernel();
            $message = str_repeat("a", 501);
            $commentary = $this->createCommentary()->setComment($message);
            $this->expectXErrorsForCommentary(1, $commentary);
        }


        
    }
?>