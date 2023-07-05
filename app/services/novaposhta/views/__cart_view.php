<div style="margin-left: 10px; padding-left: 5px; border-left: 1px solid #ccc">
    <?php foreach (['warehouse' => 'На відділення', 'postomat' => 'Поштомат', 'courier' => "Кур'єром"] as $key => $key_title) {
        $checked = (empty($userShipping->method) && $key == 'warehouse') ? 'checked' : '';
        $checked = (!empty($userShipping->method) && $userShipping->method == $key) ? 'checked' : $checked; ?>
        <label <?= $checked ? 'class="active"' : '' ?>>
            <input type="radio" name="nova-poshta-method" value="<?= $key ?>" <?= $checked ?>>
            <?= $this->text($key_title) ?> <?= $checked ?>
        </label>
    <?php } ?>
</div>

<pre>
    <?php // print_r($userShipping); 
    ?>
</pre>

<input type="hidden" name="nova-poshta-city-ref" value="<?= $userShipping->city_ref ?? '' ?>">
<input type="hidden" name="nova-poshta-warehouse-ref" value="<?= $userShipping->warehouse_ref ?? '' ?>">
<input type="hidden" name="nova-poshta-address-street-ref" value="<?= $userShipping->address_street_ref ?? '' ?>">

<div class="input-group <?= (!empty($userShipping->city_ref) && !empty($userShipping->city)) ? 'val' : '' ?>">
    <label for="novaposhta-city"><?= $this->text('Місто') ?></label>
    <input type="text" id="novaposhta-city" name="novaposhta-city" placeholder="<?= $this->text('Місто') ?>" value="<?= (!empty($userShipping->city_ref) && !empty($userShipping->city)) ? $userShipping->city : '' ?>" autocomplete="off" readonly required>
</div>

<div id="nova-poshta-warehouse" class="input-group <?= (!empty($userShipping->method) && $userShipping->method == 'courier') ? 'hide' : '' ?> <?= (!empty($userShipping->warehouse_ref) && !empty($userShipping->warehouse)) ? 'val' : '' ?>">
    <label for="novaposhta-warehouse"><?= $this->text('Відділення') ?></label>
    <input type="text" id="novaposhta-warehouse" name="novaposhta-warehouse" placeholder="<?= $this->text('Відділення') ?>" value="<?= (!empty($userShipping->warehouse_ref) && !empty($userShipping->warehouse)) ? $userShipping->warehouse : '' ?>" autocomplete="off" readonly>
</div>

<div id="nova-poshta-courier" <?= (!empty($userShipping->method) && $userShipping->method == 'courier') ? '' : 'class="hide"' ?>>
    <div class="input-group <?= (!empty($userShipping->address_street)) ? 'val' : '' ?>">
        <label for="novaposhta-street"><?= $this->text('Вулиця') ?></label>
        <input type="text" id="novaposhta-street" name="novaposhta-address-street" placeholder="<?= $this->text('Вулиця') ?>" value="<?= $userShipping->address_street ?? '' ?>" <?= (!empty($userShipping->method) && $userShipping->method == 'courier') ? 'required' : '' ?>>
    </div>

    <div class="input-group <?= (!empty($userShipping->address_house)) ? 'val' : '' ?>">
        <label for="novaposhta-address_house"><?= $this->text('Номер будинку/та квартри') ?></label>
        <input type="text" id="novaposhta-address_house" name="novaposhta-address-house" placeholder="<?= $this->text('Номер будинку/та квартри') ?>" value="<?= $userShipping->address_house ?? '' ?>" <?= (!empty($userShipping->method) && $userShipping->method == 'courier') ? 'required' : '' ?>>
    </div>
</div>

<div id="novaposhta-modal" class="modal">
    <i class="far fa-times-circle"></i>

    <div class="input-group">
        <label for="novaposhta-modal-input"><?= $this->text('Місто') ?></label>
        <input id="novaposhta-modal-input" type="text" placeholder="<?= $this->text('Місто') ?>" autocomplete="off">
    </div>
    <h4></h4>
    <ul></ul>
</div>



<?php $novaposhta_selected = $this->data->re_post('shipping-novaposhta');
if (empty($novaposhta_selected) && $userShipping && $userShipping->department)
    $novaposhta_selected = $userShipping->department;
if ($userShipping && $userShipping->initShipping)
    $this->load->js_init('initShipping()'); ?>
<script>
    NP = {
        method: 'warehouse',
        modal_mode: 'city',
        loading_gif: '<img src="<?= SERVER_URL ?>style/images/icon-loading.gif" style="width:35px">',

        modal_labels: {
            city: '<?= $this->text('Місто') ?>',
            city_popular: '<?= $this->text('Популярні міста') ?>',
            warehouse: '<?= $this->text('Відділення') ?>',
            postomat: '<?= $this->text('Поштомат') ?>',
            courier: "<?= $this->text("Кур'єром") ?>",
            no_warehouse: '<?= $this->text('Відділення тимчасово не доступні') ?>',
            street: '<?= $this->text('Вулиця') ?>'
        },

        popular_cities: [{
                id: "8d5a980d-391c-11dd-90d9-001a92567626",
                value: "Київ"
            },
            {
                id: "db5c88e0-391c-11dd-90d9-001a92567626",
                value: "Харків"
            },
            {
                id: "db5c88f0-391c-11dd-90d9-001a92567626",
                value: "Дніпро"
            },
            {
                id: "db5c88c6-391c-11dd-90d9-001a92567626",
                value: "Запоріжжя"
            },
            {
                id: "db5c88d0-391c-11dd-90d9-001a92567626",
                value: "Одеса"
            },
            {
                id: "db5c88f5-391c-11dd-90d9-001a92567626",
                value: "Львів"
            },
            {
                id: "db5c8944-391c-11dd-90d9-001a92567626",
                value: "Маріуполь"
            },
            {
                id: "db5c890d-391c-11dd-90d9-001a92567626",
                value: "Кривий Ріг"
            },
            {
                id: "db5c888c-391c-11dd-90d9-001a92567626",
                value: "Миколаїв"
            },
            {
                id: "db5c897c-391c-11dd-90d9-001a92567626",
                value: "Чернігів"
            },
            {
                id: "db5c88e5-391c-11dd-90d9-001a92567626",
                value: "Суми"
            },
            {
                id: "db5c88de-391c-11dd-90d9-001a92567626",
                value: "Вінниця"
            }
        ],

        warehouses: null, //[],

        modal_show(mode = 'city') {
            let modal = $('#novaposhta-modal'),
                input_group = modal.find('.input-group'),
                input = input_group.find('input'),
                input_label = input_group.find('label'),
                h4 = modal.find('h4');

            if (!mode) {
                mode = 'city'
            }
            this.modal_mode = mode;
            input.off('input');

            console.log('modal_show: ' + mode);

            if (mode == 'city') {
                let city = $('#novaposhta-city').val();
                if (city == '') {
                    input_group.removeClass('val');
                } else {
                    input_group.addClass('val');
                }
                this.modal_drawLI(this.popular_cities);
                h4.text(this.modal_labels.city_popular).show();

                input.val(city).attr('placeholder', this.modal_labels.city);
                input.on('input', NP.modal_inputCityChange);
                input_label.text(this.modal_labels.city);
            }
            if (mode == 'warehouse' || mode == 'postomat') {
                input_group.removeClass('val');
                input.val('').attr('placeholder', this.modal_labels[mode]);
                input.on('input', NP.modal_inputWarehouseChange);
                input_label.text(this.modal_labels[mode]);

                if(this.warehouses === null) {
                    let city_ref = $('input[name="nova-poshta-city-ref"]').val();
                    this.modal_getWarehouses(city_ref);
                }
                else {
                    this.modal_drawLI(this.warehouses);
                }
                
            }
            if (mode == 'courier') {
                let street = $('input[name="novaposhta-address-street"]').val();
                if (!street) {
                    $('#novaposhta-modal ul li').remove();
                }

                h4.text($('input[name="novaposhta-city"]').val());
                input.val(street).attr('placeholder', this.modal_labels.street);
                input.on('input', NP.modal_inputStreetChange);
                input_label.text(this.modal_labels.street);
            }

            $('#modal-bg').css('display', 'flex');
            modal.css('display', 'block');
        },

        modal_inputCityChange() {
            var input = $(this);
            value = this.value;
            if (value.length > 2) {
                input.addClass('loading');
                $('#novaposhta-modal ul li').remove();
                $('<li/>', {
                    html: NP.loading_gif
                }).appendTo('#novaposhta-modal ul');
                $.ajax({
                    url: '<?= SITE_URL . $_SESSION['alias']->alias ?>/getcities/warehouse',
                    type: 'GET',
                    data: {
                        term: value
                    },
                    complete: function() {
                        input.removeClass('loading');
                    },
                    success: function(list) {
                        NP.modal_drawLI(list);
                    }
                })
            }
        },

        modal_inputWarehouseChange() {
            var value = this.value.toLowerCase();
            if (value.length) {
                let filtered = NP.warehouses.filter(warehouse => warehouse.name.toLowerCase().search(value) >= 0);
                NP.modal_drawLI(filtered);
            }
            else {
                NP.modal_drawLI(NP.warehouses);
            }
        },

        modal_inputStreetChange() {
            var input = $(this);
            value = this.value;
            if (value.length > 2) {
                input.addClass('loading');
                $('#novaposhta-modal ul li').remove();
                $('<li/>', {
                    html: NP.loading_gif
                }).appendTo('#novaposhta-modal ul');
                city_ref = $('input[name="nova-poshta-city-ref"]').val();
                $.ajax({
                    url: '<?= SITE_URL . $_SESSION['alias']->alias ?>/getAddresses/' + city_ref,
                    type: 'GET',
                    data: {
                        term: value
                    },
                    complete: function() {
                        input.removeClass('loading');
                    },
                    success: function(list) {
                        NP.modal_drawLI(list);
                    }
                })
            }
        },

        modal_getWarehouses(city_ref) {
            $('#novaposhta-modal input').addClass('loading');
            $('#novaposhta-modal ul li').remove();
            $('<li/>', {
                html: NP.loading_gif
            }).appendTo('#novaposhta-modal ul');

            $.ajax({
                url: '<?= SITE_URL . $_SESSION['alias']->alias ?>/getWarehouses',
                type: 'POST',
                data: {
                    'city': city_ref,
                    category: NP.method
                },
                success: function(warehouses) {
                    NP.warehouses = warehouses;
                    NP.modal_show(NP.method);
                },
                complete: function() {
                    $('#novaposhta-modal input').removeClass('loading');
                },
            });
        },

        modal_drawLI(list) {
            $('#novaposhta-modal ul li').remove();
            if (list.length) {
                list.forEach(function(el) {
                    name = NP.modal_mode == 'city' || NP.modal_mode == 'courier' ? el.value : el.name;
                    title = NP.modal_mode == 'city' ? '' : el.title;
                    $('<li/>', {
                        'data-id': el.id,
                        text: name,
                        title: title,
                        click: NP.modal_selectLI
                    }).appendTo('#novaposhta-modal ul');
                })
            } else if (NP.modal_mode == 'warehouse' || NP.modal_mode == 'postomat') {
                $('<li/>', {
                    text: NP.modal_labels.no_warehouse,
                    click: function() {
                        NP.modal_show('city')
                    }
                }).appendTo('#novaposhta-modal ul');
            }
        },

        modal_selectLI() {
            let id = $(this).data('id'),
                name = $(this).text();
            console.log('modal_selectLI: ' + NP.modal_mode);
            if (NP.modal_mode == 'city') {
                $('input[name="nova-poshta-city-ref"]').val(id);
                $('input[name="novaposhta-city"]').val(name);
                $('input[name="novaposhta-city"]').closest('.input-group').addClass('val');
                $('#novaposhta-modal h4').text(name);
                
                NP.modal_getWarehouses(id);
            }
            if (NP.modal_mode == 'warehouse' || NP.modal_mode == 'postomat') {
                $('input[name="nova-poshta-warehouse-ref"]').val(id);
                $('input[name="novaposhta-warehouse"]').val(name);
                $('input[name="novaposhta-warehouse"]').closest('.input-group').addClass('val');
                NP.modal_hide();
            }
            if (NP.modal_mode == 'courier') {
                $('input[name="nova-poshta-address-street-ref"]').val(id);
                $('input[name="novaposhta-address-street"]').val(name);
                $('input[name="novaposhta-address-street"]').closest('.input-group').addClass('val');
                $('input[name="novaposhta-address_house"]').focus();
                NP.modal_hide();
            }
        },

        modal_hide() {
            $('#modal-bg, #novaposhta-modal').css('display', 'none');
        },

        changeMethod() {
            NP.method = $(this).val();
            // $('input[name="nova-poshta-city-ref"], input[name="nova-poshta-warehouse-ref"], input[name="novaposhta-city"], input[name="novaposhta-address-street"]').val('');
            if (NP.method == 'warehouse' || NP.method == 'postomat') {
                $('#nova-poshta-warehouse').removeClass('hide');
                $('#nova-poshta-courier').addClass('hide');
                $('input[name="novaposhta-warehouse"]').attr('required', true);
                $('input[name="novaposhta-address-street"], input[name="novaposhta-address-house"]').attr('required', false);
                $('#nova-poshta-warehouse label').text(NP.modal_labels[NP.method]);
                $('#nova-poshta-warehouse input').val('').attr('placeholder', NP.modal_labels[NP.method]);

                NP.warehouses = null;

                // $('input[name="novaposhta-city"]').autocomplete("option", "source", '<?= SITE_URL . $_SESSION['alias']->alias ?>/getcities/warehouse');
            }
            if (NP.method == 'courier') {
                $('#nova-poshta-warehouse').addClass('hide');
                $('#nova-poshta-courier').removeClass('hide');
                $('input[name="novaposhta-warehouse"]').attr('required', false);
                $('input[name="novaposhta-address-street"], input[name="novaposhta-address-house"]').attr('required', true);

                // $('input[name="novaposhta-city"]').autocomplete("option", "source", '<?= SITE_URL . $_SESSION['alias']->alias ?>/getcities/courier');
            }
        },

        init() {
            NP.method = $('input[name="nova-poshta-method"]').val();
            $('input[name="nova-poshta-method"]').attr('required', true).change(NP.changeMethod);

            $('input[name="novaposhta-city"]').click(function() {
                NP.modal_show('city')
            });
            $('input[name="novaposhta-warehouse"], input[name="novaposhta-address-street"]').click(function() {
                if ($('input[name="novaposhta-city"]').val()) {
                    NP.modal_show(NP.method);
                } else {
                    NP.modal_show('city')
                }
            });
            $('#novaposhta-modal i.fa-times-circle').click(NP.modal_hide);
        }
    }

    window.onload = function() {};

    function initShipping() {
        NP.init();

        // if (typeof setPercents === "function")
        //     setPercents();
    }
</script>

<style>
    #cart input.ui-autocomplete-loading {
        background: #eee url("<?= SERVER_URL ?>style/images/ui-anim_basic_16x16.gif") right center no-repeat !important
    }

    #novaposhta-modal {
        padding-top: 20px;
    }

    #novaposhta-modal i.fa-times-circle {
        position: absolute;
        right: 10px;
        top: 10px;
        cursor: pointer;
    }

    #novaposhta-modal input.loading {
        background: #eee url("<?= SERVER_URL ?>style/images/ui-anim_basic_16x16.gif") right center no-repeat !important
    }

    #novaposhta-modal ul {
        padding: 0;
    }

    #novaposhta-modal li {
        padding-left: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
        margin-bottom: 5px;
        cursor: pointer;
        list-style: none;
    }

    @media screen and (max-width: 600px) {
        #novaposhta-modal {
            left: 1%;
            width: 98%;
        }
    }
</style>