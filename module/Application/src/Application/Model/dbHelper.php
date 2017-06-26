<?php
namespace Application\Model;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\ResultSet\ResultSet;

class dbHelper  extends AbstractActionController
{
    protected  $sql = null;
    
    private function sqlInst(){
    	if($this->sql==null){
    		$db=$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    		$this->sql=new \Zend\Db\Sql\Sql($db);
    	}
    }
    public function getInst(){
    	$this->sqlInst();
    	return $this->sql;
    }
    public function getResultSetFromSelect($select){
    	$result = $this->getInst()->prepareStatementForSqlObject($select)->execute();
    	$resultSet = new ResultSet();
    	$resultSet->initialize($result)->buffer();
    	return $resultSet;
    }
   public function getRowFromSelect($select)
    {
    	$resArray=array();
    	try{
    		$inst=$this->getInst();
    		
    		$result = $inst->prepareStatementForSqlObject($select)->execute();
    		$resultSet = new ResultSet();
    		$resultSet->initialize($result);
    		if($resultSet->count()) {
    			$resArray=$rowData = $resultSet->current()->getArrayCopy();
    		}
    
    	} catch (\Zend\Db\Adapter\Exception\InvalidQueryException $e) {
    		$message = $e->getPrevious() ? $e->getPrevious()->getMessage() : $e->getMessage();
    		throw new \Exception("<p>SQL error "   . $message . "<br/>");
    
    	}catch (\Exception $e) {
    		throw new \Exception("<p>General Error: " . $e->getMessage() . "<br/>");
    	}
    
    	return $resArray;
    }
    public function rsToList($recordSet,$keyCol,$valCol)
    {
    	$resultArray = array();
    	if ($recordSet instanceof \Zend\Db\ResultSet\ResultSet) {
    		foreach($recordSet->toArray() as $key=>$value)
    			$resultArray[$value[$keyCol]] = $value[$valCol];
    	}
    	return $resultArray;
    }
    public function safeUpdate($execSql) //need safeInsert and safeUodate - different exceptions and no result check
    {
    	 
    	if ($execSql instanceof \Zend\Db\Sql\Update) {
    		try{
    			$result=$this->getSqlInstance()->prepareStatementForSqlObject($execSql)->execute()->getAffectedRows();
    		} catch (\Zend\Db\Adapter\Exception\InvalidQueryException $e) {
    			$message = $e->getPrevious() ? $e->getPrevious()->getMessage() : $e->getMessage();
    			throw new \Exception("SQL error "   . $message );
    		}catch (\Exception $e) {
    			throw new \Exception( "General Error: " . $e->getMessage() );
    		}
    	}
    	return $result;
    }
    public function safeInsert($execSql, $ignoreLastkey = false)
    {
    	if ($execSql instanceof \Zend\Db\Sql\Insert ){
    		try{
    			$lastKey=$this->getSqlInstance()->prepareStatementForSqlObject($execSql)->execute()->getGeneratedValue();
    		} catch (\Zend\Db\Adapter\Exception\InvalidQueryException $e) {
    			$message = $e->getPrevious() ? $e->getPrevious()->getMessage() : $e->getMessage();
    			throw new \Exception("<p>SQL error "   . $message . "<br/>");
    		}catch (\Exception $e) {
    			throw new \Exception( "<p>General Error: " . $e->getMessage() . "<br/>");
    		}
    	}
    	if (empty($lastKey) && ! $ignoreLastkey){throw new \Exception( '<h1>Any Error</h1><pre>'.$execSql->getSqlString($this->getDbAdapter()->getPlatform())."</pre>");} //log the error and redirect to error page and display predefined
    	return $lastKey;
    }
}

