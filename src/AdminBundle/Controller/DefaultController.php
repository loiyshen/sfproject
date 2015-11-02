<?php

namespace AdminBundle\Controller;

/**
 * 默认控制器，主要是常规通用的功能和画面
 */
class DefaultController extends AbstractController
{
    /**
     * Show the homepage
     * @return template
     */
    public function indexAction()
    {
        $session = $this->container->get('session');
        $clientIp = $session->get('client_ip');
        $loginUser = $this->getUser();
        
        return $this->render('AdminBundle:Default:index.html.twig',
                array(
                    'client_ip' => $clientIp,
                    'login_user' => $loginUser,
                )
                );
    }
}
