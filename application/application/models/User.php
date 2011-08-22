<?php
class Application_Model_User extends Application_Model
{
    /**
     * @var string Identifiant des rôles
     */
    const GERANT        = 1;
    const MANAGER       = 2;
    const ADMINISTRATOR = 3;
    const ANONYMOUS  = 0;
        
    /**
     * @var string Nom des rôles
     */
    const STR_GERANT        = 'gerant';
    const STR_MANAGER       = 'manager';
    const STR_ADMINISTRATOR = 'admin';
    const STR_ANONYMOUS = 'anonymous';
    
    protected $_id;
    protected $_implantationId;
    protected $_reportCategoryId;
    protected $_email;
    protected $_firstName;
    protected $_lastName;
    protected $_password;
    protected $_role;
    
    public function getEmail()
    {
        return $this->_email;
    }

    static public function getRoleName($role)
    {
      switch($role){
        case self::GERANT:
          return self::STR_GERANT;
        case self::MANAGER:
          return self::STR_MANAGER;
        case self::ADMINISTRATOR:
          return self::STR_ADMINISTRATOR;
      }
      return self::STR_ANONYMOUS;
    }
    
    public function getFirstName()
    {
        return $this->_firstName;
    }

    public function getFullName($lastNameFirst = false)
    {
        if ($lastNameFirst) {
            $result = $this->_lastName . ' ' . $this->_firstName;
        } else {
            $result = $this->_firstName . ' ' . $this->_lastName;
        }

        return $result;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getImplantationId()
    {
        return $this->_implantationId;
    }

    public function getLastName()
    {
        return $this->_lastName.'_recup par methode';
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function getReportCategoryId()
    {
        return $this->_reportCategoryId;
    }

    public function getRole()
    {
        return $this->_role;
    }

    public function isAdministrator()
    {
        return self::ADMINISTRATOR == $this->_role;
    }

    public function isGerant()
    {
        return self::GERANT == $this->_role;
    }

    public function isManager()
    {
        return self::MANAGER == $this->_role;
    }

    public function setEmail($email)
    {
        $validator = new Zend_Validate_EmailAddress();
        if (!$validator->isValid($email)) {
            throw new Application_Exception(
            	"Email '$email' invalide"
            );
        }

        $this->_email = (string) $email;
        return $this;
    }

    public function setFirstName($firstName)
    {
        $this->_firstName = (string) $firstName;
        return $this;
    }

    public function setId($id)
    {
        if (null !== $this->_id) {
            throw new Application_Exception(
            	"Impossible de redéfinir l'id de l'utilisateur"
            );
        }

        $this->_id = (int) $id;
        return $this;
    }

    public function setImplantationId($id)
    {
        $this->_implantationId = (string) $id;
        return $this;
    }

    public function setLastName($lastName)
    {
        $this->_lastName = (string) $lastName;
        return $this;
    }

    public function setPassword($password)
    {
        $validator = new Zend_Validate_StringLength(array('min' => 12));
        if (!$validator->isValid($password)) {
            throw new Application_Exception(
            	"Le mot de passe doit faire au minimum 12 caractères"
            );
        }

        $this->_password = (string) $password;
        return $this;
    }

    public function setReportCategoryId($id)
    {
        $this->_reportCategoryId = (int) $id;
        return $this;
    }

    public function setRole($role)
    {
        switch ($role) {
            case self::GERANT:
            case self::MANAGER:
            case self::ADMINISTRATOR:
                // Ne rien faire;
                break;

            default:
                throw new Application_Exception("Rôle '$role' invalide");
        }

        $this->_role = (int) $role;
        return $this;
    }
}
