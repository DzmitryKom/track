<?php
/**
 * Created by PhpStorm.
 * User: DKomsa
 * Date: 10/23/2017
 * Time: 3:11 PM
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Track;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Track controller.
 *
 * @Route("api/tracks")
 */

class TrackController extends Controller
{

    /**
     * Creates a new track entity.
     *
     * @Route("/new", name="api_track_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
//        $encoders = array( new JsonEncoder());
//        $normalizers = array(new ObjectNormalizer());

//        $serializer = new Serializer($normalizers, $encoders);
        $serializer = $this->get('symfony.component.serializer.serializer');

        $track = new Track();
        $form = $this->createForm('AppBundle\Form\TrackType', $track);
        $this->processForm($request,$form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($track);
        $em->flush();
        $jsonContent = $serializer->serialize($track, 'json');

        $response = new Response($jsonContent,201);
        $response->headers->set('Location',$this->generateUrl('api_track_show', array('id' => $track->getId()),UrlGeneratorInterface::ABSOLUTE_PATH));
        return $response;

    }

    /**
     * Finds and displays a track entity.
     *
     * @Route("/{id}", name="api_track_show")
     * @Method("GET")
     */
    public function showAction(Track $track)
    {
        $response = new JsonResponse((array)$track);
        return $response;
    }

    /**
     * Lists all track entities.
     *
     * @Route("/", name="api_track_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tracks = $em->getRepository('AppBundle:Track')->findAll();

        $response = new JsonResponse(["tracks"=>$tracks, 'count'=>count($tracks)]);
        return $response;
    }


    /**
     * Displays a form to edit an existing track entity.
     *
     * @Route("/{id}/edit", name="api_track_edit")
     * @Method({"PUT","PATCH"})
     */
    public function editAction(Request $request, Track $track)
    {
        $editForm = $this->createForm('AppBundle\Form\TrackType', $track);
        $this->processForm($request,$editForm);

        $this->getDoctrine()->getManager()->flush();
        $response = new JsonResponse((array)$track,200);
        $response->headers->set('Location',$this->generateUrl('api_track_show', array('id' => $track->getId()),UrlGeneratorInterface::ABSOLUTE_PATH));
        return $response;
    }

    /**
     * Deletes a track entity.
     *
     * @Route("/{id}", name="api_track_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
            $em = $this->getDoctrine()->getManager();
            $track = $em->getRepository('AppBundle:Track')->find($id);
            if($track){
                $em->remove($track);
                $em->flush();
            }


            return new Response("",204);

    }

    private function getDataBodyFromJsonRequest(Request $request){
        $body = $request->getContent();
        $data = json_decode($body,true);



        if (!$data){
            throw new Exception('Bad json');
        }
        return $data;

    }

    private function processForm(Request $request, Form $form){
        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($this->getDataBodyFromJsonRequest($request),$clearMissing);
    }
}