<?php

namespace AppBundle\Controller\Web;


use AppBundle\Entity\LoadInfo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Loadinfo controller.
 *
 * @Route("loadinfo")
 */
class LoadInfoController extends Controller
{
    /**
     * Lists all loadInfo entities.
     *
     * @Route("/", name="loadinfo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $loadInfos = $em->getRepository('AppBundle:LoadInfo')->findAll();

        return $this->render('loadinfo/index.html.twig', array(
            'loadInfos' => $loadInfos,
        ));
    }

    /**
     * Creates a new loadInfo entity.
     *
     * @Route("/new", name="loadinfo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $loadInfo = new Loadinfo();
        $form = $this->createForm('AppBundle\Form\LoadInfoType', $loadInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($loadInfo);
            $em->flush();

            return $this->redirectToRoute('loadinfo_show', array('id' => $loadInfo->getId()));
        }

        return $this->render('loadinfo/new.html.twig', array(
            'loadInfo' => $loadInfo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a loadInfo entity.
     *
     * @Route("/{id}", name="loadinfo_show")
     * @Method("GET")
     */
    public function showAction(LoadInfo $loadInfo)
    {
        $deleteForm = $this->createDeleteForm($loadInfo);

        return $this->render('loadinfo/show.html.twig', array(
            'loadInfo' => $loadInfo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing loadInfo entity.
     *
     * @Route("/{id}/edit", name="loadinfo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LoadInfo $loadInfo)
    {
        $deleteForm = $this->createDeleteForm($loadInfo);
        $editForm = $this->createForm('AppBundle\Form\LoadInfoType', $loadInfo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('loadinfo_edit', array('id' => $loadInfo->getId()));
        }

        return $this->render('loadinfo/edit.html.twig', array(
            'loadInfo' => $loadInfo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a loadInfo entity.
     *
     * @Route("/{id}", name="loadinfo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, LoadInfo $loadInfo)
    {
        $form = $this->createDeleteForm($loadInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($loadInfo);
            $em->flush();
        }

        return $this->redirectToRoute('loadinfo_index');
    }

    /**
     * Creates a form to delete a loadInfo entity.
     *
     * @param LoadInfo $loadInfo The loadInfo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LoadInfo $loadInfo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('loadinfo_delete', array('id' => $loadInfo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
