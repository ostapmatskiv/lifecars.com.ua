<div class="panel">
	<div class="panel-body">
	    <div class="col-md-4">
	        <p>Покупець: <strong><a href="<?=SITE_URL?>admin/wl_users/<?= $cart->user_email?>" class="btn btn-success btn-xs">#<?= $cart->user?>. <?= $cart->user_name?></a> (<?= $cart->user_type_name?>)</strong></p>
	        <p><strong><a href="mailto:<?=$cart->user_email?>"><?=$cart->user_email?></a> <?= $cart->user_phone?></strong></p>
	        <?= ($cart->manager) ? ' <hr style="margin:5px 0">Менеджер: <strong><a href="'.SITE_URL.'admin/wl_users/'.$cart->manager_email.'">'.$cart->manager_name.'</a></strong>' : '' ?>
	    </div>
	    <div class="col-md-4">
	    	<p>Поточний статус: <strong><?= $cart->status_name ?? 'Формування' ?></strong></p>
	        <p>Загальна сума замовлення: <strong><?= $cart->totalFormat?></strong></p>
	        <p>Оплата: <strong><?php if($cart->payed == 0) echo "Не оплачено";
	        							elseif($cart->payed >= $cart->total) echo "Оплачено повністю ({$cart->payed} грн)";
	        							else echo "Часткова оплата <u>{$cart->payedFormat}</u>"; ?></strong></p>
	    </div>
	    <div class="col-md-4">
	        <p>Створено: <strong><?= date('d.m.Y H:i', $cart->date_add)?></strong>
	        	<?php if($_SESSION['user']->admin) { ?>
	        	<button onClick="$('#uninstall-form').slideToggle()" class="btn btn-danger btn-xs right"><i class="fa fa-trash"></i> Видалити замовлення</button>
	        	<?php } ?>
	        </p>
	        <p>Остання операція: <strong><?= $cart->date_edit > 0 ? date('d.m.Y H:i', $cart->date_edit) : 'очікує' ?></strong>
	        </p>
	        <?php if(isset($cart->date_1c)) { ?>
	        <p> <?php if($cart->{"1c_status"} == $cart->status && $cart->date_1c > 0) { ?>
	        		<button type="button" onClick="set_1c_status(this)" data-status="0" class="btn btn-warning btn-xs pull-right" title="Відмітити замовлення як не синхронізовано"><i class="fa fa-repeat" aria-hidden="true"></i> Повторна синхронізація</button>
	        	<?php } else { ?>
	        		<button type="button" onClick="set_1c_status(this)" data-status="1" class="btn btn-warning btn-xs pull-right" title="Відмітити замовлення як синхронізовано"><i class="fa fa-ban" aria-hidden="true"></i> Скасувати синхронізхацію</button>
	        	<?php } ?>
	        	Синхронізація з 1с: 
	        		<strong class="text-<?= ($cart->{"1c_status"} == $cart->status && $cart->date_1c > 0) ? 'success' : 'warning' ?>" <?= ($cart->{"1c_status"} == $cart->status && $cart->date_1c > 0) ? '' : 'title="Очікуємо: '.$cart->status_1c_name.' => '.$cart->status_name.'"' ?>>
	        			<i class="fa fa-<?= ($cart->{"1c_status"} == $cart->status && $cart->date_1c > 0) ? 'check-circle' : 'ban' ?>" aria-hidden="true"></i>
	        			<?= $cart->date_1c > 0 ? date('d.m.Y H:i', $cart->date_1c) : 'очікується' ?>
	        		</strong>
	        </p>
	        <?php } ?>
	    </div>
	</div>

	<?php if($_SESSION['user']->admin) { ?>
		<div class="col-md-12">
			<div id="uninstall-form" class="alert alert-danger fade in m-t-10" style="display: none;">
		        <form action="<?=SITE_URL.'admin/'.$_SESSION['alias']->alias?>/delete" method="POST">
					<h4><i class="fa fa-trash pull-left"></i> Видалити замовлення #<?=$cart->id?>?</h4>
					<?php if(!empty($cart->products[0]->storage)) { ?>
						<p><label><input type="checkbox" name="storage_cancel" value="1" checked> Повернути зарезервований/списаний товар на склад</label></p>
						<p><label><input type="checkbox" name="payment_cancel" value="1" checked> Повернути зарезервовані/списані кошти</label></p>
					<?php } ?>
					<input type="hidden" name="id" value="<?=$cart->id?>">
					<div style="max-width: 800px">
						<div class="form-group clearfix">
						    <label class="col-md-4 control-label">Пароль адміністратора для підтвердження</label>
						    <div class="col-md-8">
						        <input type="password" name="password" required placeholder="Пароль адміністратора (Ваш пароль)" class="form-control">
						    </div>
						</div>
						<div class="m-t-10 text-center">
							<input type="submit" value="Видалити" class="btn btn-danger">
							<button type="button" style="margin-left:25px" onClick="$('#uninstall-form').slideToggle()" class="btn btn-info">Скасувати</button>
						</div>
					</div>
		        </form>
		  </div>
	  </div>
	<?php } ?>
</div>

<div class="panel">
	<div class="panel-body">
		<?php require_once 'tabs/_tabs-products.php'; ?>
	</div>
</div>

<div class="panel" id="manager_comment" <?=empty($cart->manager_comment) ? 'style="display:none"':''?>>
	<div class="panel-body">
	    <legend><i class="fa fa-comment-o" aria-hidden="true"></i> Службовий коментар до замовлення</legend>
		<textarea data-cart="<?=$cart->id?>" class="form-control" rows="3"><?=$cart->manager_comment?></textarea>
	</div>
</div>

<?php if(!empty($cart->payment->name)) { ?>
<div class="panel">
	<div class="panel-body">
		<?php
    	echo '<legend><i class="fa fa-credit-card-alt" aria-hidden="true"></i> Оплата</legend></p>';
	    echo '<p>Платіжний механізм: <b>'.$cart->payment->name.'</b></p>';
	    echo "<p>{$cart->payment->info}</p>";
	    if(!empty($cart->payment->admin_link))
	        echo "<a href='{$cart->payment->admin_link}' class='btn btn-info btn-xs'><i class=\"fa fa-external-link\" aria-hidden=\"true\"></i> Повна інформація по оплаті</a>";
		?>
	</div>
</div>
<?php } if($cart->payed < $cart->total && empty($cart->payment_alias) && $this->data->uri(3) != 'edit-shipping') { ?>
<div class="panel">
	<div class="panel-body">
		<legend><i class="fa fa-credit-card-alt" aria-hidden="true"></i> Внести оплату</legend>
		<table class="table table-striped table-bordered nowrap" width="100%">
		    <form action="<?= SITE_URL.'admin/'. $_SESSION['alias']->alias.'/addPayment'?>" onsubmit="return confirm('Внести оплату')" method="POST" class="form-horizontal" >
		        <input type="hidden" name="cart" value="<?= $cart->id?>">
		        <tbody>
		            <tr>
		                <th>Механізм</th>
		                <td>
		                    <select name="status" class="form-control" required>
		                        <?php foreach($cart->paymentsMethod as $method) if(empty($method->wl_alias)) { ?>
		                        <option value="<?= $method->id?>"><?= $method->name?></option>
		                        <?php } ?>
		                    </select>
		                </td>
		            </tr>
		            <tr>
		                <th>Сума (у валюті корзини)</th>
		                <td>
		                    <input name="amount" type="number" min="0.01" step="0.01" class="form-control" value="<?=round($cart->total - $cart->payed, 2)?>" required />
		                </td>
		            </tr>
		            <tr>
		                <th>Коментар</th>
		                <td><textarea name="comment" class="form-control" rows="5"></textarea></td>
		            </tr>
		            <tr>
		                <th></th>
		                <td>
		                    <button type="submit" class="btn btn-md btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Внести оплату</button>
		                </td>
		            </tr>
		        </tbody>
		    </form>
		</table>
	</div>
</div>
<?php } if(!empty($cart->comment) && $this->data->uri(3) != 'edit-shipping') { ?>
<div class="panel">
	<div class="panel-body">
	    <legend><i class="fa fa-commenting" aria-hidden="true"></i> Коментар (побажання) клієнта до замовлення</legend>
		<p><?=$cart->comment?></p>
	</div>
</div>
<?php }

if(($cart->shipping_id || !empty($cart->shipping_info)) && $this->data->uri(3) != 'edit-shipping') { ?>
<div class="panel">
	<div class="panel-body">
		<legend><i class="fa fa-truck" aria-hidden="true"></i> Доставка
			<?=($cart->action == 'new') ? "<a href='/admin/{$_SESSION['alias']->alias}/{$cart->id}/edit-shipping' class='btn btn-primary btn-xs'><i class=\"fa fa-pencil\"></i> редагувати</a>" : '' ?>
		</legend>
		<?php
	    if(!empty($cart->shipping->name))
	        echo "<p>Служба доставки: <b>{$cart->shipping->name}</b> </p>";
	    if(!empty($cart->shipping->text))
	        echo "<p>{$cart->shipping->text}</p>";
	    elseif(is_array($cart->shipping_info))
	    {
	        if(!empty($cart->shipping_info['city']))
	            echo "<p>Місто: <b>{$cart->shipping_info['city']}</b> </p>";
	        if(!empty($cart->shipping_info['department']))
	            echo "<p>Відділення: <b>{$cart->shipping_info['department']}</b> </p>";
	        if(!empty($cart->shipping_info['address']))
	            echo "<p>Адреса: <b>{$cart->shipping_info['address']}</b> </p>";
	    }
	    elseif(!empty($cart->shipping_info) && is_string($cart->shipping_info))
	    	echo "<p>{$cart->shipping_info}</p>";
	    if(!empty($cart->shipping_info['recipient']))
	        echo "<p>Отримувач: <b>{$cart->shipping_info['recipient']}</b> </p>";
	    if(!empty($cart->shipping_info['phone']))
	        echo "<p>Контактний телефон: <b>{$cart->shipping_info['phone']}</b> </p>";
		?>
		<div class="form-group">
            <label class="col-md-3 control-label text-right">ТТН доставки</label>
            <div class="col-md-9">
            	<div class="input-group">
                	<input type="text" class="form-control" data-cart="<?=$cart->id?>" id="shipping_ttn" value="<?=$cart->ttn?>" placeholder="ТТН доставки">
                	<span class="input-group-btn">
                		<?php $showTTNmodal = 0;
                		if($cart->status_weight < 20 && $cartStatuses)
                			foreach ($cartStatuses as $status) {
                				if($status->weight >= 20 && $status->weight < 30)
                				{
                					$showTTNmodal = 1;
                					break;
                				}
                			}
                		 ?>
						<button type="submit" class="btn btn-secondary" onclick="presaveTTN(<?=$showTTNmodal?>)">Зберегти</button>
					</span>
				</div>
            </div>
        </div>
	</div>
</div>
<?php }
elseif($cart->action == 'new' && (empty($cart->shipping_id) || $this->data->uri(3) == 'edit-shipping'))
{
	if($shippings = $this->cart_model->getShippings(array('active' => 1))) { ?>
<div class="panel" id="cart">
	<div class="panel-body">
		<legend><i class="fa fa-truck" aria-hidden="true"></i> Доставка</legend>
	    <?php if(empty($cart->shipping_id))
	    			$userShipping = $this->cart_model->getUserShipping($cart->user);
	    		else
	    		{
	    			$userShipping = new stdClass();
	    			$userShipping->method_id = $cart->shipping_id;
	    			$userShipping->info = $cart->shipping_info;
	    			$userShipping->city = $userShipping->department = $userShipping->address = '';
	    			if(!empty($userShipping->info))
						foreach ($userShipping->info as $key => $value) {
							$userShipping->$key = $value;
						}
	    		}
	    echo '<form action="'.SITE_URL.$_SESSION['alias']->alias.'/set__shippingToOrder" method="post" class="col-sm-4 w30">';
	    echo '<input type="hidden" name="order_id" value="'.$cart->id.'">';
	    echo '<input type="hidden" name="redirect" value="admin/'.$_SESSION['alias']->alias.'/'.$cart->id.'">';
	    require_once APP_PATH.'services/cart/views/__shippings_subview.php';
	    echo '<a href="/admin/'.$_SESSION['alias']->alias.'/'.$cart->id.'" class="btn btn-warning m-r-5" style="display: inline-block;"><i class="fa fa-undo" aria-hidden="true"></i> Назад</a>';
	    echo '<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Зберегти</button>';
		echo "</form></div></div>";

		echo '<link rel="stylesheet" type="text/css" href="'.SERVER_URL.'style/'.$_SESSION['alias']->alias.'/cart.css">';
		echo '<link rel="stylesheet" type="text/css" href="'.SERVER_URL.'style/'.$_SESSION['alias']->alias.'/checkout.css">';
		echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">';
		$this->load->js(['assets/jquery-ui/1.12.1/jquery-ui.min.js', 'assets/jquery.mask.min.js', 'js/'.$_SESSION['alias']->alias.'/cities.js', 'js/'.$_SESSION['alias']->alias.'/checkout.js']);
	}
}

if($cart->status > 0 && $cart->status_weight < 90 && $this->data->uri(3) != 'edit-shipping') { ?>
<div class="panel">
	<div class="panel-body">
		<?php require_once 'tabs/_tabs-history.php'; ?>
	</div>
</div>
<?php } ?>

<div class="panel">
	<div class="panel-body">
		<legend><i class="fa fa-history" aria-hidden="true"></i> Історія замовлення</legend>
		<div class="table-responsive">
		    <table class="table table-striped table-bordered nowrap" width="100%">
		        <thead>
		            <tr>
		                <th>Внесено</th>
		                <th>Статус</th>
		                <th>Коментар</th>
		            </tr>   
		        </thead>
		        <tbody>
		            <tr>
		                <td><?= date('d.m.Y H:i',$cart->date_add)?> <br> <?= $cart->user_name?></td>
		                <td>Заявка</td>
		                <td><?= $cart->comment?> </td>
		            </tr>
		            <?php if($cart->history) foreach($cart->history as $history) {?>
		            <tr>
		                <td><?= date('d.m.Y H:i', $history->date)?> <br> <?= $history->user_name?></td>
		                <td><?= $history->status_name?></td>
		                <td>
		                    <span id="comment-<?= $history->id?>">
		                        <?= $history->comment?> 
		                    </span>
		                    <span>
		                        <?= ($history->user > 0 && $history->status > 1) ? "<button data-toggle='modal' data-target='#commentModal' data-comment='{$history->comment}' data-id='{$history->id}' class='right'><i class='fa fa-pencil-square-o'></i></button>" : '' ?>
		                    </span>
		                </td>
		            </tr>
		            <?php } ?> 
		        </tbody>
		    </table>
		</div>
	</div>
</div>


<script>
function set_1c_status(btn) {
	let status = $(btn).data('status');
	$('#page-loader').removeClass('hide');
	$.ajax({
        url: '<?=SITE_URL?>admin/cart/set_1c_status',
        type: 'POST',
        data: { 
        	cart_id: <?=$cart->id?>,
        	status: status,
        	ajax: true
        }
    })
    .done(function(res) {
        if(res == 'success') {
        	if(status == 1)
        	{
        		$(btn).attr('title', 'Відмітити замовлення як не синхронізовано').html('<i class="fa fa-repeat" aria-hidden="true"></i> Повторна синхронізація').data('status', '0');
        		let date = new Date();
        		$(btn).parent().find('strong').addClass('text-success').removeClass('text-warning').html('<i class="fa fa-check-circle" aria-hidden="true"></i> '+date.toDateString());
        	}
        	else
        	{
        		$(btn).attr('title', 'Відмітити замовлення як синхронізовано').html('<i class="fa fa-ban" aria-hidden="true"></i> Скасувати синхронізхацію').data('status', '1');
        		$(btn).parent().find('strong').addClass('text-warning').removeClass('text-success').html('<i class="fa fa-ban" aria-hidden="true"></i> очікується');
        	}
        }
    })
    .always(function() {
        $('#page-loader').addClass('hide');
    });
}
</script>