<?php

class promo_send extends Controller
{

    function index() {
        $from_date = time() - 86400 * 14;
        if($orders = $this->db->select('s_cart as c', 'id', ['promo_send_at' => 0, 'status' => 6, 'date_add' => '>=' . $from_date])
                                ->join('wl_users', 'name, phone', '#c.user')
                                ->get('array')
        ) {
            if($bonus = $this->db->select('s_cart_bonus', 'id, code, info', ['status' => 1])->limit(1)->get()) {
                $this->load->library('turbosms');
                $bonus->info = str_replace('code', $bonus->code, $bonus->info);
                // echo $bonus->info;
                foreach($orders as $order) {
                    if($this->turbosms->send($order->phone, $bonus->info)) {
                        $this->db->updateRow('s_cart', ['promo_send_at' => time()], $order->id);
                        echo "Sent to client from order #{$order->id} <br>";
                    }
                }
            }
            else {
                echo "<h1>Active bonus codes not found</h1>";
                $this->db->updateRow('s_cart', ['promo_send_at' => -1], ['promo_send_at' => 0]);
            }
        }
        else {
            echo "<h1>Orders not found</h1>";
        }
    }

}