<?php

class CT1_Spot_Rate_Test extends PHPUnit_Framework_TestCase
{
  private $sr;

  public function setup(){
	  $_INPUT =  Array ( 0 => Array ( 'delta' => 0.01, 'effective_time' => 1 ), 
	  1 => Array ( 'delta' => 0.02, 'effective_time' => 2 ) 
		);
    $this->sr = new CT1_Spot_Rates();
    $this->sr->set_from_input($_INPUT, $pre = '');
  }
  public function tearDown(){
		$this->sr = null;
	}
  
  public function test_forward_rates()
  {
	  $fr=$this->sr->get_forward_rates();
	  $myfr = $fr-> get_values();
	  $f01=$myfr['0,1'];
	  $f12=$myfr['1,2'];
    $this->assertTrue( abs($f01['delta'] - 0.01) < 0.000001);
    $this->assertTrue( abs($f12['delta'] - (2*0.02-0.01)) < 0.000001);
  }  

  public function test_concepts()
  {
	  $x = new CT1_Concept_All();
		$c = $x->get_controller( array( 'concept'=>'concept_spot_rates' ) );
	  $this->assertTrue( isset($c['output']['unrendered']['forms']) ) ;
  }  

  public function test_add_spot_rate()
  {
	  $x = new CT1_Concept_All();
		$c = $x->get_controller( array(
			'request' => 'add_spot_rate',
			'i_effective' => .1,
    	'effective_time' => 1));
	  $this->assertTrue( isset($c['output']['unrendered']['forms']) ) ;
	  $this->assertTrue( isset($c['output']['unrendered']['table']) ) ;
	}

  public function test_explain_par()
  {
	  $x = new CT1_Concept_All();
		$c = $x->get_controller( array(
			'request' => 'explain_par',
    	'par_term' => 1,
			'CT1_Spot_Rates' => Array
        (
            '0' => Array
                (
                    'delta' => 0.095310179804325,
                    'effective_time' => 1
                )

        )));
	  $this->assertTrue( isset($c['output']['unrendered']['forms']) ) ;
	  $this->assertTrue( isset($c['output']['unrendered']['table']) ) ;
	  $this->assertTrue( isset($c['output']['unrendered']['formulae']) ) ;
	}

  public function test_view_spotrates()
  {
	  $x = new CT1_Concept_All();
		$c = $x->get_controller( array(
    'request' => 'view_spotrates',
    'CT1_Spot_Rates' => Array
        (
            '0' => Array
                (
                    'delta' => 0.18232155679395,
                    'effective_time' => 2
                ),

            '1' => Array
                (
                    'delta' => 0.18232155679395,
                    'effective_time' => 4
                )

        )));
	  $this->assertTrue( isset($c['output']['unrendered']['forms']) ) ;
	  $this->assertTrue( isset($c['output']['unrendered']['table']) ) ;
	}

  public function test_xml_GIVES_SAME_result()
  {
	  $x = new CT1_Concept_All();
	$c = $x->get_controller( array(
			'CT1_Spot_Rates'=>array(array('delta'=>0.095310179804325,'effective_time'=>1)),
			'request'=>'explain_forward',
			'forward_start_time'=>0,
			'forward_end_time'=>1,
		));
	  $original_formulae = $c['output']['unrendered']['formulae'];
		$c_forms = $c['output']['unrendered']['xml-form']['forms'];
		$produced_xml ='';
		foreach ($c_forms as $f){
			if ('process_xml'==$f['content']['request']){
				$produced_xml = $f['content']['values']['xml'];
			}
		}
//    $this->assertEquals( 'some-stuff-just-to-show-whats-there',$produced_xml) ;  
		$XML = $produced_xml;
	  $x = new CT1_Concept_All();
		$c = $x->get_controller( array( 'request'=>'process_xml', 'xml'=>$XML ));
	  $processed_formulae = null;
		if (isset($c['output']['unrendered']['formulae'])){
		  $processed_formulae = $c['output']['unrendered']['formulae'];
		}
	  $this->assertEquals( $original_formulae, $processed_formulae) ;

  }  


}
