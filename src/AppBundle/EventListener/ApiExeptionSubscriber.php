<?php
/**
 * Created by PhpStorm.
 * User: DKomsa
 * Date: 11/6/2017
 * Time: 12:03 PM
 */

namespace AppBundle\EventListener;


use AppBundle\Api\ApiProblemException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExeptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
       return [KernelEvents::EXCEPTION =>'onKernelException'];
    }

    public function onKernelException (GetResponseForExceptionEvent $event){

       $e =  $event->getException();
       if (!$e instanceof ApiProblemException){
           return;
       }

       $response = new JsonResponse($e->getApiProblem()->toArray(),$e->getApiProblem()->getStatusCode());
       $response->headers->set('Content-Type','application/problem+json');
       $event->setResponse($response);
    }


}