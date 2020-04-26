<?php

namespace App\tests\Entity;

use App\Entity\Publication;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PublicationTest extends KernelTestCase {

    
    private function expectXErrorsForPublication(int $nbrErrors, Publication $publication) {
        $errors = self::$container->get("validator")->validate($publication);
        $this->assertCount($nbrErrors, $errors);
    }


    private function createPublication() {
        return (new Publication())->setTitre("Je suis non-binaire")
                                  ->setMessage("Dans la vie, il faut faire des choix, et moi, j'ai choisi l'audace : ich bin non-binaire.");
    }

    //$titre;$message

    //$titre

    public function testIfTitreIsValid() {
        self::bootKernel();
        $publication = $this->createPublication();

        $this->expectXErrorsForPublication(0, $publication);
    }

    public function testIfTitreWithLessThan3LettersIsNotValid() {
        self::bootKernel();
        $publication = $this->createPublication()->setTitre("Da");

        $this->expectXErrorsForPublication(1, $publication);

    }

    public function testIfTitreWithMoreThan30LettersIsNotValid() {
        self::bootKernel();
        $publication = $this->createPublication()->setTitre("Darrrrrrrrrrrrrrrrrrrrrrrrrrrrr");

        $this->expectXErrorsForPublication(1, $publication);

    }

    //$message

    public function testIfMessageIsValid() {
        self::bootKernel();
        $publication = $this->createPublication();

        $this->expectXErrorsForPublication(0, $publication);
    }

    public function testIfMessageWithLessThan10LettersIsNotValid() {
        self::bootKernel();
        $publication = $this->createPublication()->setMessage("What's up");

        $this->expectXErrorsForPublication(1, $publication);

    }

    public function testIfMessageWithMoreThan500LettersIsNotValid() {
        self::bootKernel();
        $message = str_repeat("a", 501);
        $publication = $this->createPublication()->setMessage($message);

        $this->expectXErrorsForPublication(1, $publication);

    }

    public function testHasErrorsOnTitreAndMessageTooShorts() {
        self::bootKernel();
        $publication = $this->createPublication()->setTitre("Sa")->setMessage("Lu");
        $this->expectXErrorsForPublication(2, $publication);
    }

    public function testHasErrorsOnTitreAndMessageTooLongs() {
        self::bootKernel();
        $message = str_repeat("a", 501);
        $publication = $this->createPublication()->setTitre("Darrrrrrrrrrrrrrrrrrrrrrrrrrrrr")->setMessage($message);
        $this->expectXErrorsForPublication(2, $publication);
    }
    
    public function testIfEmptyMessageIsNotValid() {
        self::bootKernel();
        $publication = $this->createPublication()->setMessage("");
        $this->expectXErrorsForPublication(1, $publication);
    }

    public function testIfEmptyTitreIsNotValid() {
        self::bootKernel();
        $publication = $this->createPublication()->setTitre("");
        $this->expectXErrorsForPublication(1, $publication);
    }

    public function testHasErrorsOnEmptyTitreAndMessage() {
        self::bootKernel();
        $publication = $this->createPublication()->setTitre("")->setMessage("");
        $this->expectXErrorsForPublication(2, $publication);
    }

}