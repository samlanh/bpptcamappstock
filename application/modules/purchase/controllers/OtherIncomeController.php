<?php

class Purchase_OtherIncomeController extends Zend_Controller_Action
{
	const REDIRECT_URL = '/purchase/otherincome';
	
    public function init()
    {
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }

    public function indexAction()
    {
    	try{
    		$db = new Purchase_Model_DbTable_Dbotherincome();
    		if($this->getRequest()->isPost()){
    			$formdata=$this->getRequest()->getPost();
    			$formdata['start_date']=date("Y-m-d",strtotime($formdata['start_date']));
    			$formdata['end_date']=date("Y-m-d",strtotime($formdata['end_date']));
    		}
    		else{
    			$formdata = array(
    					"text_search"=>'',
    					"branch_id"=>-1,
    					'title_in'=>-1,
    					"status"=>-1,
    					'start_date'=> date('Y-m-d'),
    					'end_date'=>date('Y-m-d'),
    			);
    		}
    		
			$rs_rows= $db->getAllotherincome($formdata);//call frome model
    		$glClass = new Application_Model_GlobalClass();
    		//$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
    		$list = new Application_Form_Frmlist();
    		$collumns = array("BRANCH_NAME","INVOICE_NO","INCOME_TITLE","CURRENCY_TYPE","TOTAL_INCOME","NOTE","DATE","BY_USER","STATUS");
    		$link=array(
    				'module'=>'purchase','controller'=>'otherincome','action'=>'edit',
    		);
    		$this->view->list=$list->getCheckList(0, $collumns,$rs_rows,array('branch_name'=>$link,'title'=>$link,'invoice'=>$link,'total_amount'=>$link));
    	}catch (Exception $e){
    		Application_Form_FrmMessage::message("Application Error");
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    	}
		$formFilter = new Application_Form_Frmsearch();
		$this->view->formFilter = $formFilter;
		Application_Model_Decorator::removeAllDecorator($formFilter);
    }
    public function addAction()
    {
    	if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();	
			$db = new Purchase_Model_DbTable_Dbotherincome();				
			try {$db->addotherincome($data);
			if(isset($data['btnsave_close'])){
			    
			    	Application_Form_FrmMessage::message("Income has been Saved!");
					Application_Form_FrmMessage::redirectUrl("/purchase/otherincome");
				}
				if(isset($data['btnsavenew'])){
				    
					Application_Form_FrmMessage::message("Income has been Saved!");
					Application_Form_FrmMessage::redirectUrl("/purchase/otherincome/add");
				} 				
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
    	$pructis=new Purchase_Form_Frmothericome();
    	$frm = $pructis->FrmAddincome();
    	Application_Model_Decorator::removeAllDecorator($frm);
    	$this->view->frm_income=$frm;
    }
 
    public function editAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();	
			$data['id'] = $id;
			$db = new Purchase_Model_DbTable_Dbotherincome();				
			try {
				$db->updateotherincome($data);				
				Application_Form_FrmMessage::Sucessfull('ការកែប្រែ​​ជោគ​ជ័យ', self::REDIRECT_URL);		
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$id = $this->getRequest()->getParam('id');
		$db = new Purchase_Model_DbTable_Dbotherincome();
		$row  = $db->getotherincomebyid($id);
		$this->view->row = $row;
		
    	$pructis=new Purchase_Form_Frmothericome();
    	$frm = $pructis->FrmAddincome($row);
    	Application_Model_Decorator::removeAllDecorator($frm);
    	$this->view->frm_income=$frm;
    	
    }
    function addtitleincomeAction(){
        $post=$this->getRequest()->getPost();
        $db = new Purchase_Model_DbTable_Dbexpensetitle();
        $pid = $db->addajaxtitleincome($post);
        $result = array("id"=>$pid);
        echo Zend_Json::encode($result);
        exit();
    }

}







