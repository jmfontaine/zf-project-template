<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPrefix('Kornak_Controller_Action_Helper');
    }

    /**
     * Charge la configuration dans le registre pour la rendre disponible
     * dans toute l'application
     * @author Jean-Marc Fontaine <jean-marc.fontaine@alterway.fr>
     */
    protected function _initConfig()
    {
        Zend_Registry::set('config', new Zend_Config($this->getOptions()));
    }

    protected function _initLocale()
    {
        $locale  = new Zend_Locale('fr_FR');

        $cache = $this->bootstrap('cachemanager')
                      ->getResource('cachemanager')
                      ->getCache('locale');
        $locale->setCache($cache);

        Zend_Registry::set('Zend_Locale', $locale);
    }

    protected function _initPaginator()
    {
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(
          'common/pagination.phtml'
        );
    }

    /**
     * Gestion des routes
     * @author Florent Paterno <florent.paterno@alterway.fr>
     */
    protected function  _initRouter() {

        // Chargement des routes
        $conf = new Zend_Config_Ini(APPLICATION_PATH.'/configs/routes.ini', 'routes');

        // Initialisation du contrôleur frontal
        $ctrl = Zend_Controller_Front::getInstance();

        // Configuration du router
        $router = $ctrl->getRouter();
        $router->addConfig($conf, 'routes');

        return $router;
     }

     /**
      * Initialisation de ZFDebug
      * @author Jean-Marc Fontaine <jean-marc.fontaine@alterway.fr>
      */
     protected function _initZfdebug()
     {
        $config = Zend_Registry::get('config');
        if ($config->zfdebug->active) {
            // Définition des extensions à charger
            $options = array(
                'jquery_path' => '/js/jquery-1.4.4.min.js',
                'plugins' => array(
                    'Exception',
                    'File' => array('base_path' => APPLICATION_PATH . '/..'),
                    'Html',
                    'Memory',
                    'Registry',
                    'Time',
                	'Variables',
                )
            );

            // Configure l'extension "Database"
            if ($this->hasPluginResource('db')) {
                $this->bootstrap('db');
                $db = $this->getPluginResource('db')->getDbAdapter();
                $options['plugins']['Database']['adapter'] = $db;
            }

            // Configure l'extension "Cache"
            if ($this->hasPluginResource('cache')) {
                $this->bootstrap('cache');
                $cache = $this-getPluginResource('cache')->getDbAdapter();
                $options['plugins']['Cache']['backend'] = $cache->getBackend();
            }

            // Enregistrement de ZFDebug
            $debug = new ZFDebug_Controller_Plugin_Debug($options);
            $this->bootstrap('frontController');
            $frontController = $this->getResource('frontController');
            $frontController->registerPlugin($debug);

            // Ajout de l'extension tierce "Auth" qui ne peut être définie
            // dans le tableau ci-dessus
            $debug->registerPlugin(new ZFDebug_Controller_Plugin_Debug_Plugin_Auth());

            return $debug;
         }
     }
}
