<?php

require_once 'class-ct1-annuity-escalating.php';
require_once 'class-ct1-form.php';
require_once 'class-ct1-render.php';

class CT1_Concept_Annuity extends CT1_Form{

public function __construct(CT1_Object $obj=null){
	if (null === $obj) $obj = new CT1_Annuity_Escalating();
	parent::__construct($obj);
	$this->set_request( 'get_annuity_escalating' );
}

public function get_solution(){
	$render = new CT1_Render();
	$return = $render->get_render_latex($this->obj->explain_annuity_certain());
	return $return;
} 
	
public function get_interest_rate(){
	$render = new CT1_Render();
	$return = $render->get_render_latex($this->obj->explain_interest_rate_for_value());
	return $return;
}

public function get_calculator($parameters){
	$p = array('exclude'=>$parameters,'request'=> $this->get_request(), 'submit'=>wfMessage( 'fm-calculate')->text(), 'introduction' => wfMessage( 'fm-intro-annuity-certain')->text() );
	$c = parent::get_calculator($p);
	$c['values']['value'] = NULL;
	return $c;
}

public function get_controller($_INPUT ){
  $return=array();
	if (isset($_INPUT['request'])){
		if ($this->get_request() == $_INPUT['request']){
			if ($this->set_annuity($_INPUT)){
				if (empty( $_INPUT['value'] ) ){
					$return['formulae']= $this->get_solution();
					return $return;
				} else {
					$return['formulae']= $this->get_interest_rate();
					return $return;
				}
			} else {
				$return['warning']=wfMessage( 'fm-exception-setting-annuity')->text();
				return $return;
			}
		}
	}
	else{
		$render = new CT1_Render();
		$return['form']= $render->get_render_form($this->get_calculator(array("delta", "escalation_delta")));
    		return $return;
	}
  return $return;
}

public function set_annuity($_INPUT = array()){
	$this->set_received_input($_INPUT);
	$this->obj->set_from_input($_INPUT);
	return ($this->obj->set_from_input($_INPUT));
}

} // end of class


