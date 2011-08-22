<?php
class Application_Model_UserMapper extends Application_Model_Mapper
{
    protected function _getCryptedPassword($password)
    {
        // TODO : implémenter un vrai cryptage
        return str_rot13($password);
    }

    protected function _getSaveArrayFromItem(Application_Model $item)
    {
        $data = $item->toArray();

        // Crypte le mot de passe à la volée
        $data['password'] = $this->_getCryptedPassword($data['password']);

        return $data;
    }

    protected function _getUncryptedPassword($password)
    {
        // TODO : implémenter un vrai décryptage
        return str_rot13($password);
    }

    public function getPaginator($currentPageNumber)
    {
        $callback = new Zend_Filter_Callback(
            array($this, 'mapRowsetToItems')
        );

        $paginator = Zend_Paginator::factory($this->getDbTable()->select());
        $paginator->setCurrentPageNumber($currentPageNumber)
                  ->setItemCountPerPage(25)
                  ->setFilter($callback);
        return $paginator;
    }

    public function mapRowToItem(Zend_Db_Table_Row_Abstract $row)
    {
        $data = $row->toArray();

        // Décrypte le mot de passe à la volée
        $data['password'] = $this->_getUncryptedPassword($data['password']);

        $item = new Application_Model_User($data);
        return $item;
    }
    
    /**
     * Recherche selon des critères prédéfinis
     * @author Florent Paterno <florent.paterno@alterway.fr>
     * @param array $criteria Critère de recherche (couples colonne/valeur)
     * @return Zend_Db_Table_Rowset Les résultats de la requête
     */
    public function findByData(array $criteria)
    {
        $table = $this->getDbTable(); // Define table
        $select = $table->select(); // Define select
        
        // Browse each criteria to create SQL request
        foreach($criteria as $column => $value){
            $select->where($column . ' = ?', $value);
        }
        
        // Execute request
        $rows = $table->fetchAll($select);
        if (0 == count($rows)) {
            return;
        }
        return $rows;
    }
}
