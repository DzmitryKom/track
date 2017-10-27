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
        self::$clientStatic->setMaxRedirects(10);
    }

    public function setup(){
        $this->client = self::$clientStatic;
        $this->container = $this->client->getContainer();
        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');

    }

    protected function createUser(array $data){
        $data = array_merge([
            'planePassword'=>'password1'
        ], $data);
        $accessor = PropertyAccess::createPropertyAccessor();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        foreach ($data as $key => $value){
            $accessor->setValue($user, $key, $value);
        }
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