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
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Track controller.
 *
 * @Route("api/tokens")
 */

class TokenController extends BaseController
{



    /**
     * Creates a new track entity.
     *
     * @Route("/new", name="api_token_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserBy(['username'=>$request->getUser()]);

        if(!$user){
            throw $this->createNotFoundException('no user11111');
        }

        $isValid = $this->get('security.password_encoder')->isPasswordValid($user,$request->getPassword());

        if (!$isValid){
//            throw new BadCredentialsException();
            die('dwdew');
        }

        $token = $this->get('lexik_jwt_authentication.encoder')->encode([
            'username'=>$user->getUsername(),
            'exp'=>time()+3600,

        ]);

        return $this->createApiResponse(['token'=>$token],200);
    }


}