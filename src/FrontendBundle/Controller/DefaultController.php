<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrontendBundle:Default:index.html.twig');
    }
	
	public function helloAction($name)
    {
        return $this->render('FrontendBundle:Default:hello.html.twig', array('name' => $name));
    }
}
