<?php 

/*
 * Google Recaptcha
 *
 * v1.1 09.01.2020 	add callback and expired_callback
 * v1.0				base
 *
 */

class Recaptcha {
	
	private $secret = false;
	public $public = false;

	function __construct($data)
	{
		if(isset($data['secret']))
		{
			$this->public = $data['public'];
			$this->secret = $data['secret'];
		}
	}


    public function check($response)
    {
    	if($this->secret)
    	{
	    	$siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?";

	    	$callback = file_get_contents($siteVerifyUrl.'secret='.$this->secret.'&response='.$response);
	    	$callback = json_decode($callback);
	    	if($callback->success == true)
	    		return true;
	    }
	    return false;
    }

    public function form($callback = false, $expired_callback = false)
    {
    	$callback = ($callback) ? 'data-callback="'.$callback.'"' : '';
    	$expired_callback = ($expired_callback) ? 'data-expired-callback="'.$expired_callback.'"' : '';
    	echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
    	echo '<div class="g-recaptcha" data-sitekey="'.$this->public.'" '.$callback.' '.$expired_callback.'></div>';
    }
}

/* use js:
var recaptchaVerifyCallback = function(response) {
	$('#colToUs form button').attr('disabled', false);
	$('#colToUs form button').attr('title', false);
};
var recaptchaExpiredCallback = function(response) {
	$('#colToUs form button').attr('disabled', true);
	$('#colToUs form button').attr('title', 'Заповніть "Я не робот"');
};
*/
?>