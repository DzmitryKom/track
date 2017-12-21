<?php

namespace AppBundle\Tests\Controller\Api;

use AppBundle\Api\ApiProblem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TokenControllerTest extends ApiTestCase
{


    public function testPOSTCreateToken(){

        $userData=[
            'username'=>'test1',
            'email'=>'test1@test.com',
            'firstName'=>'firstT',
            'password'=>'password1'
        ];
        $this->createUser($userData);

        $crawler = $this->client->request('POST', '/api/tokens/new',[],[],['PHP_AUTH_USER' => $userData['username'], 'PHP_AUTH_PW'   => $userData['password'],]);

//        $crawler = $this->client->request('POST', '/api/tokens/new',['_username' => $userData['username'], '_password' => $userData['password']],[]);

        $responseText = $this->client->getResponse()->getContent();
        var_dump($responseText);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());


        $this->assertJson($responseText);


        $responseTextObject = json_decode($responseText);
//        var_dump($responseTextObject);
        $this->assertObjectHasAttribute('token',$responseTextObject);
        
    }

    public function testPOSTInvalidCredentialsCreateToken(){

        $userData=[
            'username'=>'test',
            'email'=>'test@test.com',
            'firstName'=>'firstT',
            'password'=>'password1'
        ];
        $this->createUser($userData);

//        $crawler = $this->client->request('POST', '/api/tokens/new',['_username' => $userData['username'], '_password' => 'wrong'],[]);

        $crawler = $this->client->request('POST', '/api/tokens/new',[],[],['PHP_AUTH_USER' => $userData['username'],
            'PHP_AUTH_PW'   => 'wrong',]);


//        $this->assertEquals(401, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());
        $responseText = $this->client->getResponse()->getContent();
        var_dump($responseText);

        $this->assertJson($responseText);
        $responseTextObject = json_decode($responseText);
        $this->assertObjectNotHasAttribute('token',$responseTextObject);

    }

}
