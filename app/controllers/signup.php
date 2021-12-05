<?php

class Signup extends Controller {

    private $errors = array();
    private $name = 'first_name, last_name'; // 'name'||'first_name, last_name' ім'я в одній змінній чи 2-х
    public $additionall = false; //array('address'); // false додаткові поля при реєстрації. Згодом можна використовувати у ідентифікації, тощо
    private $new_user_type = 4; // Ід типу новозареєстрованого користувача

    function _remap($method, $data = array())
    {
    	if(isset($_SESSION['option']->new_user_type))
    		$this->new_user_type = $_SESSION['option']->new_user_type;
        if (method_exists($this, $method)) {
        	if(empty($data)) $data = null;
            return $this->$method($data);
        } else {
        	$this->index($method);
        }
    }

    public function index()
    {
    	if(!$this->userIs())
    	{
    		if($_SESSION['option']->userSignUp)
    		{
    			$this->wl_alias_model->setContent(0, 202);
    			$this->load->library('facebook');
    			$this->load->library('googlesignin');
	        	if($this->googlesignin->clientId)
	        	{
	        		if(empty($_SESSION['alias']->meta))
	        			$_SESSION['alias']->meta = '<meta name="google-signin-client_id" content="'.$this->googlesignin->clientId.'">';
	        		else
	        			$_SESSION['alias']->meta .= ' <meta name="google-signin-client_id" content="'.$this->googlesignin->clientId.'">';
	        	}
    			if(isset($_GET['old']))
    			{
		    		if($_SESSION['option']->facebook_initialise)
		        		$this->load->view('profile/signup/index_view');
		        	else
		        		$this->load->view('profile/signup/email_view');
		        }
		        else
		        	$this->load->page_view('profile/login_view');
    		}
    		else
    			$this->load->page_404(false);
    	}
        else
        	$this->redirect('profile');
    }

    // public function email()
    // {
    // 	if(!$this->userIs())
    // 	{
    // 		$this->wl_alias_model->setContent();
    // 		$this->load->library('facebook');
    //     	$this->load->view('profile/signup/email_view');
    // 	}
    //     else
    //     	$this->redirect('profile');
    // }

    public function process()
    {
		if(!$this->userIs())
		{
			$_SESSION['notify'] = new stdClass();

	    	// $this->load->library('recaptcha');
			// if($this->recaptcha->check($this->data->post('g-recaptcha-response')) == false)
			// {
			// 	$_SESSION['notify']->errors = $this->text('Заповніть "Я не робот"');
			// }
			// else
			// {
		        $this->load->library('validator');
		        if($this->name == 'name')
					$this->validator->setRules($this->text("Ім'я"), $this->data->post('name'), 'required');
				else
				{
					$this->validator->setRules($this->text("Ім'я"), $this->data->post('first_name'), 'uk_letters|required');
					$this->validator->setRules($this->text("Прізвище"), $this->data->post('last_name'), 'uk_letters|required');
				}
				// $email = '';
		  //   	if($email = $this->data->post('email'))
		  //   		$email = strtolower($email);
				// $this->validator->setRules('E-mail', $email, 'required|email');
				
					$phone = $this->data->post('phone');
					$this->validator->setRules($this->text('Номер телефону'), $phone, 'phone|required');
					$this->validator->setRules($this->text('Код з СМС'), $this->data->post('code'), 'int|required');
					if($phone = $this->validator->getPhone($phone))
					{
						$code = $_SESSION['signup'][$phone] ?? '';
						$this->validator->setRules($this->text('Код з СМС'), $code, 'int|required');	
						$this->validator->equal($this->data->post('code'), $code, $this->text('Помилка СМС коду! Перевірте дані'));
					}
				// $this->validator->setRules($this->text('Пароль'), $this->data->post('password'), 'required|5..20');
				// $this->validator->equal($this->data->post('password'), $this->data->post('re-password'));

		        if($this->validator->run())
		        {
		        	unset($_SESSION['signup']);
		        	
		            $this->load->model('wl_user_model');
		            $info['email'] = '';
			    	$info['phone'] = $phone;
			    	$info['name'] = $this->data->post('first_name') .' '. $this->data->post('last_name');
			    	// $info['password'] = $_POST['password'];
			    	$info['status'] = 1;
			    	$info['photo'] = '';
			    	$additionall = array();
			    	if(!empty($this->additionall))
					{
						foreach ($this->additionall as $key) {
							if($value = $this->data->post($key))
							{
								if($key == 'phone')
									$value = $this->validator->getPhone($value);
								$additionall[$key] = $value;
							}
						}
					}

	                if($user = $this->wl_user_model->add($info, $additionall, $this->new_user_type, false))
	                {
	                	// $this->load->library('mail');
						// $info['auth_id'] = $user->auth_id;
						// if($this->mail->sendTemplate('signup/user_signup', $user->email, $info))
						// {
						// 	$_SESSION['notify']->title = $this->text('Реєстрація пройшла успішно!');
						// 	$_SESSION['notify']->success = $this->text('На поштову скриньку відправлено лист з <b>кодом підтвердження</b> та подальшими інструкціями. <br><br> <b>УВАГА!</b> Лист може знаходитися у папці <b>СПАМ!</b>');
						// }
						// else 
						// 	$_SESSION['notify']->errors = $this->text('Виникла помилка при додаванні нового користувача');
						$this->wl_user_model->setSession($user, false);
						$_SESSION['notify']->success = $this->text('Реєстрація пройшла успішно!');
						$this->redirect('profile/edit');
	                }
	                else
	                	$_SESSION['notify']->errors = $this->wl_user_model->user_errors;
		        }
		        else
		            $_SESSION['notify']->errors = '<ul>'.$this->validator->getErrors('<li>', '</li>').'</ul>';
		    // }
	        $this->redirect('signup');
		}
		$this->redirect('profile');
    }

	public function confirmed()
	{
		if($this->userIs() && isset($_POST['code']))
		{
			$_SESSION['notify'] = new stdClass();
			$this->load->model('wl_user_model');
			if($status = $this->wl_user_model->checkConfirmed($_SESSION['user']->email, $this->data->post('code')))
			{
				$_SESSION['notify']->success = $this->text('Підтвердження пройшло успішно!');
				$this->redirect('profile/edit');
			}
			else
			{
				$_SESSION['notify']->errors = $this->text('Код підтвердження не співпав!');
				$this->redirect();
			}
		}
		$this->load->page_404();
	}

	public function get_confirmed()
	{
		$_SESSION['notify'] = new stdClass();
		if (isset($_GET['code']) and isset($_GET['email']))
		{
			$this->load->model('wl_user_model');
			if ($status = $this->wl_user_model->checkConfirmed($this->data->get('email'), $this->data->get('code')))
			{
				$_SESSION['notify']->success = $this->text('Підтвердження пройшло успішно!');
				$this->redirect('profile/edit');
			}
			else
			{
				$_SESSION['notify']->errors = $this->text('Код підтвердження не співпав!');
				$this->redirect('login');
			}
		}
		$this->load->page_404(false);
	}

	// public function check_email()
	// {
	// 	$this->load->model('wl_user_model');
	// 	$res['result'] = $this->wl_user_model->userExists($this->data->post('email'));
	// 	$res['message'] = $this->wl_user_model->user_errors;
	// 	$this->load->json($res);
	// }

	public function facebook()
	{
		$this->load->library('facebook');

		$accessToken = $this->data->post('accessToken');
		$user_profile = null;

		if ($accessToken)
		{
			$this->facebook->setAccessToken($accessToken);

			try {
				$user_profile = $this->facebook->api('/me?fields=email,id,name,link');
			} catch (FacebookApiException $e) {
				error_log($e);
				$user_profile = null;
			}
		}

		if ($user_profile)
		{
			if(isset($user_profile['email'])){
				$this->load->model('wl_user_model');

				$res = array('result' => false);

				$info['email'] = $user_profile['email'];
			    $info['name'] = $_SESSION['facebook_name'] = $user_profile['name'];
			    $info['password'] = 'facebook';
			    $info['photo'] = '';
			    $additionall['facebook'] = $user_profile['id'];
			    if(!empty($user_profile['link']))
			    	$additionall['facebook_link'] = $user_profile['link'];
				if($user = $this->wl_user_model->add($info, $additionall, $this->new_user_type, false, 'by facebook'))
				{
					$this->wl_user_model->setSession($user);
					if(empty($_SESSION['user']->photo))
					{
						$facebookPhotoLink = 'https://graph.facebook.com/'.$user_profile['id'].'/picture?width=9999';
						$this->wl_user_model->setPhotoByLink($facebookPhotoLink);
					}

					if(!isset($_POST['ajax']))
					{
						$this->redirect($user->load);
						exit;
					}
					else
					{
						$res['result'] = true;
						$this->load->json($res);
					}
				}
			}

			$this->redirect('login/facebook');
		}
		else
		{
			$loginUrl = $this->facebook->getLoginUrl();
			header('Location: '.$loginUrl);
			exit;
		}
	}

	function check_phone()
	{
		$res = array('status' => false, 'message' => 'Введіть коректний номер телефону');
		if($phone = $this->data->post('phone'))
		{
			$this->load->library('validator');
			if($phone = $this->validator->getPhone($phone))
			{
				$this->load->model('wl_user_model');
				if($userExists = $this->wl_user_model->userExists($phone)) {
					$res['message'] = $this->wl_user_model->user_errors;
				} else {
					$res = array('status' => true);
				}
			}
		}
		$this->load->json($res);
	}

	public function send_phone_code()
	{
		$res = array('status' => false, 'message' => 'Введіть коректний номер телефону');
		if($phone = $this->data->post('phone'))
		{
			$this->load->library('validator');
			if($phone = $this->validator->getPhone($phone))
			{
				if(empty($_SESSION['signup'][$phone])) {
					$_SESSION['signup'][$phone] = rand(1000 , 9999);
				}
	            $this->load->library('turbosms');
	            if($this->turbosms->send($phone, $_SESSION['signup'][$phone]))
	            	$res = array('status' => true, 'code' => $_SESSION['signup'][$phone] + 1);
			}
		}
		$this->load->json($res);
	}

	public function check_phone_code()
	{
		$res = array('status' => false, 'message' => 'Введіть коректний номер телефону');
		if($phone = $this->data->post('phone'))
		{
			$this->load->library('validator');
			if($phone = $this->validator->getPhone($phone))
			{
				if(!empty($_SESSION['signup'][$phone])) {
					if($_SESSION['signup'][$phone] == $this->data->post('code')) {
						$res = array('status' => true);
					} else {
						$res['message'] = 'Помилка коду';
					}
				} else {
					$res['message'] = 'Помилка коду';
				}
			}
		}
		$this->load->json($res);
	}

}

?>