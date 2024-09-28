<h4><?=$this->text('Доставка')?></h4>
<div class="cart_section">

    <?php $shippingType = $shippings[0]->type;
    $shippingInfo = $shippings[0]->info;
    $shippingWlAlias = $shippings[0]->wl_alias;
    if(count($shippings) > 1) {
        $selected = $this->data->re_post('shipping-method');
        if(empty($selected) && $userShipping && $userShipping->method_id)
            $selected = $userShipping->method_id;
        foreach ($shippings as $i => $method) { ?>
            <label class="flex v-center">
                <input type="radio" name="shipping-method" value="<?=$method->id?>" <?php if($selected == $method->id) { echo 'checked'; $shippingType = $method->type; $shippingInfo = $method->info; $shippingWlAlias = $method->wl_alias; } ?> onchange="changeShipping(this)">
                <?= $method->wl_alias ? '<img src="/style/novaposhta/np_logo.png" style="width: 35px;padding-right: 0;">' : '' ?> <span><?=$method->name?></span>
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
        <div class="input-group <?= !empty($city) ? 'val' : '' ?>">
            <label for="shipping-city"><?=$this->text('Місто')?></label>
            <input type="text" id="shipping-city" name="shipping-city" list="shipping-cities-list" value="<?= $city ?>" <?= $shippingType == 1 || $shippingType == 2 ? 'required' : '' ?>>
            <h5 class="text-danger hide"><?= $this->text('Тільки кирилиця') ?></h5>
        </div>
        <datalist id="shipping-cities-list"></datalist>
    </div>

    <?php $department = $this->data->re_post('shipping-department');
    if(empty($department) && $userShipping && $userShipping->department)
        $department = $userShipping->department; ?>
    <div class="<?= $shippingType == 2 ? '' : 'hide' ?>" id="shipping-departments" >
        <div class="input-group <?= !empty($department) ? 'val' : '' ?>">
            <label for="shipping-department"><?=$this->text('Відділення')?></label>
            <input type="text" id="shipping-department" name="shipping-department" value="<?= $department ?>" placeholder="<?=$this->text('Введіть номер/адресу відділення')?>" <?= $shippingType == 2 ? 'required' : '' ?>>
        </div>
    </div>

    <?php $address = $this->data->re_post('shipping-address');
    if(empty($address) && $userShipping && $userShipping->address)
        $address = $userShipping->address; ?>
    <div class="<?= $shippingType == 1 ? '' : 'hide' ?>" id="shipping-address">
        <div class="input-group <?= !empty($address) ? 'val' : '' ?>">
            <label for="shipping-address-in"><?=$this->text('Адреса')?></label>
            <input type="text" id="shipping-address-in" name="shipping-address" value="<?= $address ?>" <?= $shippingType == 1 ? 'required' : '' ?>>
        </div>
    </div>

    <div id="Shipping_to_cart">
        <?php if($shippingWlAlias != $_SESSION['alias']->id) {
            if(empty($userShipping)) {
                $userShipping = new stdClass();
            }
            $userShipping->initShipping = false;
            $this->load->function_in_alias($shippingWlAlias, '__get_Shipping_to_cart', $userShipping);
        } ?>
    </div>

</div>

<?php
$recipientName = $this->data->re_post('recipientName');
if(empty($recipientName)) {
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

<?php if(isset($cart) && $this->userIs()) { ?>
    <h4><?=$this->text('Отримувач')?></h4>
    <div class="cart_section">
        <div class="input-group val <?php //= !empty($recipientName) ? 'val' : '' ?>">
            <label for="recipientName"><?=$this->text('Ім\'я Прізвище')?></label>
            <input type="text" id="recipientName" name="recipientName" placeholder="<?=$this->text('Ім\'я Прізвище')?>" class="input" value="<?= $recipientName ?>" required>
        </div>
        <div class="input-group val <?php //= !empty($recipientPhone) ? 'val' : '' ?>">
            <label for="recipientPhone"><?=$this->text('Контактний номер')?></label>
            <input type="text" id="recipientPhone" name="recipientPhone" placeholder="<?=$this->text('+380*********')?>" class="input" value="<?= $recipientPhone ?>" required>
        </div>
    </div>
<?php } else { ?>
    <input type="hidden" name="recipientName" value="<?= $recipientName ?>" required>
    <input type="hidden" name="recipientPhone" value="<?= $recipientPhone ?>" required>
<?php } ?>


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