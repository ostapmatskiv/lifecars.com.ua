<div style="margin-left: 10px; padding-left: 5px; border-left: 1px solid #ccc">
    <?php foreach (['warehouse' => 'На відділення', 'postomat' => 'Поштомат', 'courier' => "Кур'єром"] as $key => $key_title) {
        $checked = (!empty($userShipping->method) && $userShipping->method == $key) ? '' : 'checked'; ?>
        <label <?= $checked ? 'class="active"' : '' ?>>
            <input type="radio" name="nova-poshta-method" value="<?= $key ?>" <?= $checked ?>>
            <?= $this->text($key_title) ?>
        </label>
    <?php } ?>
</div>



<input type="hidden" name="nova-poshta-city-ref" value="<?= $userShipping->city_ref ?? '' ?>">
<input type="hidden" name="nova-poshta-warehouse-ref" value="<?= $userShipping->warehouse_ref ?? '' ?>">

<div class="input-group <?= (!empty($userShipping->city_ref) && !empty($userShipping->city)) ? 'val' : '' ?>">
    <label for="novaposhta-city-in1"><?= $this->text('Місто') ?></label>
    <input type="text" id="novaposhta-city-in1" name="novaposhta-city" placeholder="<?= $this->text('Місто') ?>" value="<?= (!empty($userShipping->city_ref) && !empty($userShipping->city)) ? $userShipping->city : '' ?>" autocomplete="off" readonly required>
</div>

<div id="nova-poshta-warehouse" <?= (!empty($userShipping->method) && $userShipping->method == 'courier') ? 'class="hide"' : '' ?>>
    <div class="input-group val">
        <label><?= $this->text('Відділення') ?></label>
        <select name="nova-poshta-warehouse" class="" <?= (!empty($userShipping->method) && $userShipping->method == 'courier') ? '' : 'required' ?>>
            <?php $info = '';
            if (!empty($userShipping->city_ref) && !empty($userShipping->warehouse_ref)) {
                if ($warehouses = $this->getWarehouses($userShipping->city_ref))
                    foreach ($warehouses as $warehouse) {
                        $selected = '';
                        if ($warehouse->id == $userShipping->warehouse_ref) {
                            $selected = 'selected';
                            $info = $warehouse->info;
                        }
                        echo '<option data-id="' . $warehouse->id . '" data-info="' . htmlspecialchars($warehouse->info) . '" title="' . $warehouse->title . '" ' . $selected . '>' . $warehouse->name . '</option>';
                    }
            } else { ?>
                <option selected disabled value=""><?= $this->text('Для вибору відділення спершу введіть місто') ?></option>
            <?php } ?>
        </select>

        <div class="info <?= empty($info) ? 'hide' : '' ?>"><?= $info ?></div>
    </div>
</div>

<div id="nova-poshta-courier" <?= (!empty($userShipping->method) && $userShipping->method == 'courier') ? '' : 'class="hide"' ?>>
    <!-- <label><?= $this->text('Вулиця') ?></label> -->
    <input type="text" name="novaposhta-address-street" class="form-control" placeholder="<?= $this->text('Вулиця') ?>" value="<?= $userShipping->address_street ?? '' ?>" <?= (!empty($userShipping->method) && $userShipping->method == 'courier') ? 'required' : '' ?>>
    <input type="text" name="novaposhta-address-house" class="form-control" placeholder="<?= $this->text('Номер будинку/та квартри') ?>" value="<?= $userShipping->address_house ?? '' ?>" <?= (!empty($userShipping->method) && $userShipping->method == 'courier') ? 'required' : '' ?>>
</div>

<div id="novaposhta-city-modal" class="modal">
    <i class="far fa-times-circle"></i>

    <div class="input-group <?= (!empty($userShipping->city_ref) && !empty($userShipping->city)) ? 'val' : '' ?>">
        <label for="novaposhta-city"><?= $this->text('Місто') ?></label>
        <input type="text" id="novaposhta-city-in_modal" placeholder="<?= $this->text('Місто') ?>" autocomplete="off" >
    </div>
    <h4>Популярні міста</h4>
    <ul>
        <li data-id="8d5a980d-391c-11dd-90d9-001a92567626">Київ</li>
        <li data-id="db5c88e0-391c-11dd-90d9-001a92567626">Харків</li>
        <li data-id="db5c88f0-391c-11dd-90d9-001a92567626">Дніпро</li>
        <li data-id="db5c88c6-391c-11dd-90d9-001a92567626">Запоріжжя</li>
        <li data-id="db5c88d0-391c-11dd-90d9-001a92567626">Одеса</li>
        <li data-id="db5c88f5-391c-11dd-90d9-001a92567626">Львів</li>
        <li data-id="db5c8944-391c-11dd-90d9-001a92567626">Маріуполь</li>
        <li data-id="db5c890d-391c-11dd-90d9-001a92567626">Кривий Ріг</li>
        <li data-id="db5c888c-391c-11dd-90d9-001a92567626">Миколаїв</li>
        <li data-id="db5c897c-391c-11dd-90d9-001a92567626">Чернігів</li>
        <li data-id="db5c88e5-391c-11dd-90d9-001a92567626">Суми</li>
        <li data-id="db5c88de-391c-11dd-90d9-001a92567626">Вінниця</li>
    </ul>
</div>



<?php $novaposhta_selected = $this->data->re_post('shipping-novaposhta');
if (empty($novaposhta_selected) && $userShipping && $userShipping->department)
    $novaposhta_selected = $userShipping->department;
if ($userShipping && $userShipping->initShipping)
    $this->load->js_init('initShipping()'); ?>
<script>
    window.onload = function() {

    };

    function setCity(city) {
        $("#nova-poshta-warehouse select").empty().append('<option selected disabled="" value="">Виберіть відділення</option>');
        $("#nova-poshta-warehouse .info").addClass('hide');
        $('input[name="nova-poshta-city-ref"]').val(city);
        if ($('input[name="nova-poshta-method"]').val() == 'warehouse') {
            $("div#divLoading").addClass('show');
            $.ajax({
                url: '<?= SITE_URL . $_SESSION['alias']->alias ?>/getWarehouses',
                type: 'POST',
                data: {
                    'city': city
                },
                success: function(warehouses) {
                    if (warehouses)
                        warehouses.forEach(function(warehous) {
                            $('<option/>', {
                                text: warehous.name,
                                title: warehous.title,
                                'data-id': warehous.id,
                                'data-info': warehous.info
                            }).appendTo($("#nova-poshta-warehouse select"))
                        });
                },
                complete: function() {
                    $("div#divLoading").removeClass('show');
                }
            })
        } else
            $('input[name="novaposhta-address-street"]').autocomplete({
                source: '<?= SITE_URL . $_SESSION['alias']->alias ?>/getAddresses/' + city,
                minLength: 2
            });
    }

    function selectCity() {
        var id = $(this).data('id'),
            name = $(this).text();
        $('input[name="novaposhta-city"]').val(name);
        $('input[name="novaposhta-city"]').closest('.input-group').addClass('val');
        setCity(id);
        $('#modal-bg, #novaposhta-city-modal').css('display', 'none');

        console.log(id + ' ' + name);
    }

    function initShipping() {
        $('input[name="nova-poshta-method"]').attr('required', true);

        $('input[name="novaposhta-city"]').click(function() {
            $('#modal-bg').css('display', 'flex');
            $('#novaposhta-city-modal').css('display', 'block');
            $('#novaposhta-city-in_modal').val( $('#novaposhta-city-in1').val() );
        });

        $('#novaposhta-city-modal i.fa-times-circle').click(function(){
            $('#modal-bg, #novaposhta-city-modal').css('display', 'none');
        });

        $('#novaposhta-city-modal ul li').click(selectCity);

        $('input#novaposhta-city-in_modal').on('change input', function(){
            var input = $(this);
                value = this.value;
            if(value.length > 2) {
                input.addClass('loading');
                $.ajax({
                    url: '<?= SITE_URL . $_SESSION['alias']->alias ?>/getcities/warehouse',
                    type: 'GET',
                    data: {
                        term: value
                    },
                    complete: function() {
                        $('#novaposhta-city-modal h4').hide();
                        input.removeClass('loading');
                    },
                    success: function(list) {
                        $('#novaposhta-city-modal ul li').remove()
                        list.forEach(function (el) {
                            $('<li/>', {
                                'data-id': el.id,
                                text: el.value,
                                click: selectCity
                            }).appendTo('#novaposhta-city-modal ul');
                        })
                    }
                })
            }
        })

        // $('input#novaposhta-city-in_modal').autocomplete({
        //     source: '<?= SITE_URL . $_SESSION['alias']->alias ?>/getcities/warehouse',
        //     minLength: 3,
        //     select: function(event, ui) {
        //         $('input[name="novaposhta-city"]').val(ui.item.value);
        //         $('input#novaposhta-city-in_modal').val(ui.item.value);
        //         setCity(ui.item.id);
        //     }
        // }).attr('autocomplete', 'none');

        $('#nova-poshta-method div').click(function() {
            $(this).parent().find('div').removeClass('active');
            $(this).addClass('active');
            var tab = $(this).data('tab');
            $('input[name="nova-poshta-method"]').val(tab);
            $('input[name="nova-poshta-city-ref"], input[name="nova-poshta-warehouse-ref"], input[name="novaposhta-city"], input[name="novaposhta-address-street"]').val('');
            if (tab == 'warehouse') {
                $('input[name="novaposhta-city"]').autocomplete("option", "source", '<?= SITE_URL . $_SESSION['alias']->alias ?>/getcities/warehouse');
                $('#nova-poshta-warehouse').removeClass('hide');
                $('#nova-poshta-courier').addClass('hide');
                $('select[name="nova-poshta-warehouse"]').attr('required', true);
                $('input[name="novaposhta-address-street"], input[name="novaposhta-address-house"]').attr('required', false);
            } else {
                $('input[name="novaposhta-city"]').autocomplete("option", "source", '<?= SITE_URL . $_SESSION['alias']->alias ?>/getcities/courier');
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
    #nova-poshta-method {
        border: 2px solid #7cbe49;
        border-radius: 2px;
        margin: 5px 0
    }
    #nova-poshta-method div {
        padding: 5px;
        text-align: center;
        cursor: pointer
    }
    #nova-poshta-method div.active {
        background: #7cbe49;
        color: #fff
    }
    #nova-poshta-method div:hover {
        text-decoration: underline
    }
    #buyer {
        margin: 15px 0
    }
    #nova-poshta-warehouse .info {
        border: #7cbe49 1px solid;
        padding: 5px 10px;
        border-radius: 2px;
        display: none;
    }
    #cart input.ui-autocomplete-loading {
        background: #eee url("<?= SERVER_URL ?>style/images/ui-anim_basic_16x16.gif") right center no-repeat !important
    }

    #novaposhta-city-modal {
        padding-top: 20px;
    }
    #novaposhta-city-modal i.fa-times-circle {
        position: absolute;
        right: 10px;
        top: 10px;
        cursor: pointer;
    }
    #novaposhta-city-modal input.loading {
        background: #eee url("<?= SERVER_URL ?>style/images/ui-anim_basic_16x16.gif") right center no-repeat !important
    }
    #novaposhta-city-modal ul {
        padding: 0;
    }
    #novaposhta-city-modal li {
        padding-left: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
        margin-bottom: 5px;
        cursor: pointer;
        list-style: none;
    }
    @media screen and (max-width: 600px) {
        #novaposhta-city-modal {
            left: 1%;
            width: 98%;
        }
    }

</style>