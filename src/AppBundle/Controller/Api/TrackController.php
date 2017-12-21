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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Track controller.
 *
 * @Route("api/tracks")
 */

class TrackController extends BaseController
{



    /**
     * Creates a new track entity.
     *
     * @Route("/new", name="api_track_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        var_dump($this->getUser());
        $track = new Track();
        $form = $this->createForm('AppBundle\Form\TrackType', $track);
        $this->processForm($request,$form);
        if (! $form->isValid()){
            $this->throwAdiProblemValidationException($form);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($track);
        $em->flush();
        return $this->createApiResponse($track,201,$this->generateUrl('api_track_show', array('id' => $track->getId()),UrlGeneratorInterface::ABSOLUTE_PATH));

    }

    /**
     * Finds and displays a track entity.
     *
     * @Route("/{id}", name="api_track_show")
     * @Method("GET")
     */
    public function showAction(Track $track)
    {

        return $this->createApiResponse($track);
    }

    /**
     * Lists all track entities.
     *
     * @Route("/", name="api_track_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em = $this->getDoctrine()->getManager();
        $tracks = $em->getRepository('AppBundle:Track')->findAll();
        return $this->createApiResponse(["tracks"=>$tracks, 'count'=>count($tracks)]);
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
        if (! $editForm->isValid()){
            $this->throwAdiProblemValidationException($editForm);
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->createApiResponse($track,200,$this->generateUrl('api_track_show', array('id' => $track->getId()),UrlGeneratorInterface::ABSOLUTE_PATH));
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
        return $this->createApiResponse(null,204);
    }


}