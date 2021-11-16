<?php

/**
 * for adatrade
 */
class export_1c extends Controller
{

	private $order_currency = 'UAH';
	
	function index()
	{
		# code...
	}

	public function clients()
	{
		$type = 'json';
		if(isset($_GET['type']) && $_GET['type'] == 'xml')
			$type = 'xml';

		if($type == 'json')
		{
			$this->db->select('wl_users as u', 'id, id_1c, email, name, type', ['date_1c' => 0])
						->join('wl_user_info as i', 'value as user_phone', ['user' => '#u.id', 'field' => 'phone'])
						->join('wl_user_types as t', 'title as type_name', '#u.type');
			if ($users = $this->db->get('array'))
				foreach ($users as $user) {
					$user->id = 'life-'.$user->id;
				}
			$this->load->json($users);
		}


		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n
					<VygruzkaKlientiv>\n
						<Клиенты>\n";
		$this->db->select('wl_users as u', 'id, id_1c, email, name, type', ['date_1c' => 0])
					->join('wl_user_info as i', 'value as user_phone', ['user' => '#u.id', 'field' => 'phone'])
					->join('wl_user_types as t', 'title as type_name', '#u.type');
		if ($users = $this->db->get('array'))
		{
			foreach ($users as $user) {
				$xml .= "\t" . '<Клиент КодСайта="life-'.$user->id.'" Код="'.$user->id_1c.'">' . "\n";

				$xml .= "\t\t" . '<site_id>life-' . $user->id . '</site_id>' . "\n";
				$xml .= "\t\t" . '<name>' . $user->name . '</name>' . "\n";
				$xml .= "\t\t" . '<email>' . $user->email . '</email>' . "\n";
				$xml .= "\t\t" . '<tel>' . $user->user_phone . '</tel>' . "\n";
				$xml .= "\t\t" . '<Type_Opt>' . $user->type . '</Type_Opt>' . "\n";
				$xml .= "\t\t" . '<Type_Name>' . $user->type_name . '</Type_Name>' . "\n";

				$xml .= "\t" . '</Клиент>' . "\n";
			}
		}
		$xml .= "</Клиенты>\n
					</VygruzkaKlientiv>";
		header('Content-Type: text/xml; charset=UTF-8');
		echo $xml;
	}

	public function orders()
	{
		$in_xml = [];
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n
					<orders>\n";
		$this->db->select('s_cart as c', '*', ['1c_status' => '#!c.status'])
					->join('s_cart_shipping as sh', 'name as shipping_name', '#c.shipping_id')
					->join('s_cart_payments as p', 'name as payment_name, info as payment_info', '#c.payment_id')
					->join('s_cart_status as s', 'name as status_name', '#c.status')
					->join('wl_users as u', 'email as user_email, name as user_name, type as user_type, id_1c as user_id_1c', '#c.user')
					->join('wl_user_info as i', 'value as user_phone', ['user' => '#c.user', 'field' => 'phone'])
					->join('wl_user_types as t', 'id as user_type_id, title as type_name', '#u.type');
		if ($orders = $this->db->get('array'))
		{
			$order_keys = ['status', 'status_name', 'total', 'payed', 'comment', 'date_add', 'date_edit'];
			$user_keys = ['id_site' => 'user', 'id_1c' => 'user_id_1c', 'name' => 'user_name', 'email' => 'user_email', 'phone' => 'user_phone', 'price_type_id' => 'user_type_id', 'price_type_name' => 'type_name'];
			$shipping_keys = ['id' => 'shipping_id', 'name' => 'shipping_name', 'ttn' => 'ttn', 'info' => 'shipping_info', 'info_serialize' => 'shipping_info_serialize'];
			$payment_keys = ['id' => 'payment_id', 'name' => 'payment_name', 'info' => 'payment_info'];
			$product_keys = ['id_site' => 'product_id', 'id_1c' => 'id_1c', 'name' => 'name', 'article' => 'article_show', 'price' => 'price', 'quantity' => 'quantity', 'sum' => 'sum', 'storage_id_1c' => 'storage_id_1c', 'storage_name' => 'storage_name'];
			$orders_id = [];
			foreach ($orders as $order)
				$orders_id[] = $order->id;
			$orders_products = $this->db->select('s_cart_products as cp', '*', ['cart' => $orders_id])
											->join('s_shopshowcase_products', 'id_1c, article_show', '#cp.product_id')
											// ->join('s_shopstorage', 'id_1c as storage_id_1c, name as storage_name', '#cp.storage_alias')
											->join('wl_ntkd', 'name', ['alias' => '#cp.product_alias', 'content' => '#cp.product_id', 'language' => $_SESSION['language']])
											->get('array');
			if($orders_products)											
			foreach ($orders as $order)
			{
				if(in_array($order->id, $in_xml))
					continue;
				$in_xml[] = $order->id;

				$xml .= "\t" . '<order>' . "\n";

				$xml .= "\t\t" . '<id_site>' . $order->id . '</id_site>' . "\n";
				foreach ($order_keys as $key) {
					if(empty($order->$key))
						$order->$key = 'NULL';
					$xml .= "\t\t<{$key}>{$order->$key}</{$key}>\n";
				}
				$xml .= "\t\t" . '<currency_code>'.$this->order_currency.'</currency_code>' . "\n";
				$xml .= "\t\t" . '<currency_rate>1</currency_rate>' . "\n";

				$order->user = 'life-'.$order->user;
				$xml .= "\t\t" . '<client>' . "\n";
				foreach ($user_keys as $xml_key => $key) {
					if(empty($order->$key))
						$order->$key = 'NULL';
					$xml .= "\t\t\t<{$xml_key}>{$order->$key}</{$xml_key}>\n";
				}
				$xml .= "\t\t" . '</client>' . "\n";

				$xml .= "\t\t" . '<shipping>' . "\n";
				$order->shipping_info_serialize = $order->shipping_info;
				if($order->shipping_id > 0)
				{
					if($order->shipping_id == 1)
						$order->shipping_name = 'Нова пошта';
					else
					{
						$name = unserialize($order->shipping_name);
						$order->shipping_name = (isset($name['uk'])) ? $name['uk'] : $order->shipping_name;
					}

					if(!empty($order->shipping_info) && substr($order->shipping_info, 0, 2) == 'a:')
					{
						$info = unserialize($order->shipping_info);
						$order->shipping_info = '';
						foreach ($info as $key => $value) {
							if(!empty($value))
								$order->shipping_info .= "{$key}: {$value}; ";
						}
						$order->shipping_info = substr($order->shipping_info, 0, -2);
					}
				}
				
				foreach ($shipping_keys as $xml_key => $key) {
					if(empty($order->$key))
						$order->$key = 'NULL';
					$xml .= "\t\t\t<{$xml_key}>{$order->$key}</{$xml_key}>\n";
				}
				$xml .= "\t\t" . '</shipping>' . "\n";

				// $xml .= "\t\t" . '<payment>' . "\n";
				// if($order->payment_alias == 28)
				// {
				// 	$order->payment_name = 'LiqPay';
				// 	if($order->payment_id)
				// 		if($pay = $this->db->getAllDataById('s_liqpay', $order->payment_id))
				// 			$order->payment_info = $pay->comment;
				// }
				// foreach ($payment_keys as $xml_key => $key) {
				// 	if(empty($order->$key))
				// 		$order->$key = 'NULL';
				// 	$xml .= "\t\t\t<{$xml_key}>{$order->$key}</{$xml_key}>\n";
				// }
				// $xml .= "\t\t" . '</payment>' . "\n";

				$xml .= "\t\t" . '<products>' . "\n";
				foreach ($orders_products as $product)
				{
					if($product->cart != $order->id)
						continue;

					$product->sum = $product->price * $product->quantity;

					$xml .= "\t\t\t" . '<product>' . "\n";
					foreach ($product_keys as $xml_key => $key) {
						if(empty($product->$key))
							$product->$key = 'NULL';
						$xml .= "\t\t\t\t<{$xml_key}>{$product->$key}</{$xml_key}>\n";
					}
					$xml .= "\t\t\t" . '</product>' . "\n";
				}
				$xml .= "\t\t" . '</products>' . "\n";

				$xml .= "\t" . '</order>' . "\n";
			}
		}
		$xml .= '</orders>';

		header('Content-Type: text/xml; charset=UTF-8');
		echo $xml;
	}
}

?>