<center><img src="<?=IMG_PATH?>logo.png" style="width: 150px"></center>
<div class="pull-right" style="text-align: right;">
	<p><strong>вул. В.Великого 44</strong></p>
	<p><strong>м. Львів, Україна, 79054</strong></p>
</div>
<p><strong><?=SITE_NAME?></strong></p>
<p><strong><?=SITE_EMAIL?>, +38 068 3072698</strong></p>

<h1>Замовлення #<?= $cart->id?> від <?= date('d.m.Y H:i', $cart->date_edit)?></h1>

<?php /* <div class="pull-right" style="text-align: right;">
	<p>Статус замовлення: <strong><?= $cart->status_name ?></strong></p>
</div> */ ?>

<table class="cartUserinfo">
	<tr>
		<td>Покупець:</td>
		<td>
			<strong><?= $cart->user_name ." (#$cart->user)" ?></strong>
			<br><?= $cart->user_email .", " . $cart->user_phone ?>
		</td>
	</tr>
	<?php if($cart->shipping_id && !empty($cart->shipping->name)) { ?>
		<tr>
			<td>Доставка: </td>
			<td><strong><?= $cart->shipping->name ?></strong>
				<?php if(!empty($cart->shipping->text))
	            echo "<p>{$cart->shipping->text}</p>";
			        else
			        {
			            if(!empty($cart->shipping_info['city']))
			                echo "<p>Місто: <b>{$cart->shipping_info['city']}</b> </p>";
			            if(!empty($cart->shipping_info['department']))
			                echo "<p>Відділення: <b>{$cart->shipping_info['department']}</b> </p>";
			            if(!empty($cart->shipping_info['address']))
			                echo "<p>Адреса: <b>{$cart->shipping_info['address']}</b> </p>";
			        }
			        if(!empty($cart->shipping_info['recipient']))
			            echo "<p>Отримувач: <b>{$cart->shipping_info['recipient']}</b>";
			        if(!empty($cart->shipping_info['phone']))
			            echo ", <b>{$cart->shipping_info['phone']}</b>";
			        echo " </p>"; ?>
			</td>
		</tr>
	<?php } ?>
</table>

	<div class="table-responsive" >
	<table class="table table-striped table-bordered nowrap" width="100%">
		<thead>
			<tr>
				<th>#</th>
				<?php if(!empty($cart->products[0]->info->article)) { ?>
	    			<th>Артикул</th>
	    		<?php } ?>
				<th>Виробник</th>
				<th>Товар</th>
				<?php if($cart->products[0]->storage_invoice) { ?>
					<th><?=$this->text('Склад')?></th>
				<?php } ?>
				<th width="80px">Ціна</th>
				<th width="60px">К-сть</th>
				<th width="80px">Сума</th>
			</tr>
		</thead>
		<tbody>
			<?php $i = 1; if($cart->products) foreach($cart->products as $product) {?>
			<tr>
				<td><?=$i++?></td>
				<?php if(!empty($product->info->article)) { ?>
					<td><?= $product->info->article_show ?></td>
				<?php } ?>
				<td><?=$product->info->options['1-manufacturer']->value?></td>
				<td><?php if(!empty($product->info))
				echo '<strong>'.$product->info->name.'</strong>';
    			if(!empty($product->product_options))
				{
					echo "<hr style='margin:2px'>";
					$i = 0;
					$product->product_options = unserialize($product->product_options);
					foreach ($product->product_options as $key => $value) {
						if($i++ > 0)
							echo "<br>";
						echo "{$key}: <strong>{$value}</strong>";
					}
				} ?></td>
				<?php if($product->storage_invoice) { ?>
					<td><?=$product->storage->storage_name?></td>
				<?php } ?>
				<td><?= $product->price_format ?></td>
				<td><?= $product->quantity ?></td>
				<td><?= $product->sum_format ?></td>
			</tr>
			<?php } 
			$cols = 7;
			if(!empty($cart->products[0]->info->article))
				$cols++;
			?>
			<tr>
				<td colspan="<?=$cols?>" style="text-align: right;">Сума: <b><?= $cart->totalFormat?></b></td>
			</tr>
		</tbody>
	</table>
</div>

<style>
	h1 { margin: 40px 0 30px }
	table.cartUserinfo tr td { padding: 5px }
	table.table tr td:nth-child(6),
	table.table tr td:nth-child(8) { text-align: right }
	table.table tr td:nth-child(7) { text-align: center }
</style>