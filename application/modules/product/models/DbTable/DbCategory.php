<?php

class Product_Model_DbTable_DbCategory extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_category";
	
	public function getUserId(){
		return Application_Model_DbTable_DbGlobal::GlobalgetUserId();
	}
	public function add($data){
		$db = $this->getAdapter();
		$arr = array(
				'name'			=>	$data["cat_name"],
				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'remark'		=>	$data["remark"],
		);
		$this->_name = "tb_category";
		$this->insert($arr);
	}
	public function edit($data){
		$db = $this->getAdapter();
		$arr = array(
				'name'			=>	$data["cat_name"],
				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'remark'		=>	$data["remark"],
		);
		$this->_name = "tb_category";
		$where = $db->quoteInto("id=?", $data["id"]);
		$this->update($arr, $where);
	}
	
	//Insert Popup=============================
	public function addNew($data){
		$db = $this->getAdapter();
		$arr = array(
				'name'			=>	$data["cat_name"],
				'parent_id'		=>	$data["parent"],
				'date'			=>	new Zend_Date(),
				'status'		=>	$data["status"],
				'remark'		=>	$data["remark"],
		);
		$this->_name = "tb_category";
		return $this->insert($arr);
	}
	
	public function getAllCategory($data){
		$db = $this->getAdapter();
		$sql = "SELECT 
						c.id ,
						c.`name`,
						c.`parent_id`, 
						(SELECT v.`name_en` FROM tb_view AS v WHERE v.`type`=5  AND C.`status`=v.`key_code` LIMIT 1) AS status ,
						c.`remark`
					FROM 
					    `tb_category` AS c 
					WHERE 
					     c.`status` =1";
		$where = '';
			//if($data["name"]!=""){
				//$s_where=array();
				//$s_search = addslashes(trim($data['name']));
				//$s_where[]= " c.`name` LIKE '%{$s_search}%'";
				//$s_where[]=" c.`parent_id` LIKE '%{$s_search}%'";
			//	$where.=' AND ('.implode(' OR ', $s_where).')';
			//}
			if($data["parent"]!=""){
				$where.=" AND c.`id` ='".$data["parent"]."'";
			}
			if($data["status"]!=""){
				$where.=' AND c.`status` ='.$data["status"];
			}	
		return $db->fetchAll($sql.$where);
	}
	
	public function getCategory($id){
		$db = $this->getAdapter();
		$sql = "SELECT c.id,c.`name`,c.`parent_id`,c.`remark`,c.`status` FROM `tb_category` AS c WHERE c.`id`= $id";
		return $db->fetchRow($sql);
	}
}