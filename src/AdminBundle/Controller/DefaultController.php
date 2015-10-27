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
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    /**
     * Admin Login
     * @return template
     */
    public function loginAction()
    {
        return $this->render('AdminBundle:Default:login.html.twig');
    }
}
