<?php 
class Product_Form_FrmBlock extends Zend_Form
{
	public function init()
    {
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    	$request=Zend_Controller_Front::getInstance()->getRequest();
	}
	/////////////	Form Product		/////////////////
	public function Block($data=null){
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		
		$block_name = new Zend_Form_Element_Text('block_name');
		$block_name->setAttribs(array(
				'class'=>'form-control',
				//'required'=>'required',
				'id'=>'block_name'
		));
		
		$code = new Zend_Form_Element_Text("code");
		$code->setAttribs(array(
				'class'=>'form-control',
				'id'=>'code'
		));
		
		$ph_nunber = new Zend_Form_Element_text("ph_number");
		$ph_nunber -> setAttribs(array(
				'class'=>'form-control',
				'id'=>'ph_number'
		));
		
		$addres = new Zend_Form_Element_Textarea("rmark");
		$addres->setAttribs(array(
				'class'=>'form-control',
				'style'=>'height:59px',
				'id'=>'rmark'
		));
		 
		$contact_name = new Zend_Form_Element_Text("contact_name");
		$contact_name->setAttribs(array(
				'class'=>'form-control',
				'id'=>'contact_name',
		));
		 
		$status = new Zend_Form_Element_Select("status");
		$status->setAttribs(array(
				'class'=>'form-control',
				'id'=>'status',
				
		));
		$opt = array('1'=>$tr->translate("ACTIVE"),'0'=>$tr->translate("DEACTIVE"));
		$status->setMultiOptions($opt);
		
		$branch_name = new Zend_Form_Element_Select("branch_name");
		$branch_name ->setAttribs(array(
				'class' => 'form-control',
				'id'=>'branch_name',
		));
		$db = new Product_Model_DbTable_DbBlock();
		$row = $db -> getBranchtitle();
		$opt = array('-1'=>$tr->translate("SELECT_BRANCH"));
		foreach($row as $rs){
		$opt[$rs['id']]= $rs['name'];
		}
		$branch_name -> setMultiOptions($opt);
		
		if($data != null){
			$branch_name->setValue($data["branch_id"]);
			$contact_name->setValue($data["contact_name"]);
			$block_name->setValue($data["block_name"]);
			$ph_nunber->setValue($data["phone"]);
			$addres->setValue($data["remark"]);
			$code->setValue($data["code"]);
		}
			
		$this->addElements(array($block_name,$code,$ph_nunber,$addres,$contact_name,$contact_name,$status ,$branch_name));
		return $this;
	}
}