<?php

class Product_Model_DbTable_DbMeasure extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_measure";
	
	public function getUserId(){
		return Application_Model_DbTable_DbGlobal::GlobalgetUserId();
	}
	public function add($data){
		$db = $this->getAdapter();
		$arr = array(
				'name'			=>	$data["measure_name"],
// 				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'remark'		=>	$data["remark"],
		);
		$this->_name = "tb_measure";
		$this->insert($arr);
	}
	public function edit($data){
		$db = $this->getAdapter();
		$arr = array(
				'name'			=>	$data["measure_name"],
// 				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'remark'		=>	$data["remark"],
		);
		$this->_name = "tb_measure";
		$where = $db->quoteInto("id=?", $data["id"]);
		$this->update($arr, $where);
	}
	///insert type job =============================================================================
	public function addJob($data){
		$db = $this->getAdapter();
		$arr = array(
				'title'			=>	$data["measure_name"],
				// 				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'description'	=>	$data["remark"],
		);
		$this->_name = "tb_jobtype";
		$this->insert($arr);
	}
	
	public function editJob($data){
		$db = $this->getAdapter();
		$arr = array(
				'title'			=>	$data["measure_name"],
				// 				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'description'	=>	$data["remark"],
		);
		$this->_name = "tb_jobtype";
		$where="id=".$data['id'];
		$this->update($arr, $where);
	}
	
	function getAllJob(){
		$db=$this->getAdapter();
		$sql="SELECT id,title,description,status FROM tb_jobtype 
      			 WHERE `status`=1
      			 AND    title !=''  ";
		return  $db->fetchAll($sql);
	}
	public function getJobbyId($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM `tb_jobtype` AS m  WHERE m.`id`= $id";
		return $db->fetchRow($sql);
	}
	
	//Insert Popup=============================================================================
	public function addNew($data){
		$db = $this->getAdapter();
		$arr = array(
				'name'			=>	$data["measure_name"],
// 				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'remark'		=>	$data["remark"],
		);
		$this->_name = "tb_measure";
		return $this->insert($arr);
	}
	public function getAllMeasure(){
		$db = $this->getAdapter();
		$sql = "SELECT m.id,m.`name`,m.`status`,m.`remark` FROM `tb_measure` AS m ";
		return $db->fetchAll($sql);
	}
	public function getAllMeasures($data){
	    //print_r($data);exit();
	    $db = $this->getAdapter();
	    $sql = "SELECT m.id,m.`name`,m.`status`,m.`remark` FROM `tb_measure` AS m ";
	    $where='';
	    if($data["parent"]!=""){
	        $where.="where m.`id`=".$data['parent'];
	    }
	    return $db->fetchAll($sql.$where);
	
	}
	public function getMeasure($id){
		$db = $this->getAdapter();
		$sql = "SELECT m.id,m.`name`,m.`status`,m.`remark` FROM `tb_measure` AS m  WHERE m.`id`= $id";
		return $db->fetchRow($sql);
	}
	
	
	
}