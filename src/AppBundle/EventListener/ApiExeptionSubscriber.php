<?php
/**
 * Created by PhpStorm.
 * User: DKomsa
 * Date: 11/6/2017
 * Time: 12:03 PM
 */

namespace AppBundle\EventListener;


use AppBundle\Api\ApiProblem;
use AppBundle\Api\ApiProblemException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExeptionSubscriber implements EventSubscriberInterface
{
    private $debug;

    /**
     * ApiExeptionSubscriber constructor.
     * @param $debug
     */
    public function __construct($debug)
    {
        $this->debug = $debug;
    }


    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION =>'onKernelException'];
    }

    public function onKernelException (GetResponseForExceptionEvent $event){

        $e =  $event->getException();
        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode(): 500;

        if( $statusCode == 500 && $this->debug){
            return;
        }


        if ($e instanceof ApiProblemException){
            $apiProblem = $e->getApiProblem();
        }else{
            if (!(substr($event->getRequest()->getPathInfo(),0,5)==='/api/')){
                return;
            }
            $apiProblem = new ApiProblem($statusCode);
            if ($e instanceof HttpExceptionInterface){
                $apiProblem->set("detail",$e->getMessage());
            }
        }

        $data = $apiProblem->toArray();
        if ($data['type'] != 'about:blank'){
            $data['type'] = 'http//dsfskh/erors/#'.$data['type'];
        }
//       @TODO urls error

        $response = new JsonResponse($data,$apiProblem->getStatusCode());
        $response->headers->set('Content-Type','application/problem+json');
        $event->setResponse($response);
    }


}