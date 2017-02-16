<?php 
class Purchase_Form_FrmRequest extends Zend_Form
{
	public function init()
    {
   
    }
    protected function GetuserInfo(){
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	return $result;
    }
	public function productOrder($data=null)
	{
		//Application_Form_FrmLanguages::getCurrentlanguage();
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		 
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db=new Application_Model_DbTable_DbGlobal();
		 
		$paymentElement = new Zend_Form_Element_Text('payment_number');
		$paymentElement ->setAttribs(array(
				'class' => 'form-control',
				'placeHolder' => 'Number...',
				'Onchange'=>'getCustomerInfo()'));
		$this->addElement($paymentElement);
		 
		$options = $db->getAllVendor(1);
		$vendor_id=new Zend_Form_Element_Select('v_name');
		$vendor_id ->setAttribs(array(
				'class' => 'form-control select2me',
				'Onchange'=>'getSuppliyer()'));
		 
		$vendor_id->setMultiOptions($options);
		$customerValue = $request->getParam('vendor_id');
		$vendor_id->setValue($customerValue);
		$this->addElement($vendor_id);
		 
		$po_number= new Zend_Form_Element_Text("txt_order");
		$po_number->setAttribs(array('placeholder' => 'Optional','class'=>'form-control name',
		));
		$this->addElement($po_number);
		 
		$roder_element= new Zend_Form_Element_Text("invoice_no");
		$roder_element->setAttribs(array('placeholder' => 'Optional','class'=>'validate[required] form-control',
		));
		$this->addElement($roder_element);
		 
		$orderElement = new Zend_Form_Element_Text('order');
		$orderElement ->setAttribs(array('placeholder' => 'Enter Order'));
		$this->addElement($orderElement);
		 
		$phoneElement = new Zend_Form_Element_Text('txt_phone');
		$phoneElement->setAttribs(array('placeholder' => 'Enter Phone Number'));
		$this->addElement($phoneElement);
		 
		$user= $this->GetuserInfo();
		 
		$options="";
	
		$productValue = $request->getParam('LocationId');
		$locationID = new Zend_Form_Element_Select('LocationId');
		$locationID ->setAttribs(array('class'=>'form-control select2me'));
		 
		$options = $db->getAllLocation(1);
		$locationID->setMultiOptions($options);
		$locationID->setattribs(array(
				'Onchange'=>'AddLocation()',));
		$locationID->setValue($productValue);
		$this->addElement($locationID);
	
		$paymentmethodElement = new Zend_Form_Element_Select('payment_name');
		$options_cg = $db->getAllPaymentmethod(1);
		$paymentmethodElement->setMultiOptions($options_cg);
		$this->addElement($paymentmethodElement);
		$paymentmethodElement->setAttribs(array("class"=>"form-control select2me"));
	
		$options_cur = $db->getAllCurrency(1);
		$currencyElement = new Zend_Form_Element_Select('currency');
		$currencyElement->setAttribs(array('class'=>'demo-code-language form-control select2me'));
		$currencyElement->setMultiOptions($options_cur);
		$this->addElement($currencyElement);
		 
		$descriptionElement = new Zend_Form_Element_Textarea('remark');
		$descriptionElement->setAttribs(array("class"=>'form-control',"rows"=>3));
		$this->addElement($descriptionElement);
		 
		$allTotalElement = new Zend_Form_Element_Text('all_total');
		$allTotalElement->setAttribs(array("class"=>"form-control",'readonly'=>'readonly','style'=>'text-align:left'));
		$this->addElement($allTotalElement);
		 
		$discountTypeElement = new Zend_Form_Element_Radio('discount_type');
		$discountTypeElement->setMultiOptions(array(1=>'%',2=>'Fix Value'));
		$discountTypeElement->setAttribs(array('checked'=>'checked',));
		$discountTypeElement->setAttribs(array('onChange'=>'doTotal()',"class"=>"form-control"));
		$this->addElement($discountTypeElement);
	
		$netTotalElement = new Zend_Form_Element_Text('net_total');
		$netTotalElement->setAttribs(array('readonly'=>'readonly',));
		$this->addElement($netTotalElement);
		 
		$discountValueElement = new Zend_Form_Element_Text('discount_value');
		$discountValueElement->setAttribs(array('class'=>'input100px form-control','onblur'=>'doTotal()',));
		$this->addElement($discountValueElement);
		 
		$discountRealElement = new Zend_Form_Element_Text('discount_real');
		$discountRealElement->setAttribs(array('readonly'=>'readonly','class'=>'input100px form-control',));
		$this->addElement($discountRealElement);
		 
		$globalRealElement = new Zend_Form_Element_Hidden('global_disc');
		$globalRealElement->setAttribs(array("class"=>"form-control"));
		$this->addElement($globalRealElement);
		 
		$discountValueElement = new Zend_Form_Element_Text('discount_value');
		$discountValueElement->setAttribs(array('class'=>'input100px','onblur'=>'doTotal();','style'=>'text-align:left'));
		$this->addElement($discountValueElement);
		 
		$dis_valueElement = new Zend_Form_Element_Text('dis_value');
		$dis_valueElement->setAttribs(array("required"=>1,'placeholder' => 'Discount Value','style'=>'text-align:left'));
		$dis_valueElement->setValue(0);
		$dis_valueElement->setAttribs(array("onkeyup"=>"calculateDiscount();","class"=>"form-control"));
		$this->addElement($dis_valueElement);
		 
		$totalAmountElement = new Zend_Form_Element_Text('totalAmoun');
		$totalAmountElement->setAttribs(array('readonly'=>'readonly','style'=>'text-align:left',"class"=>"form-control"
		));
		$this->addElement($totalAmountElement);
		 
		$remainlElement = new Zend_Form_Element_Text('remain');
		$remainlElement->setAttribs(array('readonly'=>'readonly','style'=>'text-align:left',"class"=>"red form-control"));
		$this->addElement($remainlElement);
		 
		$balancelElement = new Zend_Form_Element_Text('balance');
		$balancelElement->setAttribs(array('readonly'=>'readonly','style'=>'text-align:left',"class"=>"form-control"));
		$this->addElement($balancelElement);
		 
		$statusElement = new Zend_Form_Element_Select('status');
		$opt_status = array(2=>'Open',5=>"Recieved");
		$statusElement ->setAttribs(array('class'=>'form-control select2me','onchange'=>'calculatePrice();'));
		$statusElement->setMultiOptions($opt_status);
		$this->addElement($statusElement);
	
		$date_inElement = new Zend_Form_Element_Text('date_in');
		$date =new Zend_Date();
		$date_inElement ->setAttribs(array('class'=>'validate[required] form-control form-control-inline date-picker'));
		$date_inElement ->setValue($date->get('MM/d/Y'));
		$this->addElement($date_inElement);
		 
		$dateOrderElement = new Zend_Form_Element_Text('order_date');
		$dateOrderElement ->setAttribs(array('class'=>'col-md-3 validate[required] form-control form-control-inline date-picker','placeholder' => 'Click to Choose Date'));
		$dateOrderElement ->setValue($date->get('M/d/Y'));
		$this->addElement($dateOrderElement);
	
		$termElement = new Zend_Form_Element_Text('term');
		$termElement->setAttribs(array('class'=>'validate[required]',));
		$this->addElement($termElement);
		 
		$totalElement = new Zend_Form_Element_Text('total');
		$this->addElement($totalElement);
		 
		$totaTaxElement = new Zend_Form_Element_Text('total_tax');
		$totaTaxElement->setAttribs(array('class'=>'custom[number] form-control','style'=>'text-align:left'));
		$this->addElement($totaTaxElement);
		 
		$paidElement = new Zend_Form_Element_Text('paid');
		$paidElement->setAttribs(array('class'=>'custom[number] form-control','onkeyup'=>'doRemain();','style'=>'text-align:left'));
		$this->addElement($paidElement);
		 
		///conter leave controll
		$paidlElement = new Zend_Form_Element_Text('paid_l');
		$paidlElement->setAttribs(array('class'=>'custom[number] form-control','onkeyup'=>'doRemains();','style'=>'text-align:left'));
		$this->addElement($paidlElement);
		 
		$total_l_AmountElement = new Zend_Form_Element_Text('totalAmoun_l');
		$total_l_AmountElement->setAttribs(array('readonly'=>'readonly','style'=>'text-align:left',"class"=>"form-control"
		));
		$this->addElement($total_l_AmountElement);
		 
		$dis_value_l = new Zend_Form_Element_Text('dis_value_l');
		$dis_value_l->setAttribs(array("required"=>1,'placeholder' => 'Discount Value','style'=>'text-align:left'));
		$dis_value_l->setValue(0);
		$dis_value_l->setAttribs(array("onkeyup"=>"calculateDiscounts();","class"=>"form-control"));
		$this->addElement($dis_value_l);
		 
		$all_total_l = new Zend_Form_Element_Text('all_total_l');
		$all_total_l->setAttribs(array("class"=>"form-control",'readonly'=>'readonly','style'=>'text-align:left'));
		$this->addElement($all_total_l);
		 
		$remain_l = new Zend_Form_Element_Text('remain_l');
		$remain_l->setAttribs(array('readonly'=>'readonly','style'=>'text-align:left',"class"=>"red form-control"));
		$this->addElement($remain_l);
		 
		 
		$old_status = new Zend_Form_Element_Hidden('old_status');
		$this->addElement($old_status);
		 
		$old_location = new Zend_Form_Element_Hidden('old_location');
		$this->addElement($old_location);
		 
		$status = new Zend_Form_Element_Select('status_use');
		$opt_status = array(1=>'Active',0=>"Deative");
		$status ->setAttribs(array('class'=>'form-control select2me'));
		$status->setMultiOptions($opt_status);
		$this->addElement($status);
	
		$date_issuecheque = new Zend_Form_Element_Text('date_issuecheque');
		$date =new Zend_Date();
		$date_issuecheque ->setAttribs(array('class'=>'validate[required] form-control form-control-inline date-picker'));
		$date_issuecheque ->setValue($date->get('MM/d/Y'));
		$this->addElement($date_issuecheque);
		 
		Application_Form_DateTimePicker::addDateField(array('order_date','date_in','receiv_date'));
		if($data != null) {
			$old_location->setValue($data["branch_id"]);
			$status->setValue($data["status"]);
			$old_status->setValue($data["purchase_status"]);
			$idElement = new Zend_Form_Element_Hidden('id');
			$idElement->setValue($data["id"]);
			$this->addElement($idElement);
			$vendor_id->setValue($data["vendor_id"]);
			$locationID->setValue($data["branch_id"]);
			$dateOrderElement->setValue(date("m/d/Y",strtotime($data["date_order"])));
			$date_inElement->setValue(date("m/d/Y",strtotime($data["date_in"])));
			$statusElement->setValue($data["purchase_status"]);
			$date_issuecheque->setValue(date("m/d/Y",strtotime($data["date_issuecheque"])));
			//$roder_element->setAttribs(array('readonly'=>'readonly'));
			$roder_element->setValue($data["invoice_no"]);
			$po_number->setValue($data["order_number"]);
			 
			$descriptionElement->setValue($data["remark"]);
			$currencyElement->setValue($data['currency_id']);
			$paymentmethodElement->setValue($data['payment_method']);
			$paymentElement->setValue($data['payment_number']);
			$paidElement->setValue($data['paid']);
			$totalAmountElement->setValue($data['all_total']);//r
			$netTotalElement->setValue($data['all_total']);//r
			$allTotalElement->setValue($data['net_total']);//r
			$remainlElement->setValue($data['balance']);//r
	
		} else {$discountTypeElement->setValue(1);
		}
		return $this;
	
	}
}