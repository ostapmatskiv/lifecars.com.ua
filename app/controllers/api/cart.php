<?php

/**
 * for lifecars.com.ua
 */
class cart extends Controller
{

    function _remap($method)
    {
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            $this->index($method);
        }
    }

    function index()
    {
        echo 'API/cart';
		// $this->redirect('/');
	}

    function ttn() {
        $content = file_get_contents('php://input');
        $content_json = json_decode($content, true);
        echo '<pre>';
        print_r($content);
        print_r($content_json);
        echo '</pre>';

        if($order_id = $this->data->post('order_id')) {
            $data = $this->data->prepare(['ttn', 'np_status']);

            if($order = $this->db->select('s_cart', 'ttn', $order_id)->get()) {
                $log = [];
                foreach ($data as $key => $value) {
                    if($order[$key] != $value) {
                        $log[] = "{$key} => {$value}";
                    }
                }
                if(!empty($log)) {
                    // $this->db->updateRow('s_cart', $data, $order_id);
                    // $this->db->insertRow('s_cart_history', ['cart' => $order_id, 'status' => 0, 'show' => 1, 'user' => 0, 'comment' => implode(', ', $log), 'date' => time()]);
                }
            }
            echo 'OK';
        }
        else {
            echo 'ERROR: order not found';
        }
    }

}