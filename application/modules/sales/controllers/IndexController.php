<?php
class Sales_IndexController extends Zend_Controller_Action{	
    public function init()
    {
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    }
    protected function GetuserInfoAction(){
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	return $result;
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
					'branch_id'=>-1,
					'customer_id'=>-1,
					);
		}
		$db = new Sales_Model_DbTable_DbSaleOrder();
		$rows = $db->getAllSaleOrder($search);
		$columns=array("BRANCH_NAME","SALE_AGENT","SALE_NO", "ORDER_DATE",
				"CURRNECY_TYPE","IN_STOCK","LEAVE STOCK","TOTAL","DISCOUNT","TOTAL_AMOUNT","PAID","BALANCE","PROCESSING","BY_USER");
		$link=array(
				'module'=>'sales','controller'=>'index','action'=>'edit',
		);
		$link1=array(
				'module'=>'sales','controller'=>'index','action'=>'viewapp',
		);
		
		$list = new Application_Form_Frmlist();
		$this->view->list=$list->getCheckList(0, $columns, $rows, array('branch_name'=>$link,'customer_name'=>$link,'staff_name'=>$link,
				'sale_no'=>$link,'approval'=>$link1));
		
		$formFilter = new Sales_Form_FrmSearch();
		$this->view->formFilter = $formFilter;
	    Application_Model_Decorator::removeAllDecorator($formFilter);
	}	
	function addAction(){
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			try {
				$dbq = new Sales_Model_DbTable_DbSaleOrder();
				if(isset($data['saveprint'])){
					$dbq->addSaleOrder($data);
					Application_Form_FrmMessage::message("INSERT_SUCESS");
					Application_Form_FrmMessage::redirectUrl("/sales/index");
				}
				if(isset($data['save_new'])){
					$dbq->addSaleOrder($data);
					Application_Form_FrmMessage::message("INSERT_SUCESS");
					Application_Form_FrmMessage::redirectUrl("/sales/index/add");
				}
			}catch (Exception $e){
				Application_Form_FrmMessage::message('INSERT_FAIL');
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$frm_purchase = new Sales_Form_FrmSale(null);
		$form_sale = $frm_purchase->SaleOrder(null);
		Application_Model_Decorator::removeAllDecorator($form_sale);
		$this->view->form_sale = $form_sale;
		$items = new Application_Model_GlobalClass();
		$this->view->items = $items->getProductOption();
		$this->view->items_leave = $items->getLeaveProductOption();
		$this->view->jobtype=$items->getJobTypeOption();
		$this->view->term_opt = $db->getAllTermCondition(1);
		$formpopup = new Sales_Form_FrmCustomer(null);
		$formpopup = $formpopup->Formcustomer(null);
		Application_Model_Decorator::removeAllDecorator($formpopup);
		$this->view->form_customer = $formpopup;
	}
	function editAction(){
	    $tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$id = ($this->getRequest()->getParam('id'))? $this->getRequest()->getParam('id'): '0';
		$dbq = new Sales_Model_DbTable_DbSaleOrder();
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			$data['id']=$id;
			try {
				if(isset($data['saveprint'])){
					$dbq->updateSaleOrder($data);
					Application_Form_FrmMessage::message("INSERT_SUCESS");
					Application_Form_FrmMessage::redirectUrl("/sales/index");
				}
				if(isset($data['save_new'])){
					$dbq->updateSaleOrder($data);
					Application_Form_FrmMessage::message("INSERT_SUCESS");
					Application_Form_FrmMessage::redirectUrl("/sales/index/");
				}
			}catch (Exception $e){
				Application_Form_FrmMessage::message($tr->translate('UPDATE_FAIL'));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$row = $dbq->getSaleorderItemById($id);
		$this->view->rs_sale=$row;
		$this->view->rs_sale_instock=$dbq->getSaleorderDetailInstockid($id);
		$dd=$this->view->rs_sale_nonestcok=$dbq->getSaleorderDetailNoneStock($id);
		$this->view->rsterm = $dbq->getTermconditionByid($id);
		
		///link left not yet get from DbpurchaseOrder
		$frm_purchase = new Sales_Form_FrmSale(null);
		$form_sale = $frm_purchase->SaleOrder($row);
		Application_Model_Decorator::removeAllDecorator($form_sale);
		$this->view->form_sale = $form_sale;
		$this->view->discount_type = $row['discount_type'];
		// item option in select
		$items = new Application_Model_GlobalClass();
		$this->view->items = $items->getProductOption();
		$this->view->items_leave = $items->getLeaveProductOption();
		$this->view->jobtype=$items->getJobTypeOption();
		$this->view->term_opt = $db->getAllTermCondition(1);
	}

	function oldeditAction(){
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$id = ($this->getRequest()->getParam('id'))? $this->getRequest()->getParam('id'): '0';
		$dbq = new Sales_Model_DbTable_DbSaleOrder();
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			$data['id']=$id;
			try {
				if(isset($data['saveprint'])){
					$dbq->updateSaleOrder($data);
					Application_Form_FrmMessage::message("INSERT_SUCESS");
					Application_Form_FrmMessage::redirectUrl("/sales/index");
				}
				if(isset($data['save_new'])){
					$dbq->updateSaleOrder($data);
					Application_Form_FrmMessage::message("INSERT_SUCESS");
					Application_Form_FrmMessage::redirectUrl("/sales/index/");
				}
			}catch (Exception $e){
				Application_Form_FrmMessage::message($tr->translate('UPDATE_FAIL'));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$row = $dbq->getSaleorderItemById($id);
		$this->view->rs_sale=$row;
		$this->view->rs_sale_instock=$dbq->getSaleorderDetailInstockid($id);
		$dd=$this->view->rs_sale_nonestcok=$dbq->getSaleorderDetailNoneStock($id);
		$this->view->rsterm = $dbq->getTermconditionByid($id);
	
		///link left not yet get from DbpurchaseOrder
		$frm_purchase = new Sales_Form_FrmSale(null);
		$form_sale = $frm_purchase->SaleOrder($row);
		Application_Model_Decorator::removeAllDecorator($form_sale);
		$this->view->form_sale = $form_sale;
		$this->view->discount_type = $row['discount_type'];
		// item option in select
		$items = new Application_Model_GlobalClass();
		$this->view->items = $items->getProductOption();
		$this->view->items_leave = $items->getLeaveProductOption();
		$this->view->jobtype=$items->getJobTypeOption();
		$this->view->term_opt = $db->getAllTermCondition(1);
	}
	
	function viewAction(){
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			try {
				$dbq = new Sales_Model_DbTable_DbSaleOrder();
				$dbq->addSaleOrder($data);
				
				Application_Form_FrmMessage::message("INSERT_SUCESS");
				if(isset($data['btnsavenew'])){
					Application_Form_FrmMessage::redirectUrl("/sales/index");
				}
			}catch (Exception $e){
				Application_Form_FrmMessage::message('INSERT_FAIL');
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$frm_purchase = new Sales_Form_FrmSale(null);
		$form_sale = $frm_purchase->SaleOrder(null);
		Application_Model_Decorator::removeAllDecorator($form_sale);
		$this->view->form_sale = $form_sale;
		$items = new Application_Model_GlobalClass();
		$this->view->items = $items->getProductOption();
		$this->view->items_leave = $items->getLeaveProductOption();
		$this->view->jobtype=$items->getJobTypeOption();
		$this->view->term_opt = $db->getAllTermCondition(1);
		$formpopup = new Sales_Form_FrmCustomer(null);
		$formpopup = $formpopup->Formcustomer(null);
		Application_Model_Decorator::removeAllDecorator($formpopup);
		$this->view->form_customer = $formpopup;
	}
	
	function viewappAction(){
		$id = ($this->getRequest()->getParam('id'))? $this->getRequest()->getParam('id'): '0';
		if(empty($id)){
			$this->_redirect("/sales/salesapprove");
		}
		$query = new Sales_Model_DbTable_Dbsalesapprov();
		$this->view->product =  $query->getProductSaleById($id);
		$rs = $query->getProductSaleById($id);
		if(empty($rs)){
			$this->_redirect("/sales/salesapprove");
		}
		$db= new Application_Model_DbTable_DbGlobal();
		$this->view->rscondition = $db->getTermConditionById(1, $id);
	}
	public function getproductpriceAction(){
		if($this->getRequest()->isPost()){
			$post=$this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$rs = $db ->getProductPriceBytype($post['customer_id'], $post['product_id']);
			echo Zend_Json::encode($rs);
			exit();
		}
	}
	function getsonumberAction(){
		if($this->getRequest()->isPost()){
			$post=$this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$qo = $db->getSalesNumber($post['branch_id']);
			echo Zend_Json::encode($qo);
			exit();
		}
	}
	public function getqtybyidAction(){
  		$post=$this->getRequest()->getPost();
  		$item_id = $post['item_id'];
  		$branch_id = $post['branch_id'];
  		$sql="  SELECT `qty_perunit`,
						(SELECT qty FROM `tb_prolocation` WHERE location_id=$branch_id AND pro_id=$item_id LIMIT 1 ) AS qty 
						FROM tb_product WHERE id= $item_id LIMIT 1  ";
  		$db = new Application_Model_DbTable_DbGlobal();
  		$row=$db->getGlobalDbRow($sql);
  		echo Zend_Json::encode($row);
  		exit();
	}
	public function getblockAction(){	
		if($this->getRequest()->isPost()){
			$post=$this->getRequest()->getPost();
			$db = new Sales_Model_DbTable_DbSaleOrder();
			$qo = $db->getBlock($post['branch_id']);
			echo Zend_Json::encode($qo);
			exit();
		}
	}	
	public function getProductInstockAction(){
		if($this->getRequest()->isPost()){
			$post=$this->getRequest()->getPost();
			$db = new Sales_Model_DbTable_DbSaleOrder();
			$qo = $db->getProductInstock($post['branch_id']);
			array_unshift($qo, array ( 'id' => 0,'item_name' => 'Select Product'));
			echo Zend_Json::encode($qo);
			exit();
		}
	}	
	
	public function getProductNonestockAction(){
		if($this->getRequest()->isPost()){
			$post=$this->getRequest()->getPost();
			$db = new Sales_Model_DbTable_DbSaleOrder();
			$qo = $db->getProductNonestock($post['branch_id']);
			array_unshift($qo, array ( 'id' => 0,'item_name' => 'Select Product'));
			echo Zend_Json::encode($qo);
			exit();
		}
	}
}