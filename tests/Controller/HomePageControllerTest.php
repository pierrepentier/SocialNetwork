<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\Tests\Controller\Traits\AuthSimulatorTrait;


class HomePageControllerTest extends WebTestCase{
    use FixturesTrait;
    use AuthSimulatorTrait;

    private $client;

    /**
     * Prepare the tests
     * @before
     * @return void
     */
    protected function setUpTest(){
        $this->client = static::createClient();
    }

    public function testRedirectToLoginWhenNotAuthenticated(){

        $this->client->request('GET','/homepage');

        $this->assertResponseRedirects('/login');
    }

    public function testDisplayHomeIsSuccessful(){

        $this->client->request('GET','/');

        $this->assertResponseRedirects('/login');
    }

    public function testAccessToHomePageRouteWithAuth(){
        $this->loadFixtures([UserFixtures::class]);
        $user = self::$container->get(UserRepository::class)->find(1);
        $cookie=$this->createCookieForUser($user);
        $this->client->getCookieJar()->set($cookie);
        $this->client->request('GET', '/homepage');
        $this->assertResponseIsSuccessful();
    }
}