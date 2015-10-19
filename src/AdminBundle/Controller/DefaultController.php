<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }
	
	public function helloAction($name)
    {
        return $this->render('AdminBundle:Default:hello.html.twig', array('name' => $name));
    }
}
