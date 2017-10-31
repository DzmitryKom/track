<?php
/**
 * Created by PhpStorm.
 * User: DKomsa
 * Date: 10/31/2017
 * Time: 9:48 AM
 */

namespace AppBundle\Controller\Api;


use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    protected function getDataBodyFromJsonRequest(Request $request){
        $body = $request->getContent();
        $data = json_decode($body,true);

        if (!$data){
            throw new Exception('Bad json');
        }
        return $data;

    }

    protected function processForm(Request $request, Form $form){
        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($this->getDataBodyFromJsonRequest($request),$clearMissing);
    }

    protected function createApiResponse($data, $statusCode = 200, $location=null){
        $serializer = $this->get('serializer');
        $jsonContent = $serializer->serialize($data, 'json');

        $response = new Response($jsonContent,$statusCode);
        $response->headers->set('Content-Type','application/json');
        if ($location){
            $response->headers->set('Location',$location);
        }

        return $response;
    }

    protected function getErrorsFromForm(FormInterface $form){
        $errors = [];
        foreach ($form->getErrors() as $error){
            $errors[]= $error->getMessage();
        }
        foreach ($form->all() as $childForm){
            if ($childForm instanceof FormInterface){
                if($childErrors = $this->getErrorsFromForm($childForm)){
                    $errors[$childForm->getName()]=$childErrors;
                }
            }
        }
        return $errors;
    }


    protected function createValidationErrorResponse(FormInterface $form){
        $errors = $this->getErrorsFromForm($form);
        $data = [
            'type'=>'validation_error',
            'title'=> 'There was a validation error',
            'errors'=>$errors

        ];
        return $this->createApiResponse($data, 400);
    }

    protected function createForm($type, $data = null, array $options = array())
    {
        $options['csrf_protection']=false;
        return parent::createForm($type, $data,$options);
    }
}