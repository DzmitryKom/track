<?php

namespace AppBundle\Tests\Controller\Api;

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

        $data = [
            "make"=>'Mercedes',
            "model"=>'Diesel 456',
            "bodyType"=>'van',
            "miles"=>rand(0,2000000),
        ];

        $crawler = $this->client->request('POST', '/api/tracks/new',[],[],[], json_encode($data));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for: ".$crawler->getUri()." ".$this->client->getResponse()->getContent());
        $this->assertContains((string)$data['miles'], $this->client->getResponse()->getContent());
        $this->assertTrue($this->client->getResponse()->headers->has('location'));
        var_dump($this->client->getResponse()->getContent());
        var_dump($this->client->getResponse()->headers);

        $crawler = $this->client->request('GET', $this->client->getResponse()->headers->get('location'),[],[],[]);
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

}
