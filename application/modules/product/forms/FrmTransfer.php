<?php 
class Product_Form_FrmTransfer extends Zend_Form
{
	public function init()
    {

	}
	protected function GetuserInfo(){
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		return $result;
	}
	
	public function add($data=null) {
		$db=new Product_Model_DbTable_DbTransfer();
		$db_stock = new Product_Model_DbTable_DbAdjustStock();
		$rs_loc = $db->getLocation();
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
	
		$tran_num = new Zend_Form_Element_Text('tran_num');
		$tran_num->setAttribs(array('class'=>'form-control', 'required'=>'required','readOnly'=>true));
		$tran_num->setValue($db->getTransferNo());
    	
    	$date =new Zend_Date();
    	$tran_date = new Zend_Form_Element_Text('tran_date');
    	$tran_date->setValue($date->get('MM/dd/YYYY'));
    	$tran_date->setAttribs(array('class'=>'form-control date-picker', 'required'=>'required',));
    	
    	$remark = new Zend_Form_Element_Textarea("remark");
    	$remark->setAttribs(array('class'=>'form-control','style'=>'width: 100%;height:35px'));
    	
		$opt = array(''=>$tr->translate("SELECT_BRANCH"));
    	$to_loc = new Zend_Form_Element_Select("to_loc");
    	$to_loc->setAttribs(array(
    			'class'=>'form-control select2me',
				'required'=>'required',
    	));
    	if(!empty($rs_loc)){
    		foreach ($rs_loc as $rs){
    			$opt[$rs["id"]] = $rs["name"];
    		}
    	}
    	$to_loc->setMultiOptions($opt);
		
		$opt = array(''=>$tr->translate("SELECT_BRANCH"));
    	$from_loc = new Zend_Form_Element_Select("from_loc");
    	$from_loc -> setAttribs(array(
    			'class'=>'form-control select2me',
				'required'=>'required',
    	));
    	if(!empty($rs_loc)){
    		foreach ($rs_loc as $rs){
    			$opt[$rs["id"]] = $rs["name"];
    		}
    	}
    	$from_loc -> setMultiOptions($opt);
		
		
		
    	
    	$pro_name =new Zend_Form_Element_Select("pro_name");
    	$pro_name->setAttribs(array(
    			'class'=>'form-control select2me',
    			'onChange'=>'addNew();'
    	));
    	$opt= array(''=>$tr->translate("SELECT_PRODUCT"));
		$row_product = $db_stock->getProductName();
    	if(!empty($row_product)){
    		foreach ($row_product as $rs){
    			$opt[$rs["id"]] = $rs["item_name"]." ".$rs["model"]." ".$rs["size"]." ".$rs["color"];
    		}
    	}
    	$pro_name->setMultiOptions($opt);
    	
    	$type =new Zend_Form_Element_Select("type");
    	$type->setAttribs(array(
    			'class'=>'form-control select2me',
    			'onChange'=>'transferType()'
    	));
    	$opt= array(''=>$tr->translate("SELECT_TRANSFER_TYPE"),1=>$tr->translate("TRANSFER_IN"),2=>$tr->translate("TRANSFER_OUT"));
    	$type->setMultiOptions($opt);

    	$status =new Zend_Form_Element_Select("status");
    	$status->setAttribs(array(
    			'class'=>'form-control select2me',
    	));
    	$opt= array(2 =>$tr->translate("PENDING APPROVE"));
    	$status->setMultiOptions($opt);
    	//set value when edit
    	if($data != null) {
    		$from_loc->setValue($data["cur_location"]);
			$tran_num->setValue($data["tran_no"]);
    		$tran_date->setValue($data["date"]);
    		$remark->setValue($data["remark"]);
    		$to_loc->setValue($data["tran_location"]);
    		$status->setValue($data["status"]);
    		$type->setValue($data["type"]);
    	}
    	$this->addElements(array($status,$type,$pro_name,$tran_num,$tran_date,$remark,$from_loc,$to_loc));
    	return $this;
	}
	
	public function addRequest($data=null) {
		$db=new Product_Model_DbTable_DbTransfer();
		$db_stock = new Product_Model_DbTable_DbAdjustStock();
		$rs_loc = $db->getLocation();
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
	
		$tran_num = new Zend_Form_Element_Text('tran_num');
		$tran_num->setAttribs(array('class'=>'form-control', 'required'=>'required','readOnly'=>true));
		$tran_num->setValue($db->getRequestTransferNo());
    	
    	$date =new Zend_Date();
    	$tran_date = new Zend_Form_Element_Text('tran_date');
    	$tran_date->setValue($date->get('MM/dd/YYYY'));
    	$tran_date->setAttribs(array('class'=>'form-control date-picker', 'required'=>'required',));
    	
    	$remark = new Zend_Form_Element_Textarea("remark");
    	$remark->setAttribs(array('class'=>'form-control','style'=>'width: 100%;height:35px'));
    	
    	$from_loc = new Zend_Form_Element_Select("from_loc");
    	$from_loc->setAttribs(array(
    			'class'=>'form-control select2me',
    	));
    	
    	$opt = array(''=>$tr->translate("SELECT BRANCH"));
    	$to_loc = new Zend_Form_Element_Select("to_loc");
    	$to_loc->setAttribs(array(
    			'class'=>'form-control select2me',
    	));
    	if(!empty($rs_loc)){
    		foreach ($rs_loc as $rs){
    			$opt[$rs["id"]] = $rs["name"];
    		}
    	}
    	$to_loc->setMultiOptions($opt);
    	
    	$pro_name =new Zend_Form_Element_Select("pro_name");
    	$pro_name->setAttribs(array(
    			'class'=>'form-control select2me',
    			'onChange'=>'addNew();'
    	));
    	$opt= array(''=>$tr->translate("SELECT PRODUCT"));
		$row_product=$db_stock->getProductName();
    	if(!empty($row_product)){
    		foreach ($row_product as $rs){
    			$opt[$rs["id"]] = $rs["item_name"]." ".$rs["model"]." ".$rs["size"]." ".$rs["color"];
    		}
    	}
    	$pro_name->setMultiOptions($opt);
    	
    	$type =new Zend_Form_Element_Select("type");
    	$type->setAttribs(array(
    			'class'=>'form-control select2me',
    			'onChange'=>'transferType()'
    	));
    	$opt= array(''=>$tr->translate("SELECT_TRANSFER_TYPE"),1=>$tr->translate("TRANSFER_IN"),2=>$tr->translate("TRANSFER_OUT"));
    	$type->setMultiOptions($opt);
    	
    	
    	$status =new Zend_Form_Element_Select("status");
    	$status->setAttribs(array(
    			'class'=>'form-control select2me',
    	));
    	$opt= array(''=>$tr->translate("SELECT_STATUS"),1=>$tr->translate("ACTIVE"),2=>$tr->translate("DEACTIVE"));
    	$status->setMultiOptions($opt);
    	//set value when edit
    	if($data != null) {
    		$tran_num->setValue($data["tran_no"]);
    		$tran_date->setValue($data["date"]);
    		$remark->setValue($data["remark"]);
    		$to_loc->setValue($data["tran_location"]);
    		$status->setValue($data["status"]);
    		$type->setValue($data["type"]);
    	}
    	$this->addElements(array($status,$type,$pro_name,$tran_num,$tran_date,$remark,$from_loc,$to_loc));
    	return $this;
	}
	
	function frmFilter(){
		$db=new Product_Model_DbTable_DbTransfer();
		$db_stock = new Product_Model_DbTable_DbAdjustStock();
		$rs_loc = $db->getLocation();
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$request=Zend_Controller_Front::getInstance()->getRequest();
		
		$tran_num = new Zend_Form_Element_Text('tran_num');
		$tran_num->setAttribs(array('class'=>'form-control'));
		$tran_num->setValue($request->getParam("tran_num"));
		 
		$date =new Zend_Date();
		$tran_date = new Zend_Form_Element_Text('tran_date');
		$tran_date->setValue($date->get('MM/dd/YYYY'));
		$tran_date->setAttribs(array('class'=>'form-control date-picker'));
		//$tran_date->setValue($request->getParam("tran_date"));
		 
		$status =new Zend_Form_Element_Select("status");
		$status->setAttribs(array(
				'class'=>'form-control select2me',
		));
		$opt= array(''=>$tr->translate("SELECT_STATUS"),1=>$tr->translate("APPROVE"),2=>$tr->translate("PENDING APPROVE"),3=>$tr->translate("REJECT"),4=>$tr->translate("CENCEL"));
		$status->setMultiOptions($opt);
		$status->setValue($request->getParam("status"));
		
		$opt = array(''=>$tr->translate("FROM SITE"));
		$to_loc = new Zend_Form_Element_Select("to_loc");
		$to_loc->setAttribs(array(
				'class'=>'form-control select2me',
		));
		if(!empty($rs_loc)){
			foreach ($rs_loc as $rs){
				$opt[$rs["id"]] = $rs["name"];
			}
		}
		$to_loc->setMultiOptions($opt);
		$to_loc->setValue($request->getParam("to_loc"));
		
		
		$opt = array(''=>$tr->translate("SITE"));
		$from_loc = new Zend_Form_Element_Select("from_loc");
		$from_loc->setAttribs(array(
				'class'=>'form-control select2me',
		));
		if(!empty($rs_loc)){
			foreach ($rs_loc as $rs){
				$opt[$rs["id"]] = $rs["name"];
			}
		}
		$from_loc->setMultiOptions($opt);
		$from_loc->setValue($request->getParam("from_loc"));
		
		$this->addElements(array($status,$tran_num,$tran_date,$to_loc ,$from_loc));
		return $this;
	}
}