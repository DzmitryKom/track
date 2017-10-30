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
        $track = new Track();
        $form = $this->createForm('AppBundle\Form\TrackType', $track);
        $this->processForm($request,$form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($track);
        $em->flush();
        return $this->setJsonResponse($track,201,$this->generateUrl('api_track_show', array('id' => $track->getId()),UrlGeneratorInterface::ABSOLUTE_PATH));

    }

    /**
     * Finds and displays a track entity.
     *
     * @Route("/{id}", name="api_track_show")
     * @Method("GET")
     */
    public function showAction(Track $track)
    {

        return $this->setJsonResponse($track);
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
        return $this->setJsonResponse(["tracks"=>$tracks, 'count'=>count($tracks)]);
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
        return $this->setJsonResponse($track,200,$this->generateUrl('api_track_show', array('id' => $track->getId()),UrlGeneratorInterface::ABSOLUTE_PATH));
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


            return $this->setJsonResponse(null,204);

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

    private function setJsonResponse($data,$statusCode = 200,$location=null){
        $serializer = $this->get('serializer');
        $jsonContent = $serializer->serialize($data, 'json');

        $response = new Response($jsonContent,$statusCode);
        $response->headers->set('Content-Type','application/json');
        if ($location){
            $response->headers->set('Location',$location);
        }

        return $response;
    }
}