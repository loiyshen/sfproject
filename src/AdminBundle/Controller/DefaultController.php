<?php

namespace AdminBundle\Controller;

class DefaultController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    public function helloAction($name)
    {
        if($this->isGet()){
            $zip = $this->getRequestParam('zip');
        }
        return $this->render('AdminBundle:Default:hello.html.twig', array('name' => $name, 'zip' => $zip));
    }
}
