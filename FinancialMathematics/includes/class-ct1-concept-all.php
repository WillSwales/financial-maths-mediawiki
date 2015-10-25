<?php

/**
 * CT1_Concept_All class
 *
 * @package    CT1
 * @author     Owen Kellie-Smith
 */
class CT1_Concept_All extends CT1_Form{

	private $concepts;

  private $messages;

  private $tag_name="dummy_tag_set_in_ct1_concept_all";

  public function getTagName(){
		return $this->tag_name;
  }

  public function setTagName( $s ){
		$this->tag_name = $s;
  }


	public function __construct(CT1_Object $obj=null){
//		$this->set_concepts();
	}

	private function get_concept_labels(){
		$return = array();
		foreach( $this->candidate_concepts() AS $c ){
				$return = array_merge($return, $c->get_concept_label());
		}
//echo __FILE__ . " get_concept_labels " . print_r($return,1);
		return $return;
	}

	public function get_calculator( $unused ){
		$p = array('method'=> 'GET', 'submit'=>self::myMessage(  'fm-get-calculator') , self::myMessage(  'fm-select-calculator'));
		$p['select-options'] = $this->get_concept_labels() ;
		$p['select-name'] = 'concept';
		$p['select-label'] = self::myMessage(  'fm-select-calculator');
		return $p;
	}

	private function get_parameters($_INPUT){
		return; 
//		return print_r($_INPUT,1);
	}

	public function get_controller($_INPUT ){
    $return = $this->get_controller_no_xml($_INPUT );
		if (isset($return['output']['unrendered']['summary'])){
			$c = new CT1_Form_XML();
			$c->setTagName( $this->getTagName() );
			$temp = $c->get_controller( $_INPUT ); // recursive but only once - it stops being in a loop by dumping everything one layer down i.e. in ..unrenderd.xml-form,... so it doesn't set an output.unrendered.summary
		  if (isset($temp['output']['unrendered'])){
				$return['output']['unrendered']['xml-form'] = $temp['output']['unrendered'];
			}
		}
//echo __FILE__ . " get_controller return " . print_r($return,1);
		//exclude any duplicate forms;
		if (isset($return['output']['unrendered']['forms'])){
//http://stackoverflow.com/questions/307674/how-to-remove-duplicate-values-from-a-multi-dimensional-array-in-php
			$return['output']['unrendered']['forms'] = array_map("unserialize", array_unique(array_map("serialize", $return['output']['unrendered']['forms'])));
			if (isset($return['output']['unrendered']['xml-form']['forms'])){
				$return['output']['unrendered']['xml-form']['forms'] = array_map("unserialize", array_unique(array_map("serialize", $return['output']['unrendered']['xml-form']['forms'])));
				// check one by one against the regular forms
				for ($i=0; $i<count($return['output']['unrendered']['xml-form']['forms']); $i++){
					$unset_i = false;
					foreach( $return['output']['unrendered']['forms'] AS $uf ){
						if ($uf=== $return['output']['unrendered']['xml-form']['forms'][$i] ){
							$unset_i = true;
						}
					}
					if ($unset_i){
						unset($return['output']['unrendered']['xml-form']['forms'][$i]);
					}
				}
			}
		}
		return $return;
	}
	
	public function get_controller_no_xml($_INPUT ){
	$return['arrayInput']=$_INPUT;
	try{
		foreach( $this->candidate_concepts() AS $c ){
				if (isset($_INPUT['request'])){
					if ($c->get_request() == $_INPUT['request'] ){
						$return = array_merge($return, $c->get_controller( $_INPUT ));
						return $return;
					}
					if ( in_array( $_INPUT['request'], $c->get_possible_requests() ) ){
						$return = array_merge($return, $c->get_controller( $_INPUT ));
						return $return;
					}
				} // if (isset($_INPUT['request']))
				if (isset($_INPUT['concept'])){
					$temp = $c->get_concept_label();
					if ( isset( $temp[ $_INPUT['concept'] ] ) ){
						$return = array_merge($return, $c->get_controller( $_INPUT ));
						return $return;
					}
				}
			} //foreach( $this->candidate_concepts() AS $c )
		$return['output']['unrendered']['forms'][] = array(
			'content'=> $this->get_calculator( NULL ),
			'type'=>  'select'
		);
		return $return;
	}
	catch( Exception $e ){
		$return['warning']=$e->getMessage();
		return $return;
	}
}

} // end of class
