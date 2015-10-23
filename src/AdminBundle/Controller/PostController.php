<?php

namespace AdminBundle\Controller;

/**
 * 文章控制器，文章相关功能
 */
class PostController extends AbstractController
{
    /**
     * Listing Posts
     * @param int $page The page number
     * @return template
     */
    public function listAction($page)
    {
        return $this->render('AdminBundle:Post:list.html.twig',  compact('page'));
    }

    /**
     * Show a Post Detail
     * @param int $postId 
     * @return template
     */
    public function detailAction($postId)
    {
        return $this->render('AdminBundle:Post:detail.html.twig');
    }

    /**
     * Create a Post
     * @return template
     */
    public function addAction()
    {
        return $this->render('AdminBundle:Post:add.html.twig');
    }

    /**
     * Edit a Post
     * @param int $postId 
     * @return template
     */
    public function editAction($postId)
    {
        return $this->render('AdminBundle:Post:edit.html.twig');
    }

    /**
     * Result for add|edit Post
     * @return template
     */
    public function saveAction()
    {
        //
    }

   /**
     * Delete a Post
     * @param int $postId
     * @return template
     */
    public function deleteAction($postId)
    {
        return $this->render('AdminBundle:Post:delete.html.twig');  
    }

    /**
     * Result for add|edit Post
     * @return template
     */
    public function resultAction($actionType)
    {
        return $this->render('AdminBundle:Post:result.html.twig');
    }
}
