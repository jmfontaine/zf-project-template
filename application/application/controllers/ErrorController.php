<?php
class ErrorController extends Application_Controller_Action
{
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        if (!$errors) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // Erreur 404
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page non trouvée';
                break;
            default:
                // Erreur 500
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Erreur applicative';
                break;
        }

        $this->getLog()->crit($this->view->message, $errors->exception);

        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request = $errors->request;
    }
}
