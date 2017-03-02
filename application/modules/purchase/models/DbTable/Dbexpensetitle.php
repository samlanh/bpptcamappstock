<?php

class Purchase_Model_DbTable_Dbexpensetitle extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_expensetitle";
	
	public function getUserId(){
		return Application_Model_DbTable_DbGlobal::GlobalgetUserId();
	}
	public function add($data){
		$db = $this->getAdapter();
		$arr = array(
		        'parent_id_title'	=>	$data["parent_id_title"],
				'title'	=>	$data["title"],
				'title_en'	=>	$data["name_en"],
				'status'		=>	$data["status"],
				'date'		=>	date("Y-m-d"),
				'user_id'=>$this->getUserId()
		);
		$this->insert($arr);
	}
	public function edit($data){
		$db = $this->getAdapter();
		$arr = array(
		    'parent_id_title'	=>	$data["parent_id_title"],
				'title'	=>	$data["title"],
				'title_en'	=>	$data["name_en"],
				'status'		=>	$data["status"],
				'date'		=>	date("Y-m-d"),
				'user_id'=>$this->getUserId()
		);
		//$this->_name = "tb_category";
		$where = $db->quoteInto("id=?", $data["id"]);
		$this->update($arr, $where);
	}
	public function addajaxtitle($data){
		$db = $this->getAdapter();
		$arr = array(
		        'parent_id_title'	=>0,
				'title'	=>	$data["expense_title"],
				'status'		=>	1,
				'date'		=>	date("Y-m-d"),
				'user_id'=>$this->getUserId()
		);
		return $this->insert($arr);
	}
	public function addajaxtitleincome($data){
	    $db = $this->getAdapter();
	    $arr = array(
	        'parent_id_title'	=>1,
	        'title'	=>	$data["income_title"],
	        'status'		=>	1,
	        'date'		=>	date("Y-m-d"),
	        'user_id'=>$this->getUserId()
	    );
	    
	    return $this->insert($arr);
	}
	public function getAllTerm(){
		$db = $this->getAdapter();
		$sql = "SELECT
				  t.id,
		          (SELECT name_en FROM `tb_view` WHERE key_code=t.`parent_id_title` AND TYPE=15) AS parent_id_title,
				  t.title,
				   t.title_en,
				  t.status	  
				FROM
				  tb_expensetitle AS t ORDER BY id desc ";
		return $db->fetchAll($sql);
	}
	public function getAllTerms($search=null){
	    $db = $this->getAdapter();
	    $sql = "SELECT t.id,
		          (SELECT name_en FROM `tb_view` WHERE key_code=t.`parent_id_title` AND TYPE=15) AS parent_id_title,
				  t.title,t.title_en,t.status
				FROM
				  tb_expensetitle AS t";
	    $where=" ";
// 	    if (!empty($search['txt_search'])){
// 	        $s_where = array();
// 	        $s_search = trim(addslashes($search['txt_search']));
// 	        $s_where[] = " t.parent_id_title LIKE '%{$s_search}%'";
// 	        $s_where[] = " t.title LIKE '%{$s_search}%'";
// 	        $s_where[] = " t.title_en LIKE '%{$s_search}%'";
// 	        $where .=' AND ('.implode(' OR ',$s_where).')';
// 	    }
// 	    if($search["status"]>0){
// 	        $where.=' AND status='.$search["status"];
// 	    }
// 	    $dbg = new Application_Model_DbTable_DbGlobal();
// 	    $where.=$dbg->getAccessPermission();
 	   $order="ORDER BY id desc";
	    return $db->fetchAll($sql.$where.$order);
	}
	public function getTermById($id){
		$db = $this->getAdapter();
		$sql = "SELECT t.* FROM tb_expensetitle AS t WHERE t.id= $id";
		return $db->fetchRow($sql);
	}
	public function getTermcondictionType(){
		$db = $this->getAdapter();
		$sql = "SELECT v.`key_code`,v.`name_en` FROM tb_view AS v WHERE v.`type`=10";
		return $db->fetchAll($sql);
	}
}