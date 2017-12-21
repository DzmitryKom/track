<?php
/**
 * Created by PhpStorm.
 * User: DKomsa
 * Date: 10/24/2017
 * Time: 3:30 PM
 */

namespace AppBundle\Tests\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Client;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;


class ApiTestCase extends WebTestCase
{
    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {
//        static::ensureKernelShutdown();
    }

    /**
     * @var Client
     */
    static private $clientStatic;

    /**
     * @var Client
     */
     protected $client;

    /**
     * @var ContainerInterface
     */
    protected $container;


    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;



    public static function setUpBeforeClass()
    {
        self::$clientStatic = static::createClient();
        self::$clientStatic->followRedirects(true);
        self::$clientStatic->setMaxRedirects(3);
    }

    public function setup(){
        $this->client = self::$clientStatic;
        $this->container = $this->client->getContainer();
        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');

    }

    protected function createUser(array $data){
        $userManager = $this->container->get('fos_user.user_manager');

        if ($userManager->findUserBy(['email'=>$data['email']])){
            return;
        }
        $data = array_merge([
            'enabled'=>true,
        ], $data);
        $accessor = PropertyAccess::createPropertyAccessor();
        $user = $userManager->createUser();
        if (isset($data['password'])){
            $user->setPlainPassword($data['password']);
        }else{
            $user->setPlainPassword('password1');
        }

        foreach ($data as $key => $value){
            $accessor->setValue($user, $key, $value);
        }
        $userManager->updateUser($user);
        return;
    }

    protected function getExceptionFromSymfonyExceptionResponse($getPlain=false){
        $responseText = $this->client->getResponse()->getContent();
        if ($getPlain){
            return $responseText;
        }

        $tt=substr($responseText,strpos($responseText, ' exception-message '), 250);

        $startH1= strpos($tt,'</h1>');

        $exception = substr($tt,20,$startH1-19);
        return "\033[31m "."\n Exeption Message: ".$exception. " \033[0m";
    }


 // @TODO 09 tests - clear data | see below

    //$input = new ArrayInput([
    //'command'=>'doctrine:schema:validate'
    //
    //]);
    //$output = new BufferedOutput();
    //$application = new Application($this->client->getKernel());
    //$application->setAutoExit(false);
    //
    //$application->run($input,$output);
    //var_dump($output->fetch());
    //die;




}