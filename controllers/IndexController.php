<?php

class OHMSExport_IndexController extends Omeka_Controller_AbstractActionController
{
    public function indexAction()
    {
    	$form = new Zend_Form;
		$form->setAction(url(array('module'=>'ohms-export', 'controller'=>'export', 'action'=>'ohms'), 'default'))
			 ->setMethod('post');
		
		$element = new Zend_Form_Element_File('ohms');

		$form->setAttrib('enctype', 'multipart/form-data');
		$form->addElement($element, 'ohms');

		$this->view->assign('form', $form);
    }
}
