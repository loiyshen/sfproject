<?php

namespace AdminBundle\Controller;

//use CommonBundle\EntityValidate\Admin;
//use CommonBundle\Util\EntityHelper;

class AdminController extends AbstractController
{

    public $sessionKey = 'admin_sess';

    /**
     * トップページ
     * @return template
     */
    public function indexAction()
    {
        return $this->render('AdminBundle:Admin:index.html.twig', array());
    }

    /**
     * 管理者リスト
     */
    public function listAction()
    {
        $where = array();
        $administratorRepository = $this->container->get('repository_helper')->getRepository('Administrator');
        $where['cur_page'] = $this->getRequest()->get('pageNo');
        $pager = $administratorRepository->getAdministratorList($where);
        return $this->render('AdminBundle:Admin:list.html.twig', array(
                    'pager' => $pager,
                    'whereData' => array())
        );
    }

    /**
     * 管理者新規作成
     */
    public function newAction()
    {
        $request = $this->getRequest();
        $form = $this->getForm('AdminType', new Administrator(), array('validation_groups' => array('new', 'Default')));

        $formIsValid = TRUE;
        if ($this->isPost()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $admin = $form->getData();
                $enterprise = array();
                if ($admin->getEnterpriseId()) {
                    $enterprise = $this->getRepository('Enterprise')->getObjectById($admin->getEnterpriseId());
                }
                $this->sessionSet($this->sessionKey, clone $admin);
                $role = $admin->getRole();
                $adminType = $this->container->getParameter('admin_type');
                $admin->setRole($adminType[$role]);
                return $this->render('AdminBundle:Admin:confirm.html.twig', compact('admin', 'enterprise', 'role'));
            }
            $formIsValid = FALSE;
        } else {
            $adminValidateEntity = $this->getValidateEntity('new');
            $form->setData($adminValidateEntity);
        }

        $form = $form->createView();
        return $this->render('AdminBundle:Admin:input.html.twig', compact('form', 'formIsValid'));
    }

    /**
     * 管理者編集
     */
    public function editAction()
    {
        $request = $this->getRequest();
        $adminId = $request->get('adminId');
        if (!$adminId) {
            return $this->redirectErrorPage('ERR001');
        }
        $form = $this->getForm('AdminType', new Administrator(), array('validation_groups' => array('edit', 'Default')));

        $formIsValid = TRUE;
        if ($this->isPost()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $admin = $form->getData();
                $enterprise = array();
                if ($admin->getEnterpriseId()) {
                    $enterprise = $this->getRepository('Enterprise')->getObjectById($admin->getEnterpriseId());
                }
                $this->sessionSet($this->sessionKey, clone $admin);
                $role = $admin->getRole();
                $adminType = $this->container->getParameter('admin_type');
                $admin->setRole($adminType[$role]);
                return $this->render('AdminBundle:Admin:confirm.html.twig', compact('admin', 'enterprise', 'role'));
            }
            $formIsValid = FALSE;
        } else {
            $adminValidateEntity = $this->getValidateEntity('edit');
            if ($adminValidateEntity) {
                $form->setData($adminValidateEntity);
            } else {
                $administratorRepository = $this->getRepository('Administrator');
                $admin = $administratorRepository->getObjectById($adminId);
                if (!$admin) {
                    return $this->redirectErrorPage('ERR001');
                }
                $adminValidateEntity = EntityHelper::getValidateEntityInstance('Administrator', $admin);
                $enterprise = $admin->getEnterprise();
                $enterprise = $enterprise ? $this->getRepository('Enterprise')->getObjectById($enterprise->getId()) : NULL;
                if ($enterprise) {
                    $adminValidateEntity->setEnterpriseId($enterprise->getId());
                }
                $form->setData($adminValidateEntity);
            }
            
        }

        $form = $form->createView();
        return $this->render('AdminBundle:Admin:input.html.twig', compact('form', 'formIsValid'));
    }

    /**
     * 管理者情報登録
     * @return template
     */
    public function saveAction()
    {
        $adminValidateEntity = $this->getValidateEntity('save');
        if (!$adminValidateEntity) {
            return $this->redirectErrorPage('ERR001');
        }

        $adminId = $adminValidateEntity->getId();
        $isEdit = $adminId ? TRUE : FALSE;
        $administratorRepository = $this->getRepository('Administrator');
        if ($isEdit) {
            $admin = $administratorRepository->getObjectById($adminId);
        } else {
            $admin = EntityHelper::getEntityInstance('Administrator');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();
        if ($adminValidateEntity->getPassword()) {
            $adminValidateEntity->setPassword($user->setPassword($adminValidateEntity->getPassword()));
        } else if ($isEdit) {
            $adminValidateEntity->setPassword($admin->getPassword());
        }
        $admin = $administratorRepository->save($admin, $adminValidateEntity);
        if ($admin) {
            $adminId = $admin->getId();
            return $this->redirect($this->generateUrl('admin_finish', compact('adminId', 'isEdit')));
        }
        return $this->redirectErrorPage('ERR001');
    }

    /**
     * 管理者情報登録完了
     * @return template
     */
    public function finishAction()
    {
        $request = $this->getRequest();
        $adminId = $request->get('adminId');
        $isEdit = $request->get('isEdit');
        if (!$adminId) {
            return $this->redirectErrorPage('ERR001');
        }
        $administratorRepository = $this->getRepository('Administrator');
        $admin = $administratorRepository->getAdministratorDetail(array('id' => $adminId));
        if (!$admin) {
            return $this->redirectErrorPage('ERR001');
        }
        return $this->render('AdminBundle:Admin:finish.html.twig', compact('admin', 'isEdit'));
    }

    /**
     * セッション会話に保存した認証実体類の実例
     * @param string $actionName 有效值：'save'、'edit'、'new' 
     * @var object
     */
    private function getValidateEntity($actionName)
    {
        $adminValidateEntity = $this->sessionGet($this->sessionKey);
        if (empty($adminValidateEntity)) {
            return NULL;
        }
        switch ($actionName) {
            case 'new':
                if (!$adminValidateEntity->id) {
                    $this->sessionRemove($this->sessionKey);
                } else {
                    $adminValidateEntity = NULL;
                }
                break;
            case 'edit':
                if ($adminValidateEntity->id) {
                    $this->sessionRemove($this->sessionKey);
                } else {
                    $adminValidateEntity = NULL;
                }
                break;
            case 'save':
                $this->sessionRemove($this->sessionKey);
                break;
        }

        return $adminValidateEntity;
    }

}
