<?php 

/*
 * Google Recaptcha
 *
 * v2 19.12.2020 	support recaptcha v3
 * v1.1 09.01.2020 	add callback and expired_callback
 * v1.0				base
 *
 */

class Recaptcha {
	
	public $initJs = true;
	private $secret = false;
	public $public = false;
	private $secret_v3 = false;
	public $public_v3 = false;

	function __construct($data)
	{
		if(isset($data['secret']))
		{
			$this->public = $data['public'];
			$this->secret = $data['secret'];
		}
		if(isset($data['secret_v3']))
		{
			$this->public_v3 = $data['public_v3'];
			$this->secret_v3 = $data['secret_v3'];
		}
	}


    public function check($response)
    {
    	$secret = $this->secret;
    	if($this->secret_v3)
    		$secret = $this->secret_v3;
    	if($secret)
    	{
	    	$siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?";

	    	$callback = file_get_contents($siteVerifyUrl.'secret='.$secret.'&response='.$response);
	    	$callback = json_decode($callback);
	    	if($callback->success == true)
	    		return true;
	    }
	    return false;
    }

    public function form($callback = false, $expired_callback = false)
    {
    	if($this->secret)
    	{
	    	$callback = ($callback) ? 'data-callback="'.$callback.'"' : '';
	    	$expired_callback = ($expired_callback) ? 'data-expired-callback="'.$expired_callback.'"' : '';
	    	if($this->initJs)
	    	{
	    		echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
	    		$this->initJs = false;
	    	}
	    	echo '<div class="g-recaptcha" data-sitekey="'.$this->public.'" '.$callback.' '.$expired_callback.'></div>';
	    }
	    elseif ($this->secret_v3)
	    	$this->form_v3($callback, $expired_callback);
    }

    // https://developers.google.com/recaptcha/docs/v3
    public function form_v3($btnText = "Submit", $formId = false, $btnClass = '', $callback = false)
    {
    	if(!$formId)
    	{
    		echo "form Id param required / recaptcha v3";
    		return false;
    	}
    	if(!$callback)
    	{
    		$callback = 'recaptchaSubmit'.ucfirst($formId);
    		echo "<script> function {$callback}(token) {
		       	var canSubmit = true;
		       	$('#{$formId}').find('input,textarea,select').filter('[required]:visible').each(function(){
		       		if(!$(this).val())
		       		{
		       			if(canSubmit)
		       				$(this).focus();
		       			$(this).addClass('danger').change(function () { $(this).removeClass('danger'); });
		       			canSubmit = false;
		       		}
		       	});
		        if(canSubmit)
		        {
		        	$('#divLoading').addClass('show');
		          	{$formId}.submit();
		        }
	       } </script>";
    	}
    	if($this->initJs)
    	{
    		echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
    		$this->initJs = false;
    	}
    	echo '<button class="g-recaptcha '.$btnClass.'" 
	         data-sitekey="'.$this->public_v3.'" 
	         data-callback="'.$callback.'" 
        	 data-action="submit">'.$btnText.'</button>';
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