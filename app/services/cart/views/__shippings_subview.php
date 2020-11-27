<h4><?=$this->text('Доставка')?></h4>

<?php $shippingType = $shippings[0]->type;
$shippingInfo = $shippings[0]->info;
$shippingWlAlias = $shippings[0]->wl_alias;
if(count($shippings) > 1) {
    $selected = $this->data->re_post('shipping-method');
    if(empty($selected) && $userShipping && $userShipping->method_id)
        $selected = $userShipping->method_id;
    foreach ($shippings as $i => $method) { ?>
        <label>
            <input type="radio" name="shipping-method" value="<?=$method->id?>" <?php if($selected == $method->id) { echo 'checked'; $shippingType = $method->type; $shippingInfo = $method->info; $shippingWlAlias = $method->wl_alias; } ?> onchange="changeShipping(this)" <?=$i == 0?'required':''?>>
            <?=$method->name?>
        </label>
        <?php }
    } else { ?>
    <p><strong><?=$shippings[0]->name?></strong></p>
    <input type="hidden" name="shipping-method" value="<?=$shippings[0]->id?>">
<?php } ?>

<div class="alert alert-warning" id="shipping-info" <?=(empty($shippingInfo)) ? 'style="display:none"':''?>>
    <?=$shippingInfo?>
</div>

<?php $city = $this->data->re_post('shipping-city');
if(empty($city) && $userShipping && $userShipping->city)
    $city = $userShipping->city; ?>
<div class="<?= $shippingType == 1 || $shippingType == 2 ? '' : 'hide' ?>" id="shipping-cities">
    <!-- <label><?=$this->text('Місто')?></label> -->
    <input type="text" name="shipping-city" list="shipping-cities-list" placeholder="<?=$this->text('Місто')?>" value="<?= $city ?>" <?= $shippingType == 1 || $shippingType == 2 ? 'required' : '' ?>>
    <datalist id="shipping-cities-list"></datalist>
</div>

<?php $department = $this->data->re_post('shipping-department');
if(empty($department) && $userShipping && $userShipping->department)
    $department = $userShipping->department; ?>
<div class="<?= $shippingType == 2 ? '' : 'hide' ?>" id="shipping-departments" >
    <!-- <label><?=$this->text('Відділення')?></label> -->
    <input type="text" name="shipping-department" value="<?= $department ?>" placeholder="<?=$this->text('Введіть номер/адресу відділення')?>" <?= $shippingType == 2 ? 'required' : '' ?>>
</div>

<?php $address = $this->data->re_post('shipping-address');
if(empty($address) && $userShipping && $userShipping->address)
    $address = $userShipping->address; ?>
<div class="<?= $shippingType == 1 ? '' : 'hide' ?>" id="shipping-address">
    <!-- <label><?=$this->text('Адреса')?></label> -->
    <textarea name="shipping-address" placeholder="<?=$this->text('Адреса: м. Київ, вул. Київська 12, кв. 3')?>" rows="3" <?= $shippingType == 1 ? 'required' : '' ?>><?= $address ?></textarea>
</div>

<div id="Shipping_to_cart">
	<?php if(!empty($userShipping) && $shippingWlAlias != $_SESSION['alias']->id) {
        $userShipping->initShipping = true;
		$this->load->function_in_alias($shippingWlAlias, '__get_Shipping_to_cart', $userShipping);
    } ?>
</div>

<h4><?=$this->text('Отримувач')?></h4>

<?php $recipientName = $this->data->re_post('recipientName');
if(empty($recipientName))
{
    $recipientName = $userShipping && !empty($userShipping->recipientName) ? $userShipping->recipientName : '';
    if($recipientName == '' && $this->userIs())
        $recipientName = $_SESSION['user']->name;
}
$recipientPhone = $this->data->re_post('recipientPhone');
if(empty($recipientPhone) && $userShipping && !empty($userShipping->recipientPhone))
    $recipientPhone = $userShipping->recipientPhone;
if(empty($recipientPhone) && $this->userIs() && !empty($_SESSION['user']->phone))
    $recipientPhone = $_SESSION['user']->phone;
?>
<input type="text" name="recipientName" placeholder="<?=$this->text('Ім\'я Прізвище отримувача')?>" value="<?= $recipientName ?>" required>
<input type="phone" name="recipientPhone" placeholder="<?=$this->text('+380********* (Контактний номер)')?>" value="<?= $recipientPhone ?>" required>

<script>
var shippingsTypes = {
    <?php if($shippings) foreach ($shippings as $method)
        echo "\"$method->id\"" . ' : "' . $method->type. '", ';
    ?>
};
var shippingsInformation = {
    <?php if($shippings) foreach ($shippings as $method)
    {
        $method->info = nl2br($method->info);
        $method->info = preg_replace("/[\n\r]/","", $method->info); 
        echo "\"$method->id\"" . ' : ' . ($method->info != '' ? "\"$method->info\"" : '""')  . ', ';
    }
    ?>
};
</script>