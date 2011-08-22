<?php
class Admin_UsersController extends Application_Controller_Action
{
    public function editAction()
    {
        // Exemple d'ajout d'un utilisateur
        $userData = array(
            'email'            => 'jean-marc.fontaine@alterway.fr',
            'firstName'        => 'Jean-Marc',
            'implantationId'   => 'NF1664',
            'lastName'         => 'Fontaine',
            'password'         => 'passwordde12',
            'reportCategoryId' => 1,
            'role'             => Application_Model_User::GERANT,
        );
        $user = new Application_Model_User($userData);

        $userMapper = new Application_Model_UserMapper();
        $userMapper->save($user);

        var_dump($user);
    }

    public function indexAction()
    {
        // Exemple d'affichage d'une liste paginée d'utilisateurs
        $userMapper = new Application_Model_UserMapper();

        $currentPageNumber = $this->_getParam('page', 1);
        $paginator  = $userMapper->getPaginator($currentPageNumber);
        $this->view->paginator = $paginator;
    }

    public function displayAction()
    {
        // Exemple d'affichage d'un utilisateur
        $userId = $this->_getParam('userId');

        $userMapper = new Application_Model_UserMapper();
        $user = $userMapper->find($userId);

        var_dump($user);
    }
}
