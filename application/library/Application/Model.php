<?php
abstract class Application_Model
{
    public function __construct(array $data = array())
    {
        $this->setData($data);
    }
    /**
      * permet d'accéder au attribut protected et accesseur avec la notation attribut
      * $obj->attribut
      * si un accesseur existe retourne $obj->getAttribut()
      * sinon retour $obj->attribut
      * @author Stéphane Planquart
      */
    public function __get($name){
      $method='get'.ucfirst($name);
      $name='_'.$name;
      if(method_exists($this,$method)){
        return $this->{$method}();
      }
      if(isset($this->{$name})){
        return $this->{$name};
      }
      return null;
    }
    /**
      * permet d'affecter un attribut protected 
      * ou d'utiliser le setteur s'il est défini
      * $obj->attribut='ma valeur'
      * si le setteur existe retourne $obj->setAttribut()
      * sinon fait $obj->attribut='ma valeur'
      * @author Stéphane Planquart
      */
    public function __set($name,$value){
      $method='set'.ucfirst($name);
      $attr='_'.$name;
      if(method_exists($this,$method)){
        return $this->{$method}($value);
      }
      if(isset($this->{$attr})){
        return ($this->{$attr}=$value);
      }
      throw new Zend_Exception("Attribute {$name} or method {$method} does not exist");
    }
    /**
      * méthode magic permettant d'accéder au attribut 
      * avec un setteur (setMonAttribut) qui retourne l'objet this
      * @author Stéphane Planquart
      */
    public function __call($method,$args){
      /*
       * il n'est pas util de vérifié si la méthode existe 
       * car php appel call uniquement si la fonction n'existe pas 
       */
      /*if(method_exists($this,$method)){
        return call_user_func_array($method,$args);
      }*/
      if(substr($method,0,3)=='set' && count($args)==1){
        //c'est une affectation
        $attr=substr($method,3);
        $this->{$attr}=$args[0];
        return this;  //permet l'utilisation des interfaces fluides.
      }
    }
    public function setData (array $data)
    {
        foreach ($data as $name => $value) {
            $methodName = 'set' . ucfirst($name);
            if (!method_exists($this, $methodName)) {
                throw new Application_Exception("Propriété '$name' invalide");
            }

            $this->$methodName($value);
        }

        return $this;
    }

    public function toArray()
    {
        $classReflection = new ReflectionClass(get_class($this));
        $properties      = $classReflection->getProperties(
            ReflectionProperty::IS_PROTECTED
        );

        $data = array();
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $key          = substr($propertyName, 1);
            $data[$key]   = $this->$propertyName;
        }

        return $data;
    }
}