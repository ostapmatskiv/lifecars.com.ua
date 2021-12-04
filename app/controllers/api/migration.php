<?php

class migration extends Controller {

    function _remap($method)
    {
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            $this->index($method);
        }
    }

    function user_phone()
    {
		if($users = $this->db->getAllDataByFieldInArray('wl_user_info', ['field' => 'phone'])) {
			// echo "<pre>";
			// print_r($users);
			foreach($users as $user) {
				if(substr($user->value, 0, 3) == '380') {
					$this->db->updateRow('wl_users', ['phone' => $user->value], $user->user);
				}
			}
		}
	}

}