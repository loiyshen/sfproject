<?php

namespace AdminBundle\Controller;

/**
 * 管理员控制器，管理员用户相关功能
 */
class AdminController extends AbstractController
{
    /**
     * Listing Admin Users
     * @param int $page The page number
     * @return template
     */
    public function listAction($page)
    {
        return $this->render('AdminBundle:Admin:list.html.twig',  compact('page'));
    }

    /**
     * Create an Admin User
     * @return template
     */
    public function addAction()
    {
        return $this->render('AdminBundle:Admin:add.html.twig');
    }

    /**
     * Edit an Admin User
     * @param int $adminId 
     * @return template
     */
    public function editAction($adminId)
    {
        return $this->render('AdminBundle:Admin:edit.html.twig');
    }

    /**
     * Save an Admin User to DB
     * @return template
     */
    public function saveAction()
    {
        //
    }

   /**
     * Delete an Admin User
     * @param int $adminId 
     * @return template
     */
    public function deleteAction($adminId)
    {
        return $this->render('AdminBundle:Admin:delete.html.twig');  
    }

    /**
     * Result for add|edit Admin User
     * @return template
     */
    public function resultAction($actionType)
    {
        return $this->render('AdminBundle:Admin:result.html.twig');
    }
}
