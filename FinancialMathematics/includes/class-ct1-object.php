<?php   
/**
 * CT1_Object class
 *
 * @package    CT1
 * @author     Owen Kellie-Smith
 */


/**
 * CT1 Object class
 *
 * @package    CT1
 */
abstract class CT1_Object {

    /**
     * List validation constraints suitable for use with PEAR::Validate
     *
     * @return array
     *
     * @access public
     */
	public function get_valid_options(){ return array(); }

    /**
     * List defining parameter keys, descriptions, labels of object
     *
     * @return array
     *
     * @access public
     */
	public function get_parameters(){ return array(); }

    /**
     * Get validation result (list of parameter keys with boolean values)
     *
     * @param object $candidate  Object to test
     * @return array
     *
     * @access public
     */
	public function get_validation($candidate){
		$v = new Validate();
		$options =  $this->get_valid_options();
		$ret = $v->multiple($candidate, $options);
		return $ret;
	}

    /**
     * List values of defining parameter keys
     *
     * @return array
     *
     * @access public
     */
	public function get_values(){ return array(); }
		
    /**
     * List displayable labels of object
     *
     * @return array
     *
     * @access public
     */
	public function get_labels(){ return array(); }
					
} // end of class

