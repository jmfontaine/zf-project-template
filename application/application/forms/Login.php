<?php
class Application_Form_Login extends Zend_Form
{
    /**
     * Formulaire de connexion
     * @author Florent Paterno <florent.paterno@alterway.fr>
     */
    public function init()
    {
        // Nom du formulaire
        $this->setName('login');
        
        // Code implantation - Requis
        $codeImpl = new Zend_Form_Element_Text('implantationId');
        $codeImpl->setLabel('Code implantation')
                  ->setRequired(true)
                  ->addValidator('NotEmpty');
        $this->addElement($codeImpl);
        
        // Mot de passe - Requis
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Mot de passe')
                 ->setRequired(true)
                 ->addValidator('NotEmpty');
        $this->addElement($password);
        
        // Bouton de connexion
        $submit = new Zend_Form_Element_Submit('login');
        $submit->setLabel("Se connecter");
        $this->addElement($submit);
    }
}
