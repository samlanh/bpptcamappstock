<?php
class Application_Form_Frmsearch extends Zend_Form
{
	public function init()
	{
	    $tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db=new Application_Model_DbTable_DbGlobal();
		
		$tr=Application_Form_FrmLanguages::getCurrentlanguage();
		$nameValue = $request->getParam('text_search');
		$nameElement = new Zend_Form_Element_Text('text_search');
		$nameElement->setAttribs(array(
				'class'=>'form-control'
				));
		$nameElement->setValue($nameValue);
		$this->addElement($nameElement);
		
		$rs=$db->getGlobalDb('SELECT vendor_id, v_name FROM tb_vendor WHERE v_name!="" AND status=1 ');
		$options=array($tr->translate('CHOOSE_SUPPLIYER'));
		$vendorValue = $request->getParam('suppliyer_id');
		if(!empty($rs)) foreach($rs as $read) $options[$read['vendor_id']]=$read['v_name'];
		$vendor_element=new Zend_Form_Element_Select('suppliyer_id');
		$vendor_element->setMultiOptions($options);
		$vendor_element->setAttribs(array(
				'id'=>'suppliyer_id',
				'class'=>'form-control select2me'
		));
		$vendor_element->setValue($vendorValue);
		$this->addElement($vendor_element);
		$_stutas = new Zend_Form_Element_Select('status');
		$_stutas ->setAttribs(array(
				'class'=>' form-control',			
		));
		$options= array(-1=>$tr->translate("ALL"),1=>$tr->translate("ACTIVE"),0=>$tr->translate("DEACTIVE"));
		$_stutas->setMultiOptions($options);
		$this->addElement($_stutas);
		
		/////////////Date of lost item		/////////////////
		$startDateValue = $request->getParam('start_date');
		$endDateValue = $request->getParam('end_date');
		
		if($endDateValue==""){
			$endDateValue=date("m/d/Y");
			//$startDateValue=date("m/d/Y");
		}
		
		$startDateElement = new Zend_Form_Element_Text('start_date');
		$startDateElement->setValue($startDateValue);
		$startDateElement->setAttribs(array(
				'class'=>'form-control form-control-inline date-picker',
				'placeholder'=>'Start Date'
		));
		
		$this->addElement($startDateElement);
		$endDateElement = new Zend_Form_Element_Text('end_date');
		
		$endDateElement->setValue($endDateValue);
		$this->addElement($endDateElement);
		$endDateElement->setAttribs(array(
				'class'=>'form-control form-control-inline date-picker'
		));
		
		$options = $db->getAllLocation(1);
		$locationID = new Zend_Form_Element_Select('branch_id');
		$locationID ->setAttribs(array('class'=>'validate[required] form-control select2me'));
		$locationID->setMultiOptions($options);
		$locationID->setattribs(array());
		$locationID->setValue($request->getParam('branch_id'));
		$this->addElement($locationID);
		
		
	  
		$statusCOValue=4;
		$statusCOValue = $request->getParam('purchase_status');
		$optionsCOStatus=array(''=>$tr->translate('SELECT_STATUS'),0=>$tr->translate('NOT_YET_APPROVE'),1=>$tr->translate('APPROVE'),2=>$tr->translate('REJECT'),);
		$statusCO=new Zend_Form_Element_Select('purchase_status');
		$statusCO->setMultiOptions($optionsCOStatus);
		$statusCO->setattribs(array(
				'id'=>'status',
				'class'=>'form-control select2me'
		));
		
		$statusCO->setValue($statusCOValue);
		$this->addElement($statusCO);
		
		$optexpense = $db->getAllExpense(1);
		$title = new Zend_Form_Element_Select('title');
		$title->setAttribs(array(
				'class'=>' form-control select2me',
				'onchange'=>'showexpense();'
		));
		$title->setMultiOptions($optexpense);
		$valuetitle = $request->getParam('title');
		$title->setValue($valuetitle);
		$this->addElement($title);
		
		$optincome=$db->getAllincome(1);
		$title_in = new Zend_Form_Element_Select('title_in');
		$title_in->setAttribs(array(
				'class'=>' form-control select2me',
				'onchange'=>'showincome();'
		));
		$title_in->setMultiOptions($optincome);
		$valuetitle_in=$request->getParam('title');
		$title_in->setValue($valuetitle_in);
		$this->addElement($title_in);
	}
}

