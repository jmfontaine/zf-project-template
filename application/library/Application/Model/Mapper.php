<?php
abstract class Application_Model_Mapper
{
    protected $_dbTable;

    protected function _getSaveArrayFromItem(Application_Model $item)
    {
        return $item->toArray();
    }

    protected function _getDbTableName()
    {
        $class = get_class($this);
        $parts = explode('_', $class);
        $part  = array_pop($parts);
        $part  = substr($part, 0, -6);

        return "Application_Model_DbTable_$part";
    }

    protected function _getItemClassName()
    {
        $class = get_class($this);
        $parts = explode('_', $class);
        $part  = array_pop($parts);
        $part  = substr($part, 0, -6);

        return "Application_Model_$part";
    }

    public function fetchAll()
    {
        $rowset = $this->getDbTable()->fetchAll();
        return $this->_createItemsFromRowset($rowset);
    }

    public function find($id)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        return $this->mapRowToItem($result->current());
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $dbTableName = $this->_getDbTableName();
            $this->setDbTable($dbTableName);
        }

        return $this->_dbTable;
    }

    public function mapRowToItem(Zend_Db_Table_Row_Abstract $row)
    {
        $data      = $row->toArray();
        $className = $this->_getItemClassName();

        return new $className($data);
    }

    public function mapRowsetToItems(Zend_Db_Table_Rowset $rowset)
    {
        $items = array();
        foreach ($rowset as $row) {
            $items[] = $this->mapRowToItem($row);
        }
        return $items;
    }

    public function save(Application_Model $item)
    {
        $data = $this->_getSaveArrayFromItem($item);

        if (null === ($id = $item->getId())) {
            $id = $this->getDbTable()->insert($data);
            $item->setId($id);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }

        return $this;
    }

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Application_Exception(
            	'Passerelle vers la base de données invalide'
            );
        }
        $this->_dbTable = $dbTable;

        return $this;
    }
}