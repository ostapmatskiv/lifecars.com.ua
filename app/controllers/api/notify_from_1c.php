<?php

/**
 * for adatrade
 * notify success from 1c
 */
class notify_from_1c extends Controller
{
	
	function index()
	{
		$alias_table = ['cart' => 's_cart'];
		foreach ($alias_table as $alias => $table) {
			if($value = $this->data->get($alias))
				echo "ok: cart ".$value;
		}
	}


}

?>