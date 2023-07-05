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
    <label for="novaposhta-city"><?= $this->text('Місто') ?></label>
    <input type="text" id="novaposhta-city" name="novaposhta-city" placeholder="<?= $this->text('Місто') ?>" value="<?= (!empty($userShipping->city_ref) && !empty($userShipping->city)) ? $userShipping->city : '' ?>" autocomplete="off" readonly required>
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

<div id="novaposhta-modal" class="modal">
    <i class="far fa-times-circle"></i>

    <div class="input-group">
        <label for="novaposhta-city"><?= $this->text('Місто') ?></label>
        <input type="text" placeholder="<?= $this->text('Місто') ?>" autocomplete="off" >
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
            courier: "<?= $this->text("Кур'єром") ?>"
        },

        popular_cities: [
            {id:"8d5a980d-391c-11dd-90d9-001a92567626", value: "Київ"},
            {id:"db5c88e0-391c-11dd-90d9-001a92567626", value: "Харків"},
            {id:"db5c88f0-391c-11dd-90d9-001a92567626", value: "Дніпро"},
            {id:"db5c88c6-391c-11dd-90d9-001a92567626", value: "Запоріжжя"},
            {id:"db5c88d0-391c-11dd-90d9-001a92567626", value: "Одеса"},
            {id:"db5c88f5-391c-11dd-90d9-001a92567626", value: "Львів"},
            {id:"db5c8944-391c-11dd-90d9-001a92567626", value: "Маріуполь"},
            {id:"db5c890d-391c-11dd-90d9-001a92567626", value: "Кривий Ріг"},
            {id:"db5c888c-391c-11dd-90d9-001a92567626", value: "Миколаїв"},
            {id:"db5c897c-391c-11dd-90d9-001a92567626", value: "Чернігів"},
            {id:"db5c88e5-391c-11dd-90d9-001a92567626", value: "Суми"},
            {id:"db5c88de-391c-11dd-90d9-001a92567626", value: "Вінниця"}
        ],

        warehouses: [],

        modal_show(mode = 'city') {
            let modal = $('#novaposhta-modal'),
                input_group = modal.find('.input-group'),
                input = input_group.find('input'),
                input_label = input_group.find('label'),
                h4 = modal.find('h4');

            this.modal_mode = mode;
            if(mode == 'city') {
                let city = $('#novaposhta-city').val();
                if(city == '') {
                    input_group.removeClass('val');
                }
                else {
                    input_group.addClass('val');
                }
                this.modal_drawLI(this.popular_cities);
                h4.text(this.modal_labels.city_popular).show();

                input.val(city).attr('placeholder', this.modal_labels.city);
                input.on('change input', NP.modal_inputCityChange);
                input_label.text(this.modal_labels.city);
            }
            if(mode == 'warehouse' || mode == 'postomat') {
                input_group.removeClass('val');
                input.val('').attr('placeholder', this.modal_labels[mode]);
                input_label.text(this.modal_labels[mode]);
                // h4.hide();

                this.modal_drawLI(this.warehouses);
            }

            $('#modal-bg').css('display', 'flex');
            modal.css('display', 'block');
        },

        modal_inputCityChange() {
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
                        $('#novaposhta-modal h4').hide();
                        input.removeClass('loading');
                    },
                    success: function(list) {
                        NP.modal_drawLI(list);
                    }
                })
            }
        },

        modal_drawLI(list) {
            $('#novaposhta-modal ul li').remove()
            list.forEach(function (el) {
                name = NP.modal_mode == 'city' ? el.value : el.name;
                title = NP.modal_mode == 'city' ? '' : el.title;
                $('<li/>', {
                    'data-id': el.id,
                    text: name,
                    title: title,
                    click: NP.modal_selectLI
                }).appendTo('#novaposhta-modal ul');
            })
        },

        modal_selectLI() {
            let id = $(this).data('id'),
                name = $(this).text();
            if(NP.modal_mode == 'city') {
                $('input[name="nova-poshta-city-ref"]').val(id);
                $('input[name="novaposhta-city"]').val(name);
                $('input[name="novaposhta-city"]').closest('.input-group').addClass('val');
                $('#novaposhta-modal h4').text(name);
                $('#novaposhta-modal input').addClass('loading');
                $('#novaposhta-modal ul li').remove();
                $('<li/>', { html: NP.loading_gif }).appendTo('#novaposhta-modal ul');

                $.ajax({
                    url: '<?= SITE_URL . $_SESSION['alias']->alias ?>/getWarehouses',
                    type: 'POST',
                    data: {
                        'city': id
                    },
                    success: function(warehouses) {
                        NP.warehouses = warehouses;
                        NP.modal_show(NP.method);
                    },
                    complete: function() {
                        $('#novaposhta-modal input').removeClass('loading');
                    },
                });
            }
        },

        modal_hide() {
            $('#modal-bg, #novaposhta-modal').css('display', 'none');
        },

        init() {
            $('input[name="novaposhta-city"]').click(function(){ NP.modal_show('city') });
            $('#novaposhta-modal i.fa-times-circle').click(NP.modal_hide);
        }
    }

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

    function initShipping() {
        $('input[name="nova-poshta-method"]').attr('required', true).change(function() {
            let method = $(this).val();
            NP.method = method;
            // $('input[name="nova-poshta-city-ref"], input[name="nova-poshta-warehouse-ref"], input[name="novaposhta-city"], input[name="novaposhta-address-street"]').val('');
            if (method == 'courier') {
                // $('input[name="novaposhta-city"]').autocomplete("option", "source", '<?= SITE_URL . $_SESSION['alias']->alias ?>/getcities/courier');
                $('#nova-poshta-warehouse').addClass('hide');
                $('#nova-poshta-courier').removeClass('hide');
                $('select[name="nova-poshta-warehouse"]').attr('required', false);
                $('input[name="novaposhta-address-street"], input[name="novaposhta-address-house"]').attr('required', true);
            } else {
                

                // $('input[name="novaposhta-city"]').autocomplete("option", "source", '<?= SITE_URL . $_SESSION['alias']->alias ?>/getcities/warehouse');
                $('#nova-poshta-warehouse').removeClass('hide');
                $('#nova-poshta-courier').addClass('hide');
                $('select[name="nova-poshta-warehouse"]').attr('required', true);
                $('input[name="novaposhta-address-street"], input[name="novaposhta-address-house"]').attr('required', false);
            }
        });

        NP.init();


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