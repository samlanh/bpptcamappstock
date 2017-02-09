<?php

class Product_Model_DbTable_DbBlock extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_block";
	
	public function getUserId(){
		return Application_Model_DbTable_DbGlobal::GlobalgetUserId();
	}
	public function add($data){
		$db	= $this->getAdapter();
		$ids=explode(',',$data['identity']);
		if(!empty($data['identity'])){
		 foreach ($ids as $i){
			  $data_item= array(
				'code'	=> 	$data['code_'.$i],
				'block_name'	=>	$data['block_name_'.$i] ,
				'contact_name'	=>	$data['contact_name_'.$i],
				'phone'	=> 	$data['ph_number_'.$i],
				'branch_id'	=>	$data['branch_name_'.$i] ,
				'remark'	=> 	$data['rmark_'.$i],
				'status'	=> 	$data['status_'.$i],
			  );
			  $this->_name='tb_block';
			  $this->insert($data_item);
			}	
		}
	}
	public function edit($data){
		$db = $this->getAdapter();
		$arr = array(
				'code'	=> 	$data['code'],
				'block_name'	=>	$data['block_name'] ,
				'contact_name'	=>	$data['contact_name'],
				'phone'	=> 	$data['ph_number'],
				'branch_id'	=>	$data['branch_name'] ,
				'remark'	=> 	$data['rmark'],
				'status'	=> 	$data['status'],
		);
		$this->_name = "tb_block";
		$where = $db->quoteInto("id=?", $data["id"]);
		$this->update($arr, $where);
	
	}
	
	public function getAllBranch($data){
		$db = $this->getAdapter();
		$sql = "SELECT 
				  s.`id`,
				  s.`code`,
				  s.`block_name`,
				  s.contact_name,
				  s.`remark`,
				  s.`phone`,
				  ss.`name`,
				  s.`status` 
				FROM
				  `tb_block` AS s , tb_sublocation AS ss
				   WHERE 
					s.branch_id = ss.id ";
		$where='';
		if($data["branch_name"]!=""){
			$s_where=array();
			$s_search = addslashes(trim($data['branch_name']));
			$s_where[]= " s.`code` LIKE '%{$s_search}%'";
			$s_where[]=" s.`block_name` LIKE '%{$s_search}%'";
			$s_where[]=" s.contact_name LIKE '%{$s_search}%'";
			$s_where[]=" ss.`name` LIKE '%{$s_search}%'";
			$s_where[]=" s.`phone` LIKE '%{$s_search}%'";
			//$s_where[]= " cate LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ', $s_where).')';
		}
		if($data["status"]!=""){
			$where.=' AND s.status='.$data["status"];
		}
		//echo $sql;
		return $db->fetchAll($sql.$where);
	}
	
	public function getBranchById($id){
		$db = $this->getAdapter();
		$sql = "SELECT 
				  s.`id`,
				  s.`code`,
				  s.`block_name`,
				  s.contact_name,
				  s.`remark`,
				  s.`phone`,
				  s.`branch_id`,
				  s.`status` 
				FROM
				  `tb_block` AS s 
				WHERE s.id = $id";
		return $db->fetchRow($sql);
	}
	public function getBranchtitle(){
		$db = $this->getAdapter();
		$sql = "SELECT 
				  s.`id`,
				  s.`branch_code` AS code,
				  s.`name`
				FROM
				  `tb_sublocation` AS s" ;
		return $db->fetchAll($sql);
	}
		public function updatestatus($id,$status){
		$db	=	$this	->	getAdapter();
		if($status==1){
			$st = 0;
		}else{
			$st = 1;
		}
		$arr=array(
				"status"	 => 	$st ,
			);
			$this	->	_name="tb_block";
			$where	=	$this	->	getAdapter()	->	quoteInto("id=?", $id);
			$id 	= $this		->	update($arr, $where);//insert data
	}
	
}