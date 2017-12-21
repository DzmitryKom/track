<?php

namespace AppBundle\Tests\Controller\Api;

use AppBundle\Api\ApiProblem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrackControllerTest extends ApiTestCase
{



    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/api/tracks');

        $this->assertContains('track', $this->client->getResponse()->getContent());
    }

    public function testNewAndShow()
    {

        $token = $this->container->get('lexik_jwt_authentication.encoder')->encode(['username'=>'test1']);

        $authHeaders = [
            'HTTP_AUTHORIZATION' => 'Bearer '.$token,
//            'Authorization' => 'Bearer '.$token,
            'CONTENT_TYPE' => 'application/json',
        ];

        $data = [
            "make"=>'Mercedes',
            "model"=>'Diesel 456',
            "bodyType"=>'van',
            "miles"=>rand(0,2000000),
        ];

        $crawler = $this->client->request('POST', '/api/tracks/new',[],[],$authHeaders, json_encode($data));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());
        $this->assertContains((string)$data['miles'], $this->client->getResponse()->getContent());
        $this->assertTrue($this->client->getResponse()->headers->has('location'));
        $this->assertTrue($this->client->getResponse()->headers->has('content-type'));
        $this->assertEquals($this->client->getResponse()->headers->get('content-type'),"application/json");

//        var_dump($this->client->getResponse()->getContent());
//        var_dump($this->client->getResponse()->headers);

        $crawler = $this->client->request('GET', $this->client->getResponse()->headers->get('location'),[],[],$authHeaders);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());
        $this->assertContains((string)$data['miles'], $this->client->getResponse()->getContent());


    }

    public function testEditAndShow()
    {
        $track = $this->getTrack();

        $data = [
            "make"=>'Mercedes',
            "model"=>'Diesel 4563',
            "bodyType"=>'van',
            "miles"=>rand(0,2000000),
        ];

        $crawler = $this->client->request('PUT', '/api/tracks/'.$track->getId().'/edit',[],[],[], json_encode($data));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());
        $this->assertContains((string)$data['miles'], $this->client->getResponse()->getContent());
        $this->assertTrue($this->client->getResponse()->headers->has('location'));


        $crawler = $this->client->request('GET', $this->client->getResponse()->headers->get('location'),[],[],[]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());
        $this->assertContains((string)$data['miles'], $this->client->getResponse()->getContent());

//        var_dump($client->getResponse()->getContent());
//        var_dump($client->getResponse()->headers);
    }


    public function testPATCHEditAndShow()
    {
        $track = $this->getTrack();

        $data = [
            "model"=>'Diesel 4563',
            "bodyType"=>'van',
            "miles"=>rand(0,2000000),
        ];

        $crawler = $this->client->request('PATCH', '/api/tracks/'.$track->getId().'/edit',[],[],[], json_encode($data));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());
        $this->assertContains((string)$data['miles'], $this->client->getResponse()->getContent());
        $this->assertTrue($this->client->getResponse()->headers->has('location'));


        $crawler = $this->client->request('GET', $this->client->getResponse()->headers->get('location'),[],[],[]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());
        $this->assertContains((string)$data['miles'], $this->client->getResponse()->getContent());

//        var_dump($client->getResponse()->getContent());
//        var_dump($client->getResponse()->headers);
    }



    public function testDelete()
    {
        $track = $this->getTrack();

        $crawler = $this->client->request('DELETE', '/api/tracks/'.$track->getId() );

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent()." ".$this->client->getResponse()->getContent());

    }

    private function getTrack(){
        return $this->entityManager->getRepository('AppBundle:Track')->findOneBy([],['id'=>'ASC']);
    }


    public function testNewValidationsErrors()
    {

        $data = [
            "model"=>'Diesel 456',
            "bodyType"=>'van',
            "miles"=>rand(0,2000000),
        ];

        $crawler = $this->client->request('POST', '/api/tracks/new',[],[],[], json_encode($data));


        $this->assertEquals(400, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());
        $responseText = $this->client->getResponse()->getContent();

        $this->assertJson($responseText);
        $responseTextObject = json_decode($responseText);
//        var_dump($responseTextObject);
        $this->assertObjectHasAttribute('type',$responseTextObject);
        $this->assertObjectHasAttribute('title',$responseTextObject);
        $this->assertObjectHasAttribute('errors',$responseTextObject);
        $this->assertObjectNotHasAttribute('model',$responseTextObject->errors);

        $this->assertObjectHasAttribute('make',$responseTextObject->errors);
        $this->assertEquals('Please enter Make',$responseTextObject->errors->make[0]);
    }


    public function testEditValidationsErrors()
    {

        $track = $this->getTrack();

        $data = [
            "make"=>null,
            "model"=>'Diesel 4563',
            "bodyType"=>'van',
            "miles"=>rand(0,2000000),
        ];

        $crawler = $this->client->request('PUT', '/api/tracks/'.$track->getId().'/edit',[],[],[], json_encode($data));


        $this->assertEquals(400, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());
        $responseText = $this->client->getResponse()->getContent();

        $this->assertJson($responseText);
        $this->assertTrue($this->client->getResponse()->headers->has('content-type'));
        $this->assertEquals("application/problem+json",$this->client->getResponse()->headers->get('content-type'));
        $responseTextObject = json_decode($responseText);
//        var_dump($responseTextObject);
        $this->assertObjectHasAttribute('type',$responseTextObject);
        $this->assertObjectHasAttribute('title',$responseTextObject);
        $this->assertObjectHasAttribute('errors',$responseTextObject);
        $this->assertObjectNotHasAttribute('model',$responseTextObject->errors);

        $this->assertObjectHasAttribute('make',$responseTextObject->errors);
        $this->assertEquals('Please enter Make',$responseTextObject->errors->make[0]);
    }

    public function testInvalidJson()
    {

        $track = $this->getTrack();

        $invalidJsonData = <<<EOF
{
 "make":null
            "model":'Diesel 4563',
}
EOF;
        $crawler = $this->client->request('PUT', '/api/tracks/'.$track->getId().'/edit',[],[],[], $invalidJsonData);


        $this->assertEquals(400, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri().$this->getExceptionFromSymfonyExceptionResponse());
        $responseText = (string)$this->client->getResponse()->getContent();
//        var_dump($this->client->getResponse()->headers);
//        var_dump($responseText);
        $this->assertJson($responseText,$this->getExceptionFromSymfonyExceptionResponse());
        $this->assertTrue($this->client->getResponse()->headers->has('content-type'));
        $this->assertEquals("application/problem+json",$this->client->getResponse()->headers->get('content-type'));
        $responseTextObject = json_decode($responseText);
        $this->assertObjectHasAttribute('type',$responseTextObject);
        $this->assertContains('invalid_body_format',$responseTextObject->type);


        $this->assertObjectHasAttribute('title',$responseTextObject);
    }

    public function test404()
    {
        $crawler = $this->client->request('PUT', '/api/tracks/'.'cd'.'/edit');


        $this->assertEquals(404, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri().$this->getExceptionFromSymfonyExceptionResponse());
        $responseText = (string)$this->client->getResponse()->getContent();
//        var_dump($this->client->getResponse()->headers);
//        var_dump($responseText);
        $this->assertJson($responseText,$this->getExceptionFromSymfonyExceptionResponse());
        $this->assertTrue($this->client->getResponse()->headers->has('content-type'));
        $this->assertEquals("application/problem+json",$this->client->getResponse()->headers->get('content-type'));
        $responseTextObject = json_decode($responseText);
        $this->assertObjectHasAttribute('type',$responseTextObject);
        $this->assertEquals('about:blank',$responseTextObject->type);
        $this->assertObjectHasAttribute('title',$responseTextObject);
        $this->assertEquals('Not Found',$responseTextObject->title);
        $this->assertObjectHasAttribute('detail',$responseTextObject);



    }

    public function testNoRoute()
    {
        $crawler = $this->client->request('GET', '/api/tracks/'.'cd'.'/edit');


        $this->assertEquals(405, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri().$this->getExceptionFromSymfonyExceptionResponse());
        $responseText = (string)$this->client->getResponse()->getContent();
//        var_dump($this->client->getResponse()->headers);
//        var_dump($responseText);
        $this->assertJson($responseText,$this->getExceptionFromSymfonyExceptionResponse());
        $this->assertTrue($this->client->getResponse()->headers->has('content-type'));
        $this->assertEquals("application/problem+json",$this->client->getResponse()->headers->get('content-type'));
        $responseTextObject = json_decode($responseText);
        $this->assertObjectHasAttribute('type',$responseTextObject);
        $this->assertEquals('about:blank',$responseTextObject->type);
        $this->assertObjectHasAttribute('title',$responseTextObject);
        $this->assertObjectHasAttribute('detail',$responseTextObject);

    }

    public function testRequiresAuth()
    {

        $crawler = $this->client->request('GET', '/api/tracks',[],[],[

        ]);

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri().$this->getExceptionFromSymfonyExceptionResponse());
        $responseText = (string)$this->client->getResponse()->getContent();
//        $responseTextObject = json_decode($responseText);
//                var_dump($this->client->getResponse()->headers);
        var_dump($responseText);
    }


}
