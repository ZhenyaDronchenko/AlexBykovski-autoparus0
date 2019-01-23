<?php

namespace App\Security\Authentication\Handler;

use App\Entity\Admin;
use App\Entity\General\RegistrationPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * LoginSuccessHandler constructor.

     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $token->getUser();

        $response = new RedirectResponse($this->router->generate('show_user_office'));

        if($user instanceof Admin){
            $response = new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
        }

        $response->headers->clearCookie(RegistrationPage::SAVING_COOKIE_KEY);

        return $response;
    }
}
