<?php
class IndexController extends Application_Controller_Action
{

    /**
     * Index du site (mode connecté)
     * @author Florent Paterno <florent.paterno@alterway.fr>
     */
    public function indexAction()
    {
        // Définition des données sur l'utilisateur
        $stationData = array('codeImpl' => '63002',
                             'enseigne' => 'Total',
                             'reseau' => 'Mandataire',
                             'entite' => 'Maillage',
                             'icpe' => 'Déclaration',
                             'erp' => '5ème');

        // Définition des variables utilisées dans la vue
        $this->view->title = 'Ged Stations-service';
        $this->view->stationData = $stationData;
    }

    /**
     * Gestion de la page de connexion
     * @author Florent Paterno <florent.paterno@alterway.fr>
     */
    public function connexionAction(){

        // Definition de la requête
        $request = $this->getRequest();

        // Création du formulaire de connexion
        $form = new Application_Form_Login();

        // Le formulaire a t-il été soumis et est-il valide ?
        if ($request->isPost()){
            $post = $request->getPost(); // Récupération du POST

            // Formulaire valide : on vérifie les logins/mdp
            if ($form->isValid($post)){

                // Récupération des résultats associés aux champs du formulaire
                $userMap = new Application_Model_UserMapper();
                $search = array('implantationId' => $post['implantationId'],
                                'password' => $post['password']);
                $users = $userMap->findByData($search);

                // Les identifiants sont-ils correct ?
                if (!is_null($users)){

                    // Récupération du premier élément et insertion en session
                    $users = $userMap->mapRowsetToItems($users);
                    $user = $users[0];

                    // Insertion des données en session
                    $uSession = new Zend_Session_Namespace('User');
                    $uSession->user = $user;

                    // Redirection vers la page précédent l'authentification
                    $this->_helper->redirector->gotoUrlAndExit($uSession->referer);
                }
            }
            $form->populate($post); // Remplit le formulaire lors d'erreurs
        }

        // Définition des variables utilisées dans la vue
        $this->view->title = 'Identifiez-vous';
        $this->view->form = $form;

        // Aucune template utilisée pour le rendu
        $this->_helper->viewRenderer->setNoRender(TRUE);
        echo $form;
    }

    /**
     * Gestion de deconnexion
     * @author Florent Paterno <florent.paterno@alterway.fr>
     */
    public function deconnexionAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        $uSession = new Zend_Session_Namespace('User');
        $uSession->unsetAll();
        $this->_helper->redirector->gotoUrlAndExit('/');
    }

    /**
     * Signalement d'une anomalie
     * @author Florent Paterno <florent.paterno@alterway.fr>
     */
    public function anomalieAction(){

        // Definition de la requête
        $request = $this->getRequest();

        // Création du formulaire de signalement d'une anomalie
        $form = new Application_Form_Anomaly();

        // Validation du formulaire en Ajax si nécessaire
        if ($request->isXmlHttpRequest()) {
            $json = $form->processAjax($request->getPost());
            $this->_helper->json($json);
            return;
        }

        // Définition des variables utilisées dans la vue
        $this->view->title = 'Signaler une anomalie'; // Titre de la section
        $this->view->isValid = FALSE; // Le formulaire est-il valide ?

        // Définition du referer (utilisé pour rediriger l'utilisateur)
        $referer = $request->getServer('HTTP_REFERER', '/');
        $form->getElement('referer')->setValue($referer);

        // Le formulaire a t-il été soumis et est-il valide ?
        if ($request->isPost() && $form->isValid($request->getPost())) {
            // TODO : Faire le traitement des champs de l'anomalie signalée
            $this->view->isValid = TRUE;
        }
        $this->view->form = $form;
    }

    /**
     * Génére le PDF du classeur
     * @author Florent Paterno <florent.paterno@alterway.fr>
     */
    public function pdfAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        echo 'Génération du PDF';
    }
}
