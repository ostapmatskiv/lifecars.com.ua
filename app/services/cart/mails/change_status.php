<?php

// --- cart manager change status. Info mail to client --- //

/* Вхідні дані
   data[
	id - номер замовлення
	user_name - ім'я користувача
	user_email - email користувача
	user_phone - код підтвердження
   ]
*/


$subject = $data['status_name'].': Замовлення # '.$data['id'].' від '.$data['date'] . ' '.SITE_NAME;
$message = '<html><head><title>Замовлення #'.$data['id']. ' '.SITE_NAME.'</title></head><body><p>Доброго дня <b>'.$data['user_name'].'</b>!</p><h1>Замовлення #'.$data['id'].' від '.$data['date'].'</h1><p>Оновлено інформацію по Вашому замовлення:</p>';

$message .= '<table align="left" width="100%" cellpadding="5" cellspacing="5" border="0" style="border-collapse:collapse;margin-bottom: 15px">
	<tbody>
		<tr>
			<td align="left" width="170">Поточний статус замовлення</td>
			<td align="left">
				<b>'.$data['status_name'].' від '.$data['date'].'<br> '.$data['comment'].'</b>
			</td>
		</tr>
		<tr>
			<td align="left" width="170">Покупець</td>
			<td align="left">
				<b>'.$data['user_name'].'</b><br> '.$data['user_email'].'<br> '.$data['user_phone'].'
			</td>
		</tr>';
		if(!empty($data['delivery']))
			$message .= '<tr>
				<td align="left" width="170" style="border-top:1px solid #f4f4f4">Доставка:</td>
				<td align="left" style="border-top:1px solid #f4f4f4">'.$data['delivery'].'</td>
			</tr>';
		if(!empty($data['payment']))
			$message .= '<tr>
				<td align="left" width="170" style="border-top:1px solid #f4f4f4">Оплата:</td>
				<td align="left" style="border-top:1px solid #f4f4f4">
					Платіжний механізм: <b>'.$data['payment']->name.'</b>';
					if(!empty($data['payment']->info))
						$message .= '<br>'.nl2br($data['payment']->info);
				$message .= '</td>
			</tr>';
		if(!empty($data['info']))
			$message .= '<tr>
				<td align="left" width="170" style="border-top:1px solid #f4f4f4">Коментар:</td>
				<td align="left" style="border-top:1px solid #f4f4f4">'.$data['info'].'</td>
			</tr>';
	$message .= '</tbody>
</table>';

$message .= '<br><br><h3><b>Ваше замовлення</b></h3>
<table align="left" width="100%" cellpadding="10" cellspacing="0" border="0" style="border-collapse:collapse">
	<tbody><tr>
		<th style="border-bottom:2px solid #f4f4f4"></th>
		<th align="left" style="border-bottom:2px solid #f4f4f4"><strong>Артикул</strong></th>
		<th align="left" style="border-bottom:2px solid #f4f4f4"><strong>Товар</strong></th>
		<th align="right" style="border-bottom:2px solid #f4f4f4"><strong>Ціна</strong></th>
		<th align="right" style="border-bottom:2px solid #f4f4f4"><strong>Кількість</strong></th>
		<th align="right" style="border-bottom:2px solid #f4f4f4"><strong>Сума</strong></th>
	</tr>
	<tr>';
		$i = 1;
		foreach($data['products'] as $product){
		    $message .=  '<tr>
		                    <td align="left" valign="top" style="border-top:1px solid #f4f4f4">'. $i .'</td>
		                    <td align="left" valign="top" style="border-top:1px solid #f4f4f4">'. $product->info->article_show .'</td>
		                    <td align="left" valign="top" style="border-top:1px solid #f4f4f4"><a href="'.SITE_URL.$product->info->link.'" style="color:#693319!important;text-decoration:underline" target="_blank"><span style="color:#693319">'. $product->info->name.'</span></a>';
							if(!empty($product->product_options))
							{
								if(!is_array($product->product_options))
									$product->product_options = unserialize($product->product_options);
								foreach ($product->product_options as $option) {
									$message .= "<br>{$option->name}: <strong>{$option->value_name}</strong>";
								}
							}
		    $message .= '</td>
		                    <td align="right" valign="top" style="border-top:1px solid #f4f4f4">'. $product->price_format .'</td>
		                    <td align="right" valign="top" style="border-top:1px solid #f4f4f4">'. $product->quantity .' шт.</td>
		                    <td align="right" valign="top" style="border-top:1px solid #f4f4f4">';
				if($product->discount && !empty($product->sumBefore_format))		                    
		    		$message .= '<del>'. $product->sumBefore_format .'</del><br>';
		    $message .=        '<strong>'. $product->sum_format .'</strong>
		    				</td>
		                </tr>';
		    $i++;
		}

	if (!empty($data['discount']) || !empty($data['shippingPrice'])){
		$message .= '<tr><td colspan="6" align="right" style="border-top:2px solid #f4f4f4">Сума: <b>'.$data['subTotalFormat'].'</b></td><td style="border-top:2px solid #f4f4f4"></td></tr>';
	if (!empty($data['discount']))
		$message .= '<tr><td colspan="6" align="right">Знижка: <b>'.$data['discountFormat'].'</b></td><td></td></tr>';
	if (!empty($data['shippingPrice']))
		$message .= '<tr><td colspan="6" align="right">Доставка: <b>'.$data['shippingPriceFormat'].'</b></td><td></td></tr>';
}
$message .= '<tr><td colspan="6" align="right" style="border-top:2px solid #f4f4f4">До оплати: <b>'.$data['total_formatted'].'</b></td></tr></tbody></table>';

$message .= '<p><a href="'.$data['link'].'">Щоб подивитися замовлення на сайті, перейдіть за цим посиланням.</a></p>';

// if($data['payed'] < $data['total'])
// 	$message .= "<p>Для онлайн оплати Visa/MasterCard через Liqpay перейдіть <a href=\"{$data['pay_link']}\">{$data['pay_link']}</a></p>";

$message .= '<p>З найкращими побажаннями, адміністрація '.SITE_NAME.'</p></body></html>';

?>