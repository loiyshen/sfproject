<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * 默认控制器，主要是常规通用的功能和画面
 */
class SecurityController extends AbstractController
{
    /**
     * Login
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $formBuilder = $this->createFormBuilder(null, array( 'csrf_protection' => false ))
                ->add('account', 'text',array( 'label'=>'Your Username', 'required'=>true ))
                ->add('passwd', 'password',array( 'label'=>'Your Password', 'required'=>true ))
                ->add('remember_me', 'checkbox',array( 'label'=>'Remember me in 2 weeks.' ,'required'=>false ))
                ->add('login_submit', 'submit',array('label'=>'Login'));
        
        $form = $formBuilder->getForm();
        $session = $request->getSession();
        // get the login error if there is one
        $authErr = SecurityContextInterface::AUTHENTICATION_ERROR;
        if ($request->attributes->has($authErr)) {
            $error = $request->attributes->get($authErr);
        } elseif (null !== $session && $session->has($authErr)) {
            $error = $session->get($authErr);
            $session->remove($authErr);
        } else {
            $error = null;
        }
        return $this->render(
                'AdminBundle:Security:login.html.twig', 
                array(
                    'form' => $form->createView(),
                    'error'         => $error,
                )
            );
    }

    /**
     * Login Check
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }
}
