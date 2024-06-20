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
        /* Array
        (
            [0] => Array
                (
                    [order_id] => 3914
                    [ttn] => 20450938610386
                    [np_status] => Прибув у відділення
                )

        ) */

        $content = file_get_contents('php://input');
        if(empty($content)) {
            echo 'ERROR: empty input content';
            return;
        }
        $content_json = json_decode($content, true);
        if(empty($content_json)) {
            echo 'ERROR: empty content_json';
            return;
        }
        // echo '<pre>';
        // print_r($content);
        // print_r($content_json);
        // echo '</pre>';

        foreach ($content_json as $row) {
            if($order_id = $row['order_id']) {
                $data = ['ttn' => $row['ttn'], 'np_status' => $row['np_status']];
                if($order = $this->db->select('s_cart', 'ttn, np_status', $order_id)->get()) {
                    $log = [];
                    foreach ($data as $key => $value) {
                        if($order->$key != $value) {
                            $log[] = "{$key} => {$value}";
                        }
                    }
                    if(!empty($log)) {
                        $this->db->updateRow('s_cart', $data, $order_id);
                        $this->db->insertRow('s_cart_history', ['cart' => $order_id, 'status' => 0, 'show' => 1, 'user' => 0, 'comment' => implode(', ', $log), 'date' => time()]);
                    }
                    echo "OK: order {$order_id} updated. " . PHP_EOL;
                }
                else {
                    echo "ERROR: order {$order_id} not found. " . PHP_EOL;
                }
            }
        }

        $this->db->insertRow('api_cart_ttn_log', ['content' => $content, 'created_at' => time()]);
    }

}