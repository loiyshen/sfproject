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
                ->add('account', 'text',array( 'label' => $this->getCfgParameter('login.text.account'), 'required'=>true ))
                ->add('passwd', 'password',array( 'label' =>  $this->getCfgParameter('login.text.passwd'), 'required'=>true ))
                ->add('remember_me', 'checkbox',array( 'label' => $this->getCfgParameter('login.text.remember_me'),'required'=>false ))
                ->add('login_submit', 'submit',array('label' => $this->getCfgParameter('login.text.login_submit')));
        //get the form
        $form = $formBuilder->getForm();
        $session = $request->getSession();
        // get the login error if there is one
        $authErr = SecurityContextInterface::AUTHENTICATION_ERROR;
        if ($request->attributes->has($authErr)) {
            //$error = $request->attributes->get($authErr);
            $errMsg = $this->getCfgParameter('login.err.auth_fail');
        } elseif (null !== $session && $session->has($authErr)) {
            //$error = $session->get($authErr);
            $session->remove($authErr);
            $errMsg = $this->getCfgParameter('login.err.auth_fail');
        } else {
            //$error = null;
            $errMsg = null;
        }
        //render template
        return $this->render(
                'AdminBundle:Security:login.html.twig', 
                array(
                    'form' => $form->createView(),
                    'errMsg'  => $errMsg,
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
