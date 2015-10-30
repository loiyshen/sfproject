<?php

namespace AdminBundle\Services;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminLoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor.
     * 
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Gets the EntityRepository for an entity.
     * @param string $entityName        The name of the entity.
     * @param string $entityManagerName The entity manager name (null for the default one)
     * @return EntityRepository
     */
    public function getEntityRepository($entityName, $entityManagerName = NULL)
    {
        return $this->container->get('doctrine')->getRepository($entityName, $entityManagerName);
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return Response the response to return
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /**
         * 登录成功后，将要跳转的路径
         * 注： security_admin.yml中设定的 default_target_path 就不起作用了
         */
        $targetPath = 'adm_homepage';

        /**
         * 通过$request来取得相关参数(如：server/cookie/GET/POST参数等)
         */
        // 取得用户的IP
        $clientIp = $request->getClientIp();
        
        // 取得session服务
        $session = $this->container->get('session');
        
        $session->set('client_ip', $clientIp);

        /**
         * 这里可以通过取得EntityRepository来取得DB中的相关数据
         * 然后进行必要的操作，比如：保存到Session, Cookie等等。
         * 
         * $postRepository = $this->getEntityRepository('Post');
         * $postCount = $postRepository->getAllPostCount();
         * $session->set('admin_post_count', $postCount);
         */
        
        /**
         * 取得当前登录的User对象
         */
         $user = $token->getUser();
         $session->set('login_user', $user);

        /** 
         * User和Admin其实是一样的，通过 $user 来取得 Admin 的实例
         * $adminId = $user->getId();
         * $AdminRepository = $this->getEntityRepository('Admin');
         * $admin = $AdminRepository->getAdminByAdminId($adminId);
         * 保存到 Session 中
         * $session->set('login_admin', $admin);
         */
        
        // 取得router服务
        $router = $this->container->get('router');
        $redirectUrl = $router->generate($targetPath, array());
        return new RedirectResponse($redirectUrl);
    }
}