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
                ->add('account', 'text',array( 'label'=>'用户名:', 'required'=>true ))
                ->add('passwd', 'password',array( 'label'=>'密码:', 'required'=>true ))
                ->add('remember_me', 'checkbox',array( 'label'=>'2周内自动登录' ,'required'=>false ))
                ->add('login_submit', 'submit',array());
        
        $form = $formBuilder->getForm();
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
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
