<?php

class Product_Model_DbTable_DbTransfer extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_product_transfer";
	public function setName($name)
	{
		$this->_name=$name;
	}
	protected function GetuserInfo(){
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		return $result;
	}
	function getPrefix(){
		$id = $this->GetuserInfo();
		$db = $this->getAdapter();
		$sql = "SELECT s.prefix FROM `tb_sublocation` AS s WHERE s.`id` =".$id["branch_id"];
		//echo $sql;
		return $db->fetchOne($sql);
	}
	public function getTransferNo(){
		$db =$this->getAdapter();
		$prefix = $this->getPrefix();
		$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
		$acc_no = $db->fetchOne($sql);
		$new_acc_no= (int)$acc_no+1;
		$acc_no= strlen((int)$acc_no+1);
		$pre = "TR";
		for($i = $acc_no;$i<4;$i++){
			$pre.='0';
		}
		return $prefix.$pre.$new_acc_no;
	}
	
	public function getRequestTransferNo(){
		$location = $this->GetuserInfo();
		$db =$this->getAdapter();
		$prefix = $this->getPrefix();
		$sql="SELECT id FROM `tb_request_transfer` AS t WHERE t.`cur_location`=".$location["branch_id"]." ORDER BY id DESC LIMIT 1 ";
		$acc_no = $db->fetchOne($sql);
		$new_acc_no= (int)$acc_no+1;
		$acc_no= strlen((int)$acc_no+1);
		$pre = "RQTR";
		for($i = $acc_no;$i<4;$i++){
			$pre.='0';
		}
		return $prefix.$pre.$new_acc_no;
	}
	function getLocation(){
		$id = $this->GetuserInfo();
		$db = $this->getAdapter();
		$sql = "SELECT s.id,s.`name` FROM `tb_sublocation` AS s WHERE s.`status`=1 AND s.`id` !='".$id["branch_id"]."'";
		//echo $sql;
		return $db->fetchAll($sql);
	}
	public function getview($id){
		$db = $this->getAdapter();
		$sql = "SELECT 
				  p.*,
				  (SELECT s.name FROM `tb_sublocation` AS s WHERE s.id=p.`cur_location`) AS cur_location,
				  (SELECT s.name FROM `tb_sublocation` AS s WHERE s.id=p.`tran_location`) AS tran_location,
  				  (SELECT u.`fullname` FROM `tb_acl_user` AS u WHERE u.`user_id`=p.`user_mod`) AS user_tran ,
				  (SELECT v.`name_en` FROM `tb_view` AS v WHERE v.`key_code`=p.`type` and v.type=20) AS type
				FROM
				  `tb_product_transfer` AS p
				where p.id='".$id."'";
		return $db->fetchrow($sql);		  
	}
	public function getitemview($id){
		$db = $this->getAdapter();
		$sql = "SELECT 
				  t.* ,
				  (SELECT p.`item_name` FROM `tb_product` AS p WHERE p.id=t.`pro_id` LIMIT 1) AS item_name
				FROM
				  `tb_transfer_item` AS t 
				WHERE t.`tran_id`='".$id."'";
		return $db->fetchAll($sql);	
	}
	function getTransfer($data){
		$tran_date = $data["tran_date"];
		$db = $this->getAdapter();
		$sql = "SELECT 
				  p.*,
				  (SELECT s.name FROM `tb_sublocation` AS s WHERE s.id=p.`cur_location`) AS cur_location,
				  (SELECT s.name FROM `tb_sublocation` AS s WHERE s.id=p.`tran_location`) AS tran_location,
  				  (SELECT u.`fullname` FROM `tb_acl_user` AS u WHERE u.`user_id`=p.`user_mod`) AS user_tran
				FROM
				  `tb_product_transfer` AS p 
				WHERE 1 AND p.`date`<='$tran_date'";
		$where = '';
	  	if($data["tran_num"]!=""){
	  		$s_where=array();
	  		$s_search = addslashes(trim($data['tran_num']));
	  		$s_where[]= " p.tran_no LIKE '%{$s_search}%'";
	  		//$s_where[]=" p.user_mod LIKE '%{$s_search}%'";
	  		$s_where[]= " p.date LIKE '%{$s_search}%'";
	  		$s_where[]= " p.remark LIKE '%{$s_search}%'";
	  		//$s_where[]= " cate LIKE '%{$s_search}%'";
	  		$where.=' AND ('.implode(' OR ', $s_where).')';
	  	}
	  	if($data["type"]!=""){
	  		$where.=' AND p.`type`='.$data["type"];
	  	}
	  	if($data["status"]!=""){
	  		$where.=' AND p.status='.$data["status"];
	  	}
	  	if($data["to_loc"]!=""){
	  		$where.=' AND p.tran_location='.$data["to_loc"];
	  	}
  		//echo $sql.$where;
		return $db->fetchAll($sql.$where);
	}
	function getTransferById($id){
		$db = $this->getAdapter();
		$sql="SELECT p.* FROM `tb_product_transfer` AS p WHERE p.id=$id";
		return $db->fetchRow($sql);
	}
	function getTransferDettail($id){
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		$db = $this->getAdapter();
		$loc_id = $result["branch_id"];
		$sql="SELECT 
				  t.* ,
				  (SELECT p.`item_name` FROM `tb_product` AS p WHERE p.id=t.`pro_id` LIMIT 1) AS item_name,
				  (SELECT p.`qty` FROM `tb_prolocation` AS p WHERE p.pro_id=t.`pro_id` AND p.`location_id`=$loc_id LIMIT 1) AS qty_loc
				FROM
				  `tb_transfer_item` AS t 
				WHERE t.`tran_id` = $id";
		return $db->fetchAll($sql);
	}
	
	function getCurrentTransfer($id){
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		$db = $this->getAdapter();
		$loc_id = $result["branch_id"];
		$sql="SELECT 
				  t.`pro_id`,
				  t.`qty`,
				  p.`cur_location`,
				  p.`tran_location`,
				  p.`type`
				FROM
				  `tb_transfer_item` AS t ,
				  `tb_product_transfer` AS p
				WHERE t.`tran_id` = p.`id`AND p.id=$id";
		return $db->fetchAll($sql);
	}
	public function add($data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$user_info = new Application_Model_DbTable_DbGetUserInfo();
			$result = $user_info->getUserInfo();
			$arr = array(
					'tran_no'		=>	$data["tran_num"],
					'cur_location'	=>	$data['from_loc'],
					'tran_location'	=>	$data["to_loc"],
					'date'			=>	$data["tran_date"],
					'date_mod'		=>	new Zend_Date(),
					'remark'		=>	$data["remark"],
					'status'		=>	$data["status"],
					'user_mod'		=>	$result["user_id"],
			);
			$id = $this->insert($arr);
			if(!empty($data['identity'])){
				$identitys = explode(',',$data['identity']);
				foreach($identitys as $i)
				{
					$arr_ti = array(
							'tran_id'		=>	$id,
							'pro_id'		=>	$data["pro_id_".$i],
							'qty'			=>	$data["qty_tran_".$i],
							'remark'		=>	$data["remark_".$i],
					);
				$this->_name="tb_transfer_item";
				$this->insert($arr_ti);
				}
			}
			$db->commit();
		}catch (Exception $e){
			$db->rollBack();
			Application_Model_DbTable_DbUserLog::writeMessageError($e);
			echo $e->getMessage();exit();
		}
	}
	public function edit($data,$id){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$user_info = new Application_Model_DbTable_DbGetUserInfo();
			$result = $user_info->getUserInfo();
				
			$arr = array(
					'tran_no'		=>	$data["tran_num"],
					'cur_location'	=>	$data["from_loc"],
					'tran_location'	=>	$data["to_loc"],
					'date'			=>	$data["tran_date"],
					'date_mod'		=>	new Zend_Date(),
					'remark'		=>	$data["remark"],
					'status'		=>	$data["status"],
					'user_mod'		=>	$result["user_id"],
			);
			$where = $db->quoteInto("id=?",$id);
			$this->update($arr, $where);	
			$db = Zend_Db_Table::getDefaultAdapter();
			$where = $db->quoteInto('tran_id = ?', $id);
			$db->delete('tb_transfer_item', $where);	
			if(!empty($data['identity'])){
				$identitys = explode(',',$data['identity']);
				foreach($identitys as $i)
				{
					$arr_ti = array(
							'tran_id'		=>	$id,
							'pro_id'		=>	$data["pro_id_".$i],
							'qty'			=>	$data["qty_tran_".$i],
							'remark'		=>	$data["remark_".$i],
					);
					$this->_name="tb_transfer_item";
					$this->insert($arr_ti);
				}
			}
			$db->commit();
		}catch (Exception $e){
			$db->rollBack();
			Application_Model_DbTable_DbUserLog::writeMessageError($e);
			echo $e->getMessage();exit();
		}
	}
	function getProductName(){
		$db_globle = new Application_Model_DbTable_DbGlobal();
		
		$db = $this->getAdapter();
		$sql = "SELECT 
				  p.`id`,
				  p.`item_name` ,
				  p.`item_code`,
				  (SELECT b.name FROM `tb_brand` AS b WHERE b.id=p.`brand_id`) AS brand,
				  (SELECT c.name FROM `tb_category` AS c WHERE c.id = p.`cate_id`) AS category,
				  (SELECT v.name_kh FROM `tb_view` AS v WHERE v.id=p.`model_id`) AS model,
				  (SELECT v.name_kh FROM `tb_view` AS v WHERE v.id=p.`color_id`) AS color,
				  (SELECT v.name_kh FROM `tb_view` AS v WHERE v.id=p.`size_id`) AS size
				FROM
				  `tb_product` AS p,
				  `tb_prolocation` AS pl 
				WHERE p.`id` = pl.`pro_id` ";
		$location = $db_globle->getAccessPermission('pl.`location_id`');
		return $db->fetchAll($sql.$location);
	}
	function getProductById($id){
		$db_globle = new Application_Model_DbTable_DbGlobal();
		$db = $this->getAdapter();
		$sql = "SELECT
				  p.`id`,
				  p.`item_name` ,
				  p.`item_code`,
				  (SELECT b.name FROM `tb_brand` AS b WHERE b.id=p.`brand_id`) AS brand,
				  (SELECT c.name FROM `tb_category` AS c WHERE c.id = p.`cate_id`) AS category,
				  (SELECT v.name_kh FROM `tb_view` AS v WHERE v.id=p.`model_id`) AS model,
				  (SELECT v.name_kh FROM `tb_view` AS v WHERE v.id=p.`color_id`) AS color,
				  (SELECT v.name_kh FROM `tb_view` AS v WHERE v.id=p.`size_id`) AS size,
				  pl.`qty`
				FROM
				  `tb_product` AS p,
				  `tb_prolocation` AS pl
				WHERE p.`id` = pl.`pro_id` AND p.`id`=$id ";
		$location = $db_globle->getAccessPermission('pl.`location_id`');
		//echo $sql.$location;
		return $db->fetchRow($sql.$location);
	}
	function getRequestTransfer($data){
		$tran_date = $data["tran_date"];
		$db = $this->getAdapter();
		$sql = "SELECT 
				  rt.`tran_no`,
				  rt.`date`,
				  rt.`date_tran`,
				  (SELECT s.`name` FROM `tb_sublocation` AS s WHERE s.`id`=rt.`tran_location`) AS re_tran ,
				  rt.`remark`,
				  rt.`is_approved`,
				  rt.`approved_by`,
				  (SELECT u.`fullname` FROM  `tb_acl_user` AS u WHERE u.`user_id`=rt.`user_id`) AS `user`
				FROM
				  `tb_request_transfer` AS rt ";
		$where = '';
	  	if($data["tran_num"]!=""){
	  		$s_where=array();
	  		$s_search = addslashes(trim($data['tran_num']));
	  		$s_where[]= " p.tran_no LIKE '%{$s_search}%'";
	  		//$s_where[]=" p.user_mod LIKE '%{$s_search}%'";
	  		$s_where[]= " p.date LIKE '%{$s_search}%'";
	  		$s_where[]= " p.remark LIKE '%{$s_search}%'";
	  		//$s_where[]= " cate LIKE '%{$s_search}%'";
	  		$where.=' AND ('.implode(' OR ', $s_where).')';
	  	}
	  	if($data["type"]!=""){
	  		$where.=' AND p.`type`='.$data["type"];
	  	}
	  	if($data["status"]!=""){
	  		$where.=' AND p.status='.$data["status"];
	  	}
	  	if($data["to_loc"]!=""){
	  		$where.=' AND p.tran_location='.$data["to_loc"];
	  	}
  		//echo $sql.$where;
		return $db->fetchAll($sql.$where);
	}
	public function checktransfer($post,$id){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$user_info = new Application_Model_DbTable_DbGetUserInfo();
			$result = $user_info->getUserInfo();
			if($post['status']==2){
				$tr = $this -> getbranch($id); 
				$row=$this->get_itemtransfer($tr['id']);
				if(!empty($row)){
					foreach($row as $rs){
						$pro = $this -> getproduct($rs['pro_id'],$tr['cur_location']);
							if(!empty($pro)){
								$arr_pro = array(
									'qty'		=>	$pro['qty'] + $rs['qty'],
								);
								$this->_name = "tb_prolocation";
								$where=" id = ".$pro['id'];
								$this->update($arr_pro,$where);
							}else{
								$arr_pro = array(
								'pro_id'			=>	$rs['pro_id'],
								'location_id'		=>	$tr['cur_location'],
								'qty'				=>	$rs["qty"],
								'last_mod_userid'	=>	$result["user_id"],
								'last_mod_date'		=>	new Zend_Date(),
								);
								$this->_name="tb_prolocation";
								$this->insert($arr_pro);	
							}	
							$pro_tran = $this -> getproduct($rs['pro_id'],$tr['tran_location']);
							if(!empty($pro_tran)){
								$arr_pro = array(
									'qty'		=>	$pro_tran['qty'] - $rs['qty'],
								);
								$this->_name = "tb_prolocation";
								$where=" id = ".$pro_tran['id'];
								$this->update($arr_pro,$where);
							}
					}
					$arr = array(
					'status'		=> 1,
					'remark_reject' => $post['remark'],
					);
					$this->_name = "tb_product_transfer";
					$where=" id = ".$id;
					$this->update($arr,$where);
				}
			}else{
				$arr = array(
					'status'		=> 3,
					'remark_reject' => $post['remark'],
				);
				$this->_name = "tb_product_transfer";
				$where=" id = ".$id;
				$this->update($arr,$where);
			}		
			$db->commit();
		}catch (Exception $e){
			$db->rollBack();
			Application_Model_DbTable_DbUserLog::writeMessageError($e);
			echo $e->getMessage();exit();
		}
	}
	public function get_itemtransfer($id){
		$db = $this->getAdapter();
		$sql="	SELECT 
					   p.pro_id ,
					   p.qty
				FROM 
					tb_transfer_item AS p
				WHERE 
					 p.tran_id= '".$id."'";
		return $db->fetchAll($sql);			 
	}
	public function getproduct($pid,$branch_id){
		$db = $this->getAdapter();
		$sql="	SELECT * FROM tb_prolocation WHERE pro_id='".$pid."' AND location_id ='".$branch_id."'";
		return $db->fetchrow($sql);	
		
	}
	public function getbranch($id){
		$db = $this->getAdapter();
		$sql="	SELECT 
		               t.id ,
					   t.cur_location ,
					   t.tran_location 
				FROM 
					   tb_product_transfer AS t 
				WHERE 
					 t.id='".$id."' ";
		return $db->fetchrow($sql);
	}
	
}