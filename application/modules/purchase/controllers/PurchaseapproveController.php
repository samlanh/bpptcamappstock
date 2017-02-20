<?php
class Purchase_PurchaseapproveController extends Zend_Controller_Action
{	
    public function init()
    {
        /* Initialize action controller here */
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    }
	public function indexAction()
	{
		if($this->getRequest()->isPost()){
				$search = $this->getRequest()->getPost();
				$search['start_date']=date("Y-m-d",strtotime($search['start_date']));
				$search['end_date']=date("Y-m-d",strtotime($search['end_date']));
		}
		else{
			$search =array(
					'text_search'=>'',
					'start_date'=>date("Y-m-d"),
					'end_date'=>date("Y-m-d"),
					'suppliyer_id'=>0,
					'purchase_status'=>0,
					);
		}
		$db = new Purchase_Model_DbTable_DbRequestProductOrder();
		$rows = $db->getAllPurchaseOrder($search);
		$list = new Application_Form_Frmlist();
		$columns=array("BRANCH_NAME","VENDOR_NAME","PURCHASE_ORDER","ORDER_DATE","DATE_IN",
				 "CURRNECY_TYPE","TOTAL_AMOUNT","PAID","BALANCE","ORDER_STATUS","STATUS","BY_USER","CHECK");
		$link=array(
				'module'=>'purchase','controller'=>'purchaseapprove','action'=>'edit',
		);
		$link1=array(
				'module'=>'purchase','controller'=>'purchaseapprove','action'=>'edit',
		);
		
		$this->view->list=$list->getCheckList(0, $columns, $rows, array('branch_name'=>$link,'vendor_name'=>$link,'order_number'=>$link,'date_order'=>$link,'CHECK'=>$link1));
		$formFilter = new Application_Form_Frmsearch();
		$this->view->formFilter = $formFilter;
		Application_Model_Decorator::removeAllDecorator($formFilter);
	}
	public function addAction(){
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			try {
			$db = new Purchase_Model_DbTable_DbRequestProductOrder();
			
			    if(isset($data['btnsave_close'])){
			    	$db->addPurchaseOrder($data);
			    	Application_Form_FrmMessage::message("Purchase has been Saved!");
					Application_Form_FrmMessage::redirectUrl("/purchase/index");
				}
				if(isset($data['btnsavenew'])){
					$db->addPurchaseOrder($data);
					Application_Form_FrmMessage::message("Purchase has been Saved!");
					Application_Form_FrmMessage::redirectUrl("/purchase/index/add");
				} 
			}catch (Exception $e){
				Application_Form_FrmMessage::message('INSERT_FAIL');
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		///link left not yet get from DbpurchaseOrder 	
		$frm_purchase = new Application_Form_purchase(null);
		$form_add_purchase = $frm_purchase->productOrder();
		Application_Model_Decorator::removeAllDecorator($form_add_purchase);
		$this->view->form_purchase = $form_add_purchase;
		
		// item option in select
		$items = new Application_Model_GlobalClass();
		$this->view->items = $items->getProductOption();
		$this->view->items_leave = $items->getLeaveProductOption();
		$this->view->jobtype=$items->getJobTypeOption();
		
		$formProduct = new Product_Form_FrmProduct();
		$formStockAdd = $formProduct->add(null);
		Application_Model_Decorator::removeAllDecorator($formStockAdd);
		$this->view->form = $formStockAdd;
		
		$formpopup = new Application_Form_FrmPopup(null);
		//for add vendor
		$formStockAdd = $formpopup->popupVendor(null);
		Application_Model_Decorator::removeAllDecorator($formStockAdd);
		$this->view->form_vendor = $formStockAdd;
	 	
	}
	public function editAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$data['start_date']=date("Y-m-d",strtotime($data['start_date']));
			$data['end_date']=date("Y-m-d",strtotime($data['end_date']));
		}else{
			$data = array(
					'text_search'=>'',
					'start_date'=>date("Y-m-d"),
					'end_date'=>date("Y-m-d"),
					'suppliyer_id'=>0,
					'branch_id'=>0,
			);
		}
		$this->view->rssearch = $data;
		$query = new report_Model_DbQuery();
		$this->view->repurchase =  $query->getAllPurchaseReport($data);
		$frm = new Application_Form_FrmReport();
		
		$form_search=$frm->FrmReportPurchase($data);
		Application_Model_Decorator::removeAllDecorator($form_search);
		$this->view->form_purchase = $form_search;
	}
 

	
}