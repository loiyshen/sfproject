<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityRepository;

class AbstractController extends Controller
{

    const ENTITY_BUNDLE = "CommonBundle";
    const FORM_NAMESPACE = "CommonBundle\Form\\";

    /**
     * Get Entity Manager
     * 
     * @param string $name The name of the service(null for the default one).
     * @return object The instance of the given service.
     */
    public function getEntityManager($name = NULL)
    {
        //$this->container->get('doctrine')->getManager($name);
        return $this->getDoctrine()->getManager($name);
    }

    /**
     * Gets the EntityRepository for an entity.
     * @param string $entityName        The name of the entity.
     * @param string $entityManagerName The name of the service(null for the default one).
     * @return EntityRepository
     */
    public function getEntityRepository($entityName, $entityManagerName = NULL)
    {
        return $this->container->get('doctrine')->getRepository($entityName, $entityManagerName);
    }

    /**
     * Gets the value of the key in the configuration files.
     * @param string $key The key in configuration file.
     * @param string $def The default value.
     * @return string|array 
     */
    public function getCfgParameter($key, $def = NULL)
    {
        if ($this->container->hasParameter($key)) {
            return $this->container->getParameter($key);
        }
        return $def;
    }

    /**
     * Creates and returns a Form instance
     * @param string      $type    The built type of the form
     * @param mixed       $data    The initial data for the form
     * @param array       $options Options for the form
     * @return Form
     */
    public function getForm($type, $data = NULL, array $options = array())
    {
        $type = self::FORM_NAMESPACE . $type;
        $formType = new $type();
        $formType->setContainer($this->container);
        $form = $this->createForm($formType, $data, $options);
        return $form;
    }

    /**
     * Gets the option value by the key of the form.
     * @param  Form    $form 
     * @param  string  $key option key
     * @param  mixed   $def default value
     * @return mixed
     */
    public function getFormOption($form, $key, $def = NULL)
    {
        if ($form->getConfig()->hasOption($key)) {
            return $form->getConfig()->getOption($key);
        }
        return $def;
    }

    /**
     * Check the request method is [POST] or not.
     * @return bool True for [POST], False otherwise. 
     */
    public function isPost()
    {
        return $this->getRequest()->getMethod() === "POST";
    }

    /**
     * Get the form data by POST
     * @param Form  $form The built type of the form
     * @return array 
     */
    public function getPostData(Form $form)
    {
        return $this->getRequest()->request->get($form->getName());
    }

    /**
     * Check the request method is [GET] or not.
     * @return bool True for [GET], False otherwise. 
     */
    public function isGet()
    {
        return $this->getRequest()->getMethod() === "GET";
    }

    /**
     * Get the request parameter
     * @param string $paramName The Parameter Name
     * @param mixed  $def The Default Value
     * @return mixed $ret The parameter value or default value.
     */
    public function getRequestParam($paramName, $def = null)
    {
        $ret = $this->getRequest()->get($paramName, $def);
        return $ret;
    }

    /**
     * Gets all session data.
     * @return SessionInterface|null All Session Data.
     */
    public function getAllSession()
    {
        return $this->getRequest()->getSession();
    }

    /**
     * Sets an attribute to the session.
     * @param string $key The session key.
     * @param mixed  $value The value for the key.
     * @return 
     */
    public function setSession($key, $value)
    {
        return $this->getSession()->set($key, $value);
    }

    /**
     * Gets an attribute from the session.
     * @param string $key The session key.
     * @param mixed  $def The default value if not found.
     * @return mixed $ret
     */
    public function getSession($name, $def = NULL)
    {
        $ret = $this->getSession()->get($name, $def);
        return $ret;
    }

    /**
     * Whether the session has the attribute of the key.
     * @param string $key The session key.
     * @return bool $ret True for has been set, False otherwise.
     */
    public function hasSession($key)
    {
        $ret = $this->getSession()->has($key);
        return $ret;
    }

    /**
     * Removes an attribute of the session.
     * @param string $name
     * @return mixed $ret The removed value or null when it does not exist.
     */
    public function removeSession($name)
    {
        $ret = $this->getSession()->remove($name);
        return $ret;
    }
    
    /**
     * Get a user from the Security Context.
     * @return Object $user
     */
    public function getLoginUser()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        return $user;
    }

}