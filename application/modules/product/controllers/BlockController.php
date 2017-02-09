<?php
class Product_blockController extends Zend_Controller_Action
{
public function init()
    {
        /* Initialize action controller here */
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }
    protected function GetuserInfoAction(){
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	return $result;
    }
    public function indexAction()
    {
		$db = new Product_Model_DbTable_DbBlock();
		$formFilter = new Product_Form_FrmBranchFilter();
		$frmsearch = $formFilter->branchFilter();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
		}else{
			$data = array(
					'branch_name'	=>	'',
					'status'	=>	1
			);
		}
		$this->view->formFilter = $frmsearch;
		$list = new Application_Form_Frmlist();
		$result = $db->getAllBranch($data);
		$this->view->resulr = $result;
		Application_Model_Decorator::removeAllDecorator($formFilter);
	}
	public function addAction()
	{
		$session_stock = new Zend_Session_Namespace('stock');
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			$db = new Product_Model_DbTable_Dbblock();
			$db->add($data);
			if($data['save_close']){
				Application_Form_FrmMessage::Sucessfull("INSER_SUCCESS", '/product/block/index');
			}
			else{
				Application_Form_FrmMessage::Sucessfull("INSER_SUCCESS", '/product/block/add/add');
			}
		}
		$formFilter = new Product_Form_FrmBlock();
		$formAdd = $formFilter->Block();
		$this->view->frmAdd = $formAdd;
		Application_Model_Decorator::removeAllDecorator($formAdd);
	}
	public function editAction()
	{
		$id = ($this->getRequest()->getParam('id'))? $this->getRequest()->getParam('id'): '0';
		$db = new Product_Model_DbTable_DbBlock();
		if($id==0){
			$this->_redirect('/product/block/index/add');
		}
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			$data["id"] = $id;
			$db->edit($data);
			if($data['save_close']){
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS", '/product/block/index');
			}
		}
		$rs = $db->getBranchById($id);
		$formFilter = new Product_Form_FrmBlock();
		$formAdd = $formFilter->Block($rs);
		$this->view->frmAdd = $formAdd;
		Application_Model_Decorator::removeAllDecorator($formAdd);
	}
	//view category 27-8-2013
	
	public function addNewLocationAction(){
		$post=$this->getRequest()->getPost();
		$add_new_location = new Product_Model_DbTable_DbAddProduct();
		$location_id = $add_new_location->addStockLocation($post);
		$result = array("LocationId"=>$location_id);
		if(!$result){
			$result = array('LocationId'=>1);
		}
		echo Zend_Json::encode($result);
		exit();
	}
	public function upadatestatusAction(){
				try {
				$db = new Product_Model_DbTable_DbBlock();
				$id 	=	 $this	->	getRequest()	->	getParam("id");
				$status = 	 $this	->	getRequest()	->	getParam("status");
				//print_r($status);exit();
				$db	->	updatestatus($id,$status);
				Application_Form_FrmMessage::Sucessfull("Update_SUCCESS");
				$this->_redirect('/product/block/index/');
			}
			catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				$err 	=	$e	->	getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
	}
	
}

