<?php
namespace Application\Model;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Sql\Expression;

class colorModel extends AbstractActionController
{
    // $select->columns(array('*',new Expression('SUM(allocated_quantity) as sum_allocated_quantity'),new Expression('SUM(quantity) as sum_quantity')));
    public function search($key_word=NULL){
        $map=$this->getServiceLocator()->get('dbHelper');
        $select=$map->getInst()->select('colors');
        if($key_word){
                        trim(mb_strtolower($key_word,'UTF-8'));
                        $select->where->nest
                        ->expression("LOWER(first_name) LIKE ?", '%'.strtolower($key_word).'%')
                        ->or
                        ->expression("LOWER(last_name) LIKE ?", '%'.trim(strtolower($key_word)).'%')
                        ->or
                        ->expression("LOWER(color) LIKE ?", '%'.trim(strtolower($key_word)).'%')
                        ->unnest;
                }
        $select->order('color ASC');
        $recordSet=$map->getResultSetFromSelect($select);
        return $recordSet;
    }
    
}

?>