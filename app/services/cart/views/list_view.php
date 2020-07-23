<h2><?=$this->text('Мої замовлення')?></h2>
<table class="cart_list">
	<thead>
		<tr>
			<?php /*<th></th>*/ ?>
			<th><?=$this->text('Замовлення')?></th>
			<th><?=$this->text('Статус')?></th>
			<th><?=$this->text('Сума')?></th>
			<th><?=$this->text('Оплата')?></th>
			<th><?=$this->text('Доставка')?></th>
		</tr>
	</thead>
	<tbody>
		<?php if($orders) { foreach($orders as $order) { ?>
		<tr>
			<?php /*<td>
				<a class="btn" href="<?= SITE_URL.'cart/'.$order->id ?>"><i class="far fa-list-alt"></i> <?=$this->text('Перегляд')?></a>
				<?php if(false && $order->status == 1 && $showPayment) { ?>
					<a href="<?= SITE_URL.'cart/'.$order->id ?>/pay" class="btn btn-warning"><i class="fas fa-credit-card"></i> <?=$this->text('Оплатити')?></a>
				<?php } ?>
			</td>*/ ?>
			<td><a class="btn" href="<?= SITE_URL.'cart/'.$order->id ?>"><i class="far fa-list-alt"></i> #<strong><?= $order->id .'</strong> від '. date('d.m.Y H:i', $order->date_add)?></a></td>
			<td><?= $order->status_name ?></td>
			<td><?= $order->total_format ?></td>
			<td><?= empty($order->payed) ? 'Очікує оплати' : 'Оплачено' ?> </td>
			<td><?=$order->shipping_name ?><?=!empty($order->ttn)?'. ТТН '.$order->ttn:''?></td>
		</tr>
		<?php } } else { ?>
			<tr>
				<td colspan="5">Замовлення відсутні</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<style>
	table.cart_list { width: 100%; max-width: 100%; border-collapse: collapse; }
	table.cart_list th, table.cart_list td {
	    border: 1px solid #ddd !important;
	    padding: 10px;
	}
</style>