<?php   
/**
 * CT1_Interest_Format class
 *
 * @package    CT1
 * @author     Owen Kellie-Smith
 */

require_once 'class-ct1-object.php';

class CT1_Interest_Format extends CT1_Object{

    /**
     * Frequency (instalments per year)
     *
     * @access protected
     * @var    number
     */
	protected $m = 1;

    /**
     * Timing flag (true means in advance)
     *
     * @access protected
     * @var    boolean 
     */
	protected $advance = false;

    /**
     * List defining parameter keys, descriptions, labels of object
     *
     * @return array
     *
     * @access public
     */
	public function get_valid_options(){ 
		$r = parent::get_valid_options();
		$r['m'] = array(
							'type'=>'number',
							'decimal'=>'.',
							'min'=>0.00001,
						);
		$r['advance'] = array(
							'type'=>'boolean',
						);
		return $r; 
	}

    /**
     * List defining parameter keys, descriptions, labels of object
     *
     * @return array
     *
     * @access public
     */
	public function get_parameters(){ 
		$r = parent::get_parameters();
		$r['m'] = array(
				'name'=>'m',
				'label'=>wfMessage( 'fm-label-frequency-instalment')->text(),
				);
		$r['advance'] = array(
				'name'=>'advance',
				'label'=>wfMessage( 'fm-label-advanced')->text() ,
				);
		return $r; 
	}

    /**
     * List values of defining parameter keys
     *
     * @return array
     *
     * @access public
     */
	public function get_values(){ 
		$r = parent::get_values();
		$r['m'] = $this->get_m();
		$r['advance'] = $this->get_advance();
		return $r; 
	}
		
	public function __construct( $m=1, $advance=false ){
	  $this->set_m($m);
	  $this->set_advance($advance);
	}

	public function get_m(){
		return $this->m;
	}

	public function set_m($m){
	  $candidate = array('m'=>$m);
	  $valid = $this->get_validation($candidate);
		if ($valid['m']) $this->m = $m;
	}

	public function get_advance(){
		return $this->advance;
	}
 
	protected function my_bool($b){
		if ('on' == $b) 
			return true;
		if ('1' == $b) 
			return true;
		if (1 == $b) 
			return true;
		return false;
	}

	public function set_advance($b){
		if ('on' == $b) $b = true;
		if ('1' == $b) $b = true;
		if (1 == $b) $b = true;
		if (is_bool($b)) $this->advance = $b;
	}

	public function equals($f){
		if(!($f instanceof CT1_Interest_Format))        return false;
		if( $f->get_m()       != $this->get_m()       ) return false;
		if( $f->get_advance() != $this->get_advance() ) return false;
		return true;
	}

	public function get_description(){
		if ($this->is_continuous()) return wfMessage( 'fm-interest-continuous')->text();
		if ($this->advance) $out =  wfMessage( 'fm-discount-rate')->text();
		else $out = wfMessage( 'fm-interest-rate')->text();
		if (1!=$this->m) $out.=" " . wfMessage( 'fm-convertible')->text(). " " . $this->m . wfMessage( 'fm-times-per-year')->text();
		return $out;
	}

	public function get_label(){
		return $this->label_interest_format();
	}

	protected function label_interest_format(){
		if ($this->is_continuous()) $return = "\\delta";
		else{
			if ($this->advance) $out="d";
			else $out="i";
			if (1!=$this->m) $out.="^{(" . $this->m . ")}";
			$return = $out;
		}
		return $return;
	}

	public function get_labels(){
		$labels = parent::get_labels();
		$labels['CT1_Interest_Format'] = $this->label_interest_format();
		return $labels;
	}

	protected function is_continuous(){
		$m_continuous = 366;
		if ($this->m > $m_continuous) return true;
		return false;
	}

	public function set_from_input($_INPUT = array(), $pre = ''){
		try{
			$this->set_m(	$_INPUT[$pre. 'm'] );
			$this->set_advance($_INPUT[$pre . 'advance']);
			return true;
		}
		catch( Exception $e ){ 
			throw new Exception( wfMessage( 'fm-exception-in') . " " . __FILE__ . ": " . $e->getMessage() );
		}
	}

  
} // end of class


//$ir = new CT1_Interest_Format();
//$ir->set_advance(true);
//$ir->set_advance(false);
//	print_r( $ir->get_values() );
//if(	$ir->get_advance() ) print_r( "true");
//else print_r("false");
