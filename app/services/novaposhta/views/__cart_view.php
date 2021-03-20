<?php /* <div id="nova-poshta-method" class="flex">
    <div data-tab="warehouse" class="w50<?=(!empty($userShipping->method) && $userShipping->method == 'courier') ? '':' active'?>"><?=$this->text('На відділення')?></div>
    <div data-tab="courier" class="w50<?=(!empty($userShipping->method) && $userShipping->method == 'courier') ? ' active':''?>"><?=$this->text("Кур'єром")?></div>
</div> */ ?>

<input type="hidden" name="nova-poshta-method" value="warehouse">
<input type="hidden" name="nova-poshta-city-ref" value="<?=$userShipping->city_ref ?? ''?>">
<input type="hidden" name="nova-poshta-warehouse-ref" value="<?=$userShipping->warehouse_ref ?? ''?>">

 <!-- <label><?=$this->text('Місто')?></label> -->
<input type="text" name="novaposhta-city" placeholder="<?=$this->text('Місто')?>" value="<?=(!empty($userShipping->city_ref) && !empty($userShipping->city)) ? $userShipping->city : ''?>" autocomplete="off" class="form-control" required>

<div id="nova-poshta-warehouse" <?=(!empty($userShipping->method) && $userShipping->method == 'courier') ? 'class="hide"':''?>>
    <!-- <label><?=$this->text('Відділення')?></label> -->
    <select name="nova-poshta-warehouse" class="form-control" <?=(!empty($userShipping->method) && $userShipping->method == 'courier') ? '':'required'?>>
        <?php $info = '';
        if (!empty($userShipping->city_ref) && !empty($userShipping->warehouse_ref)) {
            if($warehouses = $this->getWarehouses($userShipping->city_ref))
                foreach ($warehouses as $warehouse) {
                    $selected = '';
                    if($warehouse->id == $userShipping->warehouse_ref)
                    {
                        $selected = 'selected';
                        $info = $warehouse->info;
                    }
                    echo '<option data-id="'.$warehouse->id.'" data-info="'.htmlspecialchars($warehouse->info).'" title="'.$warehouse->title.'" '.$selected.'>'.$warehouse->name.'</option>';
                }
        } else { ?>
            <option selected disabled value=""><?=$this->text('Для вибору відділення спершу введіть місто')?></option>
        <?php } ?>
    </select>

    <div class="info <?=empty($info) ? 'hide' : ''?>"><?=$info?></div>
</div>

<div id="nova-poshta-courier" <?=(!empty($userShipping->method) && $userShipping->method == 'courier') ? '':'class="hide"'?>>
    <!-- <label><?=$this->text('Вулиця')?></label> -->
    <input type="text" name="novaposhta-address-street" class="form-control" placeholder="<?=$this->text('Вулиця')?>" value="<?=$userShipping->address_street ?? ''?>" <?=(!empty($userShipping->method) && $userShipping->method == 'courier') ? 'required':''?>>
    <input type="text" name="novaposhta-address-house" class="form-control" placeholder="<?=$this->text('Номер будинку/та квартри')?>" value="<?=$userShipping->address_house ?? ''?>" <?=(!empty($userShipping->method) && $userShipping->method == 'courier') ? 'required':''?>>
</div>



<?php $novaposhta_selected = $this->data->re_post('shipping-novaposhta');
if(empty($novaposhta_selected) && $userShipping && $userShipping->department)
    $novaposhta_selected = $userShipping->department;
if($userShipping && $userShipping->initShipping)
    $this->load->js_init('initShipping()'); ?>
<script>
    window.onload = function ( ) {
        
    };
    function setCity(city) {
        $("#nova-poshta-warehouse select").empty().append('<option selected disabled="" value="">Виберіть відділення</option>');
        $("#nova-poshta-warehouse .info").addClass('hide');
        $('input[name="nova-poshta-city-ref"]').val(city);
        if($('input[name="nova-poshta-method"]').val() == 'warehouse')
        {
            $("div#divLoading").addClass('show');
            $.ajax({
                url: '<?=SITE_URL.$_SESSION['alias']->alias?>/getWarehouses',
                type: 'POST',
                data: { 'city' : city },
                success:function(warehouses) {
                    if(warehouses)
                        warehouses.forEach(function(warehous) {
                            $('<option/>', { text: warehous.name, title: warehous.title, 'data-id': warehous.id, 'data-info': warehous.info}).appendTo($("#nova-poshta-warehouse select"))
                        });
                },
                complete: function() {
                    $("div#divLoading").removeClass('show');
                }
            })
        }
        else
            $('input[name="novaposhta-address-street"]').autocomplete({
                source: '<?=SITE_URL.$_SESSION['alias']->alias?>/getAddresses/'+city,
                minLength: 2
            });
    }

    function initShipping() {
        $('input[name="nova-poshta-method"]').attr('required', true);

        $('input[name="novaposhta-city"]').autocomplete({
            source: '<?=SITE_URL.$_SESSION['alias']->alias?>/getcities/warehouse',
            minLength: 3,
            select: function (event, ui) {
                $('input[name="novaposhta-city"]').val(ui.item.value);
                setCity(ui.item.id);
            }
        }).attr('autocomplete', 'none');
        $('#nova-poshta-method div').click(function(){
            $(this).parent().find('div').removeClass('active');
            $(this).addClass('active');
            var tab = $(this).data('tab');
            $('input[name="nova-poshta-method"]').val(tab);
            $('input[name="nova-poshta-city-ref"], input[name="nova-poshta-warehouse-ref"], input[name="novaposhta-city"], input[name="novaposhta-address-street"]').val('');
            if(tab == 'warehouse')
            {
                $('input[name="novaposhta-city"]').autocomplete( "option", "source", '<?=SITE_URL.$_SESSION['alias']->alias?>/getcities/warehouse');
                $('#nova-poshta-warehouse').removeClass('hide');
                $('#nova-poshta-courier').addClass('hide');
                $('select[name="nova-poshta-warehouse"]').attr('required', true);
                $('input[name="novaposhta-address-street"], input[name="novaposhta-address-house"]').attr('required', false);
            }
            else
            {
                $('input[name="novaposhta-city"]').autocomplete( "option", "source", '<?=SITE_URL.$_SESSION['alias']->alias?>/getcities/courier');
                $('#nova-poshta-warehouse').addClass('hide');
                $('#nova-poshta-courier').removeClass('hide');
                $('select[name="nova-poshta-warehouse"]').attr('required', false);
                $('input[name="novaposhta-address-street"], input[name="novaposhta-address-house"]').attr('required', true);
            }
            if (typeof setPercents === "function")
                setPercents()
        });
        $("#nova-poshta-warehouse select").change(function() {
            var option = $(this).find(':selected');
            $('input[name="nova-poshta-warehouse-ref"]').val(option.data('id'));
            $("#nova-poshta-warehouse .info").html(option.data('info')).removeClass('hide')
            if (typeof setPercents === "function")
                setPercents();
        });
        $('input[name="novaposhta-address-street"], input[name="novaposhta-address-house"]').change(function() {
            if (typeof setPercents === "function")
                setPercents();
        });

        if (typeof setPercents === "function")
            setPercents();
    }
</script>

<style>
    #nova-poshta-method { border: 2px solid #7cbe49; border-radius: 2px; margin: 5px 0 }
    #nova-poshta-method div { padding: 5px; text-align: center; cursor: pointer }
    #nova-poshta-method div.active { background: #7cbe49; color: #fff }
    #nova-poshta-method div:hover { text-decoration: underline }
    #buyer { margin: 15px 0 }
    #nova-poshta-warehouse .info {
        border: #7cbe49 1px solid;
        padding: 5px 10px;
        border-radius: 2px;
        display: none;
    }
    #cart input.ui-autocomplete-loading { background: #eee url("<?=SERVER_URL?>style/images/ui-anim_basic_16x16.gif") right center no-repeat !important }
</style>