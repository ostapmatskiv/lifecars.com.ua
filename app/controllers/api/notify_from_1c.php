<?php

/**
 * for adatrade
 * notify success from 1c
 */
class notify_from_1c extends Controller
{
	
	function index()
	{
		$time = time();
		$alias_table = ['cart' => 's_cart', 'client' => 'wl_users'];
		foreach ($alias_table as $alias => $table) {
			if($row_id = $this->data->get($alias))
			{
				$data = ['date_1c' => $time];
				if($alias == 'cart')
				{
					if($cart = $this->db->getAllDataById($table, $row_id))
						$data['1c_status'] = $cart->status;
					else
					{
						echo "error: cart #".$row_id.' not found';
						exit;
					}
				}
				if($this->db->updateRow($table, $data, $row_id))
					echo "ok: {$alias} #".$row_id;
			}
		}
	}


}

?>