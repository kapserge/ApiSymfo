<?php
namespace App\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
class TokenAuthenticator extends AbstractGuardAuthenticator{


    /**
     * Called on every request to decide if this authenticator should be
     * used for the request.
     */
    public function supports(Request $request)
    {
        return $request->headers->has('AUTH-TOKEN');
    }
    /**
     * Return whatever credentials you want to be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        return array(
            'token' => $request->headers->get('AUTH-TOKEN'),
        );
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiKey = $credentials['token'];
        if (null === $apiKey) {
            return "";
        }
        // if a User object, checkCredentials() is called
        return $userProvider->loadUserByUsername($apiKey);
    }
    public function checkCredentials( $credentials , UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case
        // return true to cause authentication success
        return true;
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey )
    {
        // on success, let the request continue
        return null;
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        );
        return new JsonResponse( $data, Response:: HTTP_FORBIDDEN);
    }
    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            'message' => 'Authentication Required'
        );
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
    public function supportsRememberMe()
    {
        return false;
    }
}

