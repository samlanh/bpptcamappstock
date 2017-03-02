<?php
class Purchase_ExpensetitleController extends Zend_Controller_Action
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
        try{
        $db = new Purchase_Model_DbTable_Dbexpensetitle();
		if($this->getRequest()->isPost()){
		    $search = $this->getRequest()->getPost();
		}else{
		    $search = array(
		        "txt_search"=>'',
		        "parent_id_title"=>-1,
		        "title"	=>	'',
		        "title_en"	=>	'',
		         "status"=>-1,
		    );
		}
		$rows = $db->getAllTerms($search);
		$glClass = new Application_Model_GlobalClass();
		$rows = $glClass->getImgStatus($rows, BASE_URL, true);
		$list = new Application_Form_Frmlist();
		$columns=array("CATEGORY","TITLE","NAME_ENTITLE","STATUS");
		$link=array(
				'module'=>'purchase','controller'=>'expensetitle','action'=>'edit',
		);
		$this->view->list=$list->getCheckList(0, $columns, $rows, array('title'=>$link));
		
		$frmexpensetitle=new Purchase_Form_Frmexpensetitle();
		$frmexpensetitle->FrmAddExpenseTitle();
		$this->view->filterexpensetitle=$frmexpensetitle;
		}catch (Exception $e){
		    Application_Form_FrmMessage::message("Application Error");
		    Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	public function addAction()
	{
	    $tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$session_stock = new Zend_Session_Namespace('stock');
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			$db = new Purchase_Model_DbTable_Dbexpensetitle();
			$db->add($data);
			if($data['save_close']){
				Application_Form_FrmMessage::message($tr->translate("INSERT_SUCCESS"));
				Application_Form_FrmMessage::redirectUrl('/purchase/expensetitle/index');
			}
			else{
				Application_Form_FrmMessage::message($tr->translate("INSERT_SUCCESS"));
			}
		}
	}
	public function editAction()
	{
	    $tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$id = ($this->getRequest()->getParam('id'))? $this->getRequest()->getParam('id'): '0';
		$db = new Purchase_Model_DbTable_Dbexpensetitle();
		
		if($id==0){
			$this->_redirect('/purchase/expensetitle/index');
		}
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			$data["id"] = $id;
			$db = new Purchase_Model_DbTable_Dbexpensetitle();
			$db->edit($data);
			if($data['save_close']){
				Application_Form_FrmMessage::Sucessfull($tr->translate("EDIT_SUCCESS"), '/purchase/expensetitle/index');
			}
		}
		$this->view->rs =  $db->getTermById($id);
	}
	function addexpensetitleAction(){
		$post=$this->getRequest()->getPost();
		$db = new Purchase_Model_DbTable_Dbexpensetitle();
		$pid = $db->addajaxtitle($post);
		$result = array("id"=>$pid);
		echo Zend_Json::encode($result);
		exit();
	}
	function addajaxtitleincomeAction(){
	    
	    $post=$this->getRequest()->getPost();
	    $db = new Purchase_Model_DbTable_Dbexpensetitle();
	    $pid = $db->addajaxtitleincome($post);
	    $result = array("id"=>$pid);
	    echo Zend_Json::encode($result);
	    exit();
	   
	}
	
}

