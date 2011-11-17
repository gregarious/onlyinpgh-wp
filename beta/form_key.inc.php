<?php  
class formKey
{  
    private $formKey;  
    private $prev_formKey;  

    public function __construct() {
    	if(isset($_SESSION['form_key'])) {  
	        $this->prev_formKey = $_SESSION['form_key'];  
	    }  
    }

    // returns true if form key exists and matches set one for this session
	public function validate() {  
		return $_POST['form_key'] == $this->prev_formKey;
	}  

    private function generateKey() {  
	    $ip = $_SERVER['REMOTE_ADDR'];  
	    $uniqid = uniqid(mt_rand(), true);  
	  
	    //Hash both the ip address, uniqid, and something of a salt
	    return md5($ip . $uniqid . "SALTY!");  
	}
	
	public function outputKey() {
		$this->formKey = $this->generateKey();
		$_SESSION['form_key'] = $this->formKey;
		echo "<input type='hidden' name='form_key' id='form_key' value='".$this->formKey."'>";
	}

}  
?>