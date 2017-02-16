<?php

class Product_Model_DbTable_DbBrand extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_brand";
	
	public function getUserId(){
		return Application_Model_DbTable_DbGlobal::GlobalgetUserId();
	}
	public function add($data){
		$db = $this->getAdapter();
		$arr = array(
				'name'			=>	$data["brand_name"],
				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'remark'		=>	$data["remark"],
		);
		$this->_name = "tb_brand";
		$this->insert($arr);
	}
	public function edit($data){
		$db = $this->getAdapter();
		$arr = array(
				'name'			=>	$data["brand_name"],
				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'remark'		=>	$data["remark"],
		);
		$this->_name = "tb_brand";
		$where = $db->quoteInto("id=?", $data["id"]);
		$this->update($arr, $where);
	}
	
	//Popup Insert=======================================\
	public function addNew($data){
		$db = $this->getAdapter();
		$arr = array(
				'name'			=>	$data["brand_name"],
				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'remark'		=>	$data["remark"],
		);
		$this->_name = "tb_brand";
		return $this->insert($arr);
	}
	
	public function getAllBrands($data){
		$db = $this->getAdapter();
		$sql = "SELECT c.id,c.`name`,
				(SELECT name_en FROM tb_view WHERE tb_view.key_code=c.status AND tb_view.type=5) AS `status`,c.`remark` 
				FROM `tb_brand` AS c";
		$where = ' WHERE 1';
		if($data["name"]!=""){
		    $where.=" AND c.id=".$data['name'];
			/*$s_where=array();
			$s_search = addslashes(trim($data['name']));
			$s_where[]= " c.`name` LIKE '%{$s_search}%'";
			$s_where[]=" c.`remark` LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ', $s_where).')';*/
		}
		if($data['status']>-1){
			$where.=" AND c.status=".$data['status'];
		}
// 		echo $sql.$where;
		$order=" ORDER BY c.id DESC ";
		return $db->fetchAll($sql.$where.$order);
	}
	public function getAllBrand(){
		$db = $this->getAdapter();
		$sql = "SELECT c.id,c.`name`,c.`parent_id`,c.`remark`,c.`status` FROM `tb_brand` AS c WHERE c.`status` =1";
		return $db->fetchAll($sql);
	}
	
	public function getBrand($id){
		$db = $this->getAdapter();
		$sql = "SELECT c.id,c.`name`,c.`parent_id`,c.`remark`,c.`status` FROM `tb_brand` AS c WHERE c.`id`= $id";
		return $db->fetchRow($sql);
	}
}