<?php

/*

 	Service "Shop Cart 2.5"
	for WhiteLion 1.1

*/

class cart extends Controller {

    private $marketing = array();
    private $use__profile_view = false; // for list, detal subview

    function __construct()
    {
        parent::__construct();
        if(empty($_SESSION['cart']))
            $_SESSION['cart'] = new stdClass();
        $_SESSION['cart']->initJsStyle = true;

        // if($cooperation = $this->db->getAllDataByFieldInArray('wl_aliases_cooperation', $_SESSION['alias']->id, 'alias1'))
        //     foreach ($cooperation as $c) {
        //         if($c->type == 'marketing')
        //             $this->marketing[] = $c->alias2;
        //     }
    }

    function _remap($method, $data = array())
    {
        if (method_exists($this, $method)) {
        	if(empty($data)) $data = null;
            return $this->$method($data);
        } else {
        	$this->index($method);
        }
    }

    public function index()
    {
        $this->load->smodel('cart_model');

        if($id = $this->data->uri(1))
        {
            if(is_numeric($id) && $id > 0)
            {
                if($this->userIs() || $this->data->get('key'))
                    $this->__view_order_by_id($id);
                else
                    $this->redirect('login');
            }
            else
                $this->load->page_404();
        }

        // for login by facebook or google
        if($this->userIs())
        {
            $cart_user_id = $this->cart_model->getUser(false);
            if($cart_user_id != $_SESSION['user']->id)
            {
                if($this->db->getCount($this->cart_model->table('_products'), array('cart' => 0, 'user' => $cart_user_id)))
                    $this->db->updateRow($this->cart_model->table('_products'), array('user' => $_SESSION['user']->id), array('cart' => 0, 'user' => $cart_user_id));
                $_SESSION['cart']->user = $_SESSION['user']->id;
            }
        }

        $this->wl_alias_model->setContent();
        $res = array('subTotal' => 0, 'subTotalFormat' => '', 'discountTotal' => 0);
        if($res['products'] = $this->cart_model->getProductsInCart(0, 0))
        {
            $res['products'] = $this->setProductsInfo($res['products']);
            $res['subTotal'] = $this->cart_model->getSubTotalInCart();
            $res['subTotalFormat'] = $this->load->function_in_alias($res['products'][0]->product_alias, '__formatPrice', $res['subTotal']);
            if($this->cart_model->discountTotal)
                $res['discountTotal'] = $this->load->function_in_alias($res['products'][0]->product_alias, '__formatPrice', $this->cart_model->discountTotal);
        }
        $this->load->page_view('index_view', $res);
    }

    private function __view_order_by_id($id, $return = false)
    {
        $this->load->smodel('cart_model');
        if($cart = $this->cart_model->getById($id))
        {
            if(!$return)
            {
                $this->wl_alias_model->setContent($id);
                $_SESSION['alias']->name = $_SESSION['alias']->title = $this->text('Замовлення №').$id;
            }
            $go = false;
            if($this->userIs() && $cart->user == $_SESSION['user']->id || $this->userCan())
                $go = true;
            else if($key = $this->data->get('key'))
                if($user = $this->db->getAllDataById('wl_users', $key, 'auth_id'))
                    if($cart->user == $user->id)
                        $go = true;
            if($go)
            {
                if(!$return)
                    $_SESSION['alias']->breadcrumbs = array($this->text('До всіх замовлень') => $_SESSION['alias']->alias.'/my', $this->text('Замовлення №').$id => '');

                $cart->totalFormat = $cart->total;
                $cart->subTotal = $cart->subTotalFormat = $cart->shippingPrice = $cart->shippingPriceFormat = 0;
                $cart->shipping = $cart->payment = false;

                if($cart->shipping_id && !empty($cart->shipping_info))
                {
                    $cart->shipping_info = unserialize($cart->shipping_info);
                    if($cart->shipping = $this->cart_model->getShippings(array('id' => $cart->shipping_id)))
                    {
                        $cart->shipping = $cart->shipping[0];
                        if($_SESSION['language'])
                        {
                            @$name = unserialize($cart->shipping->name);
                            if(isset($name[$_SESSION['language']]))
                                $cart->shipping->name = $name[$_SESSION['language']];
                            else if(is_array($name))
                                $cart->shipping->name = array_shift($name);
                            @$info = unserialize($cart->shipping->info);
                            if(isset($info[$_SESSION['language']]))
                                $cart->shipping->info = $info[$_SESSION['language']];
                            else if(is_array($info))
                                $cart->shipping->info = array_shift($info);
                        }
                        $cart->shipping->text = '';
                        if($cart->shipping->wl_alias)
                            $cart->shipping->text = $this->load->function_in_alias($cart->shipping->wl_alias, '__get_info', $cart->shipping_info);  
                    }
                    if(!empty($cart->shipping_info['price']))
                        $cart->shippingPrice = $cart->shipping_info['price'];
                }

                if($cart->products)
                {
                    $shop_alias = $cart->products[0]->product_alias;
                    if(empty($shop_alias))
                    {
                        if($row = $this->db->select('s_cart_products', 'product_alias', ['product_alias' => '>0'])->limit(1)->get())
                            $shop_alias = $row->product_alias;
                    }
                    foreach ($cart->products as $product) {
                        if(!empty($product->product_alias))
                        {
                            $product->info = $this->load->function_in_alias($product->product_alias, '__get_Product', $product->product_id);
                            if($product->storage_invoice)
                                $product->storage = $this->load->function_in_alias($product->storage_alias, '__get_Invoice', array('id' => $product->storage_invoice, 'user_type' => $cart->user_type));
                            $product->price_format =  $this->load->function_in_alias($product->product_alias, '__formatPrice', $product->price);
                            $cart->subTotal += $product->price * $product->quantity + $product->discount;
                            $product->sum_format = $this->load->function_in_alias($product->product_alias, '__formatPrice', $product->price * $product->quantity);
                            if($product->discount)
                                $product->sum_before_format = $this->load->function_in_alias($product->product_alias, '__formatPrice', $product->price * $product->quantity + $product->discount);
                        }
                        else if(!empty($product->product_options))
                        {
                            $options = unserialize($product->product_options);
                            $product->info = new stdClass();
                            $product->info->id = $product->id;
                            $product->info->photo = $options['photo'];
                            $product->info->cart_photo = $product->info->admin_photo = IMG_PATH.$options['cart_photo'];
                            $product->info->article = $product->info->article_show = $options['article'];
                            $product->info->name = $options['name'];
                            $product->info->link = $options['photo'] ?? '';
                            $cart->subTotal += $product->price * $product->quantity + $product->discount;
                            if($shop_alias)
                            {
                                $product->price_format = $this->load->function_in_alias($shop_alias, '__formatPrice', $product->price);
                                $product->sum_format = $this->load->function_in_alias($shop_alias, '__formatPrice', $product->price * $product->quantity);
                            }
                            else
                            {
                                $product->price_format = $product->price;
                                $product->sum_format = $product->price * $product->quantity;
                            }
                            $product->product_options = false;
                        }
                    }

                    $cart->subTotalFormat = $cart->subTotal;
                    $cart->discountFormat = $cart->discount;
                    $cart->shippingPriceFormat = $cart->shippingPrice;
                    $cart->totalFormat = $cart->total;
                    $cart->payedFormat = $cart->payed;
                    if($shop_alias)
                    {
                        if ($cart->subTotal != $cart->total)
                            $cart->subTotalFormat = $this->load->function_in_alias($shop_alias, '__formatPrice', $cart->subTotal);
                        $cart->totalFormat = $this->load->function_in_alias($shop_alias, '__formatPrice', $cart->total);
                        if($cart->discount)
                            $cart->discountFormat = $this->load->function_in_alias($shop_alias, '__formatPrice', $cart->discount);
                        if($cart->shippingPrice)
                            $cart->shippingPriceFormat = $this->load->function_in_alias($shop_alias, '__formatPrice', $cart->shippingPrice);
                    }
                    if($cart->payed > 0 && $cart->payed < $cart->total)
                    {
                        $cart->toPay = $cart->toPayFormat = $cart->total - $cart->payed;
                        if($shop_alias)
                        {
                            $cart->payedFormat = $this->load->function_in_alias($shop_alias, '__formatPrice', $cart->payed);
                            $cart->toPayFormat = $this->load->function_in_alias($shop_alias, '__formatPrice', $cart->toPay);
                        }
                    }
                    else if($cart->payed < $cart->total)
                    {
                        $cart->toPay = $cart->toPayFormat = $cart->total;
                        $cart->payedFormat = 0;
                        if($shop_alias)
                            $cart->toPayFormat = $this->load->function_in_alias($shop_alias, '__formatPrice', $cart->toPay);
                    }
                    else if($cart->payed > $cart->total)
                    {
                        $cart->toPay = $cart->toPayFormat = 0;
                        if($shop_alias)
                            $cart->payedFormat = $this->load->function_in_alias($shop_alias, '__formatPrice', $cart->payed);
                    }
                }
                
                if($cart->payment_alias && $cart->payment_id)
                    $cart->payment = $this->load->function_in_alias($cart->payment_alias, '__get_info', $cart->payment_id);
                else if($cart->payment_id)
                {
                    $cart->payment = $this->cart_model->getPayments(array('id' => $cart->payment_id));
                    if($cart->payment)
                        $cart->payment = $cart->payment[0];
                }

                if($return)
                {
                    $cart->return_url = $_SESSION['alias']->alias.'/success?order='.$cart->id;
                    return $cart;
                }

                if($this->data->uri(2) == 'print')
                    $this->load->view('print_view', array('cart' => $cart));
                elseif($this->data->uri(2) == 'pay' && !empty($cart->toPay))
                {
                    if($payments = $this->cart_model->getPayments(array('active' => 1, 'wl_alias' => '>0')))
                    {
                        $cooperation_where['alias1'] = $_SESSION['alias']->id;
                        $cooperation_where['type'] = 'payment';
                        $ntkd = array('alias' => '#c.alias2', 'content' => 0);
                        if($_SESSION['language'])
                            $ntkd['language'] = $_SESSION['language'];
                        $payments = $this->db->select('wl_aliases_cooperation as c', 'alias2 as id', $cooperation_where)
                                                ->join('s_cart_payments', 'name as payname, info as payinfo', ['wl_alias' => '#c.alias2'])
                                                ->join('wl_ntkd', 'name, list as info', $ntkd)
                                                ->get('array');
                        if(count($payments) == 1)
                        {
                            $accessKey = '';
                            if(!$this->userIs() && $this->data->get('key'))
                                $accessKey = '&key='.$this->data->get('key');
                            $this->redirect($_SESSION['alias']->alias.'/pay?cart='.$cart->id.'&method='.$payments[0]->id.$accessKey);
                        }
                        else
                        {
                            foreach ($payments as $pay) {
                                if(!empty($pay->payname))
                                {
                                    if($_SESSION['language'])
                                    {
                                        $name = unserialize($pay->payname);
                                        if(isset($name[$_SESSION['language']]))
                                            $pay->name = $name[$_SESSION['language']];
                                    }
                                    else
                                        $pay->name = $pay->payname;
                                }
                            }
                            if($this->userIs() && $this->use__profile_view)
                                $this->load->profile_view('pay_view', array('cart' => $cart, 'payments' => $payments));
                            else
                                $this->load->page_view('pay_view', array('cart' => $cart, 'payments' => $payments));
                        }
                    }
                    else
                        $this->redirect($_SESSION['alias']->alias.'/'.$cart->id);
                }
                else
                {
                    $showPayment = false;
                    if(!empty($_SESSION['notify']->meta))
                        $_SESSION['alias']->meta = $_SESSION['notify']->meta;

                    if($cart->payed < $cart->total)
                        if($payments = $this->cart_model->getPayments(array('active' => 1, 'wl_alias' => '>0')))
                            $showPayment = true;
                    if($this->userIs() && $this->use__profile_view)
                        $this->load->profile_view('detal_view', array('cart' => $cart, 'showPayment' => $showPayment));
                    else
                        $this->load->page_view('detal_view', array('cart' => $cart, 'showPayment' => $showPayment, 'echoContainer' => true));
                }
                exit;
            }
            else
                $this->load->notify_view(array('errors' => $this->text('Немає прав для перегляду даного замовлення.')));
        }
        else if ($return)
            return false;
        else
            $this->load->page_404(false);
    }

    public function my($user = 0)
    {
        if($this->userIs())
        {
            $this->wl_alias_model->setContent();
            $_SESSION['alias']->link = implode('/', $this->data->url());
            $_SESSION['alias']->name = $this->text('Мої замовлення');

            if($user == 0)
                $user = $_SESSION['user']->id;
            if($id = $this->data->uri(2))
            {
                if($this->userCan() && is_numeric($id))
                    $user = $id;
                else
                    $this->load->page_404(false);
            }

            $showPayment = false;
            $this->load->smodel('cart_model');
            $orders = $this->cart_model->getCarts(array('user' => $user));
            if($orders)
            {
                $check_payments = false;
                foreach ($orders as &$order) {
                    if($order->shipping_name_ntkd)
                        $order->shipping_name = $order->shipping_name_ntkd;
                    else if($order->shipping_name && $_SESSION['language'])
                    {
                        $name = @unserialize($order->shipping_name);
                        $order->shipping_name = $name[$_SESSION['language']] ?? $order->shipping_name;
                    }
                    if(!empty($order->shipping_info))
                    {
                        $shipping_info = @unserialize($order->shipping_info);
                        if(is_array($shipping_info))
                            $order->shipping_info = $shipping_info;
                    }

                    if($order->status_weight < 90)
                        $check_payments = true;
                    if($order->total && !empty($order->products))
                    {
                        $order->total_format = $order->total;
                        if($order->products[0]->product_alias)
                            $order->total_format = $this->load->function_in_alias($order->products[0]->product_alias, '__formatPrice', $order->total);
                    }
                    else
                        $order->total_format = 0;
                }
                
                if($check_payments)
                    if($status = $this->db->getAllDataByFieldInArray($this->cart_model->table('_status'), 10, 'weight'))
                        if($payments = $this->cart_model->getPayments(array('active' => 1, 'wl_alias' => '>0')))
                            $showPayment = true;
            }

            if($this->use__profile_view)
                $this->load->profile_view('list_view', array('orders' => $orders, 'showPayment' => $showPayment));
            else
                $this->load->page_view('list_view', array('orders' => $orders, 'showPayment' => $showPayment));
            exit;
        }
        else
            $this->redirect('login');
    }

    public function addProduct($return_product = false)
    {
        $res = array('result' => false, 'subTotal' => 0);
        if($this->data->post('productKey') && $this->data->post('quantity') != 0)
        {
            $wl_alias = $id = $storage_alias = $storage_id = 0;
            $key = explode('-', $this->data->post('productKey'));
            if(count($key) >= 2 && is_numeric($key[0]) && is_numeric($key[1]))
            {
                $wl_alias = $key[0];
                $id = $key[1];
                if(isset($key[3]) && is_numeric($key[2]) && is_numeric($key[3]))
                {
                    $storage_alias = $key[2];
                    $storage_id = $key[3];
                }
            }
            $quantity = $this->data->post('quantity');

            if($id > 0 && is_numeric($quantity) && $quantity > 0)
            {
                $where = array('id' => $id);

                $product_options = $changePrice = array();
                if(!empty($_POST['options']) && is_array($_POST['options']))
                    foreach ($_POST['options'] as $option_id => $option_value) {
                        if(is_numeric($option_id) && is_numeric($option_value))
                        {
                            if($info = $this->load->function_in_alias($wl_alias, '__get_Option_Info', $option_id))
                            {
                                $my_option = new stdClass();
                                $my_option->id = $info->id;
                                $my_option->changePrice = $info->changePrice;
                                $my_option->name = $info->name;
                                $my_option->value_id = $option_value;
                                $my_option->value_name = '';
                                if(!empty($info->values))
                                    foreach ($info->values as $value) {
                                        if($value->id == $option_value)
                                        {
                                            $my_option->value_name = $value->name;
                                            break;
                                        }
                                    }
                                if(isset($info->changePrice) && $info->changePrice)
                                    $changePrice[$info->id] = $option_value;
                                $product_options[] = $my_option;
                            }
                        }
                    }
                if(!empty($changePrice))
                    $where['options'] = $changePrice;
                $where['additionalFileds'] = array('quantity' => $quantity);

                if($product = $this->load->function_in_alias($wl_alias, '__get_Product', $where))
                {
                    $product->name = html_entity_decode($product->name, ENT_QUOTES, 'utf-8');
                    $product->product_options = $product_options;
                    $product->storage_alias = $product->storage_invoice = 0;
                    if($storage_alias && $storage_id)
                        if($invoice = $this->load->function_in_alias($storage_alias, '__get_Invoice', $storage_id))
                        {
                            $product->storage_alias = $storage_alias;
                            $product->storage_invoice = $storage_id;
                            if($invoice->price_out)
                            {
                                $product->price = $invoice->price_out;
                                $product->price_format = 0;
                            }
                            if($invoice->price_in)
                                $product->price_in = $invoice->price_in;
                            if($invoice->amount_free < $product->quantity)
                                $product->quantity = $invoice->amount_free;
                            $product->sum_format = 0;
                        }
                    if(isset($product->discount))
                    	$product->discount *= $product->quantity;

                    $product->sum = $product->price * $product->quantity;
                    if(empty($product->price_format))
                        $product->price_format = $this->load->function_in_alias($wl_alias, '__formatPrice', $product->price);
                    if(empty($product->sum_format))
                        $product->sum_format = $this->load->function_in_alias($wl_alias, '__formatPrice', $product->sum);

                    if($return_product)
                        return $product;

                    $this->load->smodel('cart_model');
                    if($product->key = $this->cart_model->addProduct($product))
                    {
                        $openProduct = new stdClass();
                        $openProduct->key = $product->key;
                        $openProduct->article = $product->article_show ?? $product->article;
                        $openProduct->price_format = $product->price_format;
                        $openProduct->sum_format = $product->sum_format;
                        $openProduct->quantity = $product->quantity;
                        $openProduct->photo = $product->photo ?? false;
                        $openProduct->admin_photo = !empty($product->admin_photo) ? IMG_PATH.$product->admin_photo : false;
                        $openProduct->cart_photo = !empty($product->cart_photo) ? IMG_PATH.$product->cart_photo : false;
                        $openProduct->link = $product->link;
                        $openProduct->name = $product->name;
                        $openProduct->product_options = '';
                        if(!empty($product->product_options))
                            foreach ($product->product_options as $option) {
                                if(!empty($openProduct->product_options))
                                    $openProduct->product_options .= '<br>';
                                $openProduct->product_options .= $option->name.': '.$option->value_name;
                            }
                        $res['product'] = $openProduct;
                        $res['subTotal'] = $this->cart_model->getSubTotalInCart();
                        $res['subTotalFormat'] = $this->load->function_in_alias($wl_alias, '__formatPrice', $res['subTotal']);
                        $res['productsCountInCart'] = $this->cart_model->getProductsCountInCart();
                        if($this->cart_model->discountTotal)
                            $res['discountTotal'] = $this->load->function_in_alias($wl_alias, '__formatPrice', $this->cart_model->discountTotal);
                        else
                            $res['discountTotal'] = 0;
                        $res['result'] = true;
                    }
                }
            }
        }
        $this->load->json($res);
    }

    public function removeProduct()
    {
        $res = array('result' => false, 'subTotal' => 0);
        if($id = $this->data->post('id'))
        {
            $this->load->smodel('cart_model');
            if(is_numeric($id))
            {
                if($product = $this->cart_model->getProductInfo(array('id' => $id)))
                {
                    $user_id = $this->cart_model->getUser();
                    if($product->user == $user_id)
                    {
                        if($product->cart == 0)
                        {
                            if($this->db->deleteRow($this->cart_model->table('_products'), $id))
                            {
                                $res['result'] = true;
                                $res['subTotal'] = $this->cart_model->getSubTotalInCart();
                                $res['subTotalFormat'] = $this->load->function_in_alias($product->product_alias, '__formatPrice', $res['subTotal']);
                                $res['productsCountInCart'] = $this->cart_model->getProductsCountInCart();
                                if($this->cart_model->discountTotal)
                                    $res['discountTotal'] = $this->load->function_in_alias($product->product_alias, '__formatPrice', $this->cart_model->discountTotal);
                                else
                                    $res['discountTotal'] = 0;
                            }
                            else
                                $res['error'] = $this->text('Помилка оновлення інформації');
                        }
                        else
                            $res['error'] = $this->text('Редагувати інформацію про товар можна лише на неоформлених замовленнях!');
                    }
                    else
                        $res['error'] = $this->text('У Вас відсутній доступ до даного товару!');
                }
                else
                    $res['error'] = $this->text('Товар у корзині не ідентифіковано');
            }
            else
                $res['error'] = $this->text('Товар у корзині не ідентифіковано');
            
        }
        $this->load->json($res);
    }

    public function updateProduct()
    {
        $res = array('result' => false, 'subTotal' => 0);
        $id = $this->data->post('id');
        if(is_numeric($id) && $id > 0)
        {
            $this->load->smodel('cart_model');
            if($product = $this->cart_model->getProductInfo(array('id' => $id)))
            {
                $user_id = $this->cart_model->getUser();
                if($product->user == $user_id)
                {
                    if($product->cart == 0)
                    {
                        $quantity = $this->data->post('quantity');
                        if(is_numeric($quantity) && $quantity > 0)
                        {
                            $res['quantity'] = $product->quantity;
                            if($product->storage_invoice)
                            {
                                if($invoice = $this->load->function_in_alias($product->storage_alias, '__get_Invoice', $product->storage_invoice))
                                {
                                    if($invoice->amount_free > $quantity)
                                    {
                                        $data = array();
                                        if($invoice->price_out)
                                            $product->price = $data['price'] = $invoice->price_out;
                                        if($invoice->price_in)
                                            $product->price_in = $data['price_in'] = $invoice->price_in;
                                        $res['quantity'] = $data['quantity'] = $data['quantity_wont'] = $quantity;
                                        if($this->db->updateRow($this->cart_model->table('_products'), $data, $id))
                                        {
                                            $res['result'] = true;
                                            $res['priceFormat'] = $this->load->function_in_alias($product->product_alias, '__formatPrice', $product->price);
                                            $res['priceSumFormat'] = $this->load->function_in_alias($product->product_alias, '__formatPrice', $product->price * $res['quantity']);
                                        }
                                        else
                                            $res['error'] = $this->text('Помилка оновлення інформації');
                                    }
                                    else
                                        $res['error'] = $this->text('Увага! Недостатня кількість товару на складі');
                                }
                                else
                                    $res['error'] = $this->text('Товар відсутній на складі');
                            }
                            else
                            {
                                $product->key = $product->id;
                                $product->quantity = $quantity;
                                $products = $this->setProductsInfo([$product]);
                                $product = $products[0];

                                $data = array();
                                $res['quantity'] = $data['quantity'] = $data['quantity_wont'] = $quantity;
                                $data['discount'] = !empty($product->info->discount) && $product->info->discount > 0 ? $product->info->discount * $quantity : 0;
                                if($this->db->updateRow($this->cart_model->table('_products'), $data, $id))
                                {
                                    $res['result'] = true;
                                    $res['priceFormat'] = $product->info->price_format;
                                    $res['priceSumFormat'] = $product->info->sum_format;
                                }
                                else
                                    $res['error'] = $this->text('Помилка оновлення інформації');
                            }
                        }
                        elseif(isset($_POST['quantity']))
                            $res['error'] = $this->text('Кількість має бути більше нуля');
                        if(isset($_POST['active']) && ($_POST['active'] == 0 || $_POST['active'] == 1))
                        {
                            if($this->db->updateRow($this->cart_model->table('_products'), ['active' => $_POST['active']], $id))
                                    $res['result'] = true;
                        }


                        $res['subTotal'] = $this->cart_model->getSubTotalInCart($user_id);
                        $res['subTotalFormat'] = $this->load->function_in_alias($product->product_alias, '__formatPrice', $res['subTotal']);
                        $res['productsCountInCart'] = $this->cart_model->getProductsCountInCart();
                        if($this->cart_model->discountTotal)
                            $res['discountTotal'] = $this->load->function_in_alias($product->product_alias, '__formatPrice', $this->cart_model->discountTotal);
                        else
                            $res['discountTotal'] = 0;
                    }
                    else
                        $res['error'] = $this->text('Редагувати інформацію про товар можна лише на неоформлених замовленнях!');
                }
                else
                    $res['error'] = $this->text('У Вас відсутній доступ до даного товару!');
            }
            else
                $res['error'] = $this->text('Товар у корзині не ідентифіковано');
        }
        $this->load->json($res);
    }

    public function login()
    {
        $notify = '';
        if($this->data->post('email') && $this->data->post('password'))
        {
            $_SESSION['notify'] = new stdClass();

            $key = 'email';
            $email_phone = $this->data->post('email');
            $password = $this->data->post('password');
            $this->load->library('validator');
            if(!$this->validator->email('email', $email_phone))
            {
                if($email_phone = $this->validator->getPhone($email_phone))
                {
                    $key = 'phone';
                    $password = $email_phone;
                }
                else
                    $key = false;
            }

            if($key)
            {
                $this->load->model('wl_user_model');
                if($this->wl_user_model->login($key, $password))
                {
                    $this->load->smodel('cart_model');
                    $cart_user_id = $this->cart_model->getUser(false);
                    if($this->db->getCount($this->cart_model->table('_products'), array('cart' => 0, 'user' => $_SESSION['user']->id)))
                    {
                        $notify = '<br><br><strong>'.$this->text('Увага! Ми помітили, що Ви раніше додавали товар до корзини. Перевірте корзину перед замовленням').'</strong>';
                        $this->db->updateRow($this->cart_model->table('_products'), array('active' => 0), array('cart' => 0, 'user' => $_SESSION['user']->id));
                    }
                    $this->db->updateRow($this->cart_model->table('_products'), array('user' => $_SESSION['user']->id), array('cart' => 0, 'user' => $cart_user_id));
                    $_SESSION['cart']->user = $_SESSION['user']->id;

                    if(date('H') > 18 || date('H') < 6)
                        $_SESSION['notify']->success = $this->text('Доброго вечора', 0).', <strong>'.$_SESSION['user']->name.'</strong>! '.$this->text('Дякуємо що повернулися');
                    else
                        $_SESSION['notify']->success = $this->text('Доброго дня', 0).', <strong>'.$_SESSION['user']->name.'</strong>! '.$this->text('Дякуємо що повернулися');
                    $_SESSION['notify']->success .= $notify;
                }
                else
                    $_SESSION['notify']->error = $this->text('Неправильно введено email/телефон або пароль');
            }
            else
                $_SESSION['notify']->error = $this->text('Невірний формат email/номеру телефону');
        }
        if($notify != '')
            $this->redirect($_SESSION['alias']->alias);
        else
            $this->redirect();
    }

    public function checkEmail($get_user = false)
    {
        $res = array('result' => false, 'message' => '');
        if($phone = $this->data->post('phone'))
        {
            $this->load->model('wl_user_model');
            $user = new stdClass();
            if($this->wl_user_model->userExists($phone, $user))
            {
                if(!empty($user->password) && $_SESSION['option']->usePassword || $get_user)
                {
                    $res['result'] = true;
                    if($get_user)
                        $res['user'] = $user;
                    $res['email'] = $user->email;
                    $res['phone'] = $user->phone;
                    $res['message'] = '<p>'.$this->text('Доброго дня', 0);
                    if(date('H') > 18 || date('H') < 6)
                        $res['message'] = '<p>'.$this->text('Доброго вечора', 0);
                    if(!empty($user->name))
                        $res['message'] .= ', <strong>'.$user->name.'</strong>';
                    $res['message'] .= '</p><p>';
                    $res['message'] .= $this->text('У магазині за Вашою email адресою <strong>наявний персональний кабінет покупця</strong>. <u>Ваші персональні дані - найвища цінність для нас!</u><p> Просимо вибачення за дискомфорт, та змушені просити Вас <strong>ввести пароль</strong>, який Ви отримали при здійсненні першої покупки <br>(знайдіть лист у Вашій електронній скринці з інформацією про першу покупку) або встановили його самостійно в процесі реєстрації. </p><p>Якщо не можете знайти/згадати пароль доступу до кабінету, пропонуємо скористатися процедурою відновлення паролю. </p><p>З повагою, адміністрація '.SITE_NAME).'</p>';
                }
            }
        }
        if($this->data->post('ajax') == true)
            $this->load->json($res);
        else
            return $res;
    }

    public function confirm()
    {
        $this->load->smodel('cart_model');
        if($products = $this->cart_model->getProductsInCart())
        {
            $this->load->library('validator');
            if(!$this->userIs())
            {
                // $this->validator->setRules($this->text('email'), $this->data->post('email'), 'required|email');
                $this->validator->setRules($this->text('Ім\'я Прізвище'), $this->data->post('name'), 'required|5..50');
            }
            // if(!empty($_POST['phone']))
                $this->validator->setRules($this->text('Контактний номер'), $this->data->post('phone'), 'required|phone');
            $shippings = $this->cart_model->getShippings(array('active' => 1));
            if($shippings)
            {
                $this->validator->setRules($this->text('Ім\'я Прізвище отримувача'), $this->data->post('recipientName'), 'required');
                $this->validator->setRules($this->text('Контактний номер'), $this->data->post('recipientPhone'), 'required|phone');
            }

            if($this->validator->run())
            {
                $_POST['phone'] = !empty($_POST['phone']) ? $this->validator->getPhone($_POST['phone']) : '';
                $_POST['recipientPhone'] = !empty($_POST['recipientPhone']) ? $this->validator->getPhone($_POST['recipientPhone']) : '';
                $new_user = $new_user_password = $user_auth_id = $loginViaPhone = false;
                if(!$this->userIs())
                {
                    $check = $this->checkEmail(true);
                    if($check['result'] && $check['user'])
                    {
                        // if($_SESSION['option']->usePassword)
                        // {
                        //     $_SESSION['notify'] = new stdClass();
                        //     $_SESSION['notify']->error = $check['message'];
                        //     $this->redirect();
                        // }
                        // else
                        // {
                            $loginViaPhone = true;
                            $user_auth_id = $check['user']->auth_id;
                            $this->wl_user_model->setSession($check['user']);
                            // $this->cart_model->updateAdditionalUserFields($_SESSION['user']->id);
                        // }
                    }
                    else
                    {
                        $this->load->model('wl_user_model');
                        $info = $additionall = array();
                        $info['status'] = 1;
                        $info['email'] = $this->data->post('email');
                        $info['phone'] = $this->validator->getPhone($_POST['phone']);
                        $info['name'] = $this->data->post('name');
                        $info['photo'] = NULL;
                        // if($_SESSION['option']->usePassword)
                        //     $info['password'] = $new_user_password = bin2hex(openssl_random_pseudo_bytes(4));
                        $additionall = array();
                        // if(!empty($this->cart_model->additional_user_fields))
                        //     foreach ($this->cart_model->additional_user_fields as $key) {
                        //         $additionall[$key] = $this->data->post($key);
                        //     }
                        if($user = $this->wl_user_model->add($info, $additionall, $_SESSION['option']->new_user_type, $_SESSION['option']->usePassword, 'cart autoregister'))
                        {
                            $user_auth_id = $user->auth_id;
                            $this->wl_user_model->setSession($user);
                        }
                        $new_user = true;
                    }
                }
                // else
                //     $this->cart_model->updateAdditionalUserFields($_SESSION['user']->id);

                $cart_user_id = $this->cart_model->getUser(false);
                if($cart_user_id != $_SESSION['user']->id)
                {
                    if($this->db->getCount($this->cart_model->table('_products'), array('cart' => 0, 'user' => $_SESSION['user']->id)))
                        $this->db->updateRow($this->cart_model->table('_products'), array('active' => 0), array('cart' => 0, 'user' => $_SESSION['user']->id));
                    $this->db->updateRow($this->cart_model->table('_products'), array('user' => $_SESSION['user']->id), array('cart' => 0, 'user' => $cart_user_id));
                    $_SESSION['cart']->user = $_SESSION['user']->id;
                }

                $delivery_name = '';
                $delivery = array('id' => 0, 'recipient' => '', 'info' => [], 'text' => '');
                if($shippings)
                    if($shippingId = $this->data->post('shipping-method'))
                        if(is_numeric($shippingId))
                            if($shipping = $this->db->getAllDataById($this->cart_model->table('_shipping'), array('id' => $shippingId, 'active' => 1))) {
                                $delivery['id'] = $shipping->id;
                                if($shipping->wl_alias)
                                {
                                    $info = $this->load->function_in_alias($shipping->wl_alias, '__set_Shipping_from_cart');
                                    if(!empty($info['info']))
                                        $delivery['info'] = $info['info'];
                                    if(!empty($info['text']))
                                        $delivery['text'] = $info['text'];
                                    $wl_ntkd_delivery = ['alias' => $shipping->wl_alias, 'content' => 0];
                                    if($_SESSION['language'])
                                        $wl_ntkd_delivery['language'] = $_SESSION['language'];
                                    if($name = $this->db->getAllDataById('wl_ntkd', $wl_ntkd_delivery))
                                        $delivery_name = $name->name;
                                }
                                else
                                {
                                    $delivery_name = $shipping->name;
                                    if(!empty($shipping->name) && ($_SESSION['language']))
                                    {
                                        $name = unserialize($shipping->name);
                                        if(isset($name[$_SESSION['language']]))
                                            $delivery_name = $name[$_SESSION['language']];
                                        else if(is_array($name))
                                            $delivery_name = array_shift($name);
                                    } 

                                    if($shipping->type < 3) // 3 => без адреси
                                    {
                                        if($city = $this->data->post('shipping-city'))
                                        {
                                            $info['city'] = $city;
                                            $delivery['text'] .= $this->text('Місто').': '.$city;
                                        }
                                        if($shipping->type == 1) // 1 => за адресою
                                            if($address = $this->data->post('shipping-address'))
                                            {
                                                $info['address'] = $address;
                                                $delivery['text'] .= '<br>'.$this->text('Адреса').': '.$address;
                                            }
                                        if($shipping->type == 2) // 2 => у відділення
                                            if($department = $this->data->post('shipping-department'))
                                            {
                                                $info['department'] = $department;
                                                $delivery['text'] .= ' '.$this->text('Відділення').': '.$department;
                                            }
                                        $delivery['info'] = $info;
                                    }
                                }

                                $delivery['info']['recipientName'] = $this->data->post('recipientName');
                                $delivery['info']['recipientPhone'] = $this->data->post('recipientPhone');
                                if($shipping->pay >= 0)
                                {
                                    $delivery['pay'] = $delivery['info']['pay'] = $shipping->pay;
                                    $delivery['price'] = $delivery['info']['price'] = $shipping->price;
                                }
                                $delivery['text'] .= '<br><br>'.$this->text('Отримувач').': <strong>'.$delivery['info']['recipientName'].', '.$this->data->formatPhone($delivery['info']['recipientPhone']).'</strong>';
                            }

                $payment = false;
                if($payment_method = $this->data->post('payment_method'))
                    if($payment = $this->cart_model->getPayments(array('id' => $payment_method, 'active' => 1)))
                        $payment = $payment[0];

                if($cart = $this->cart_model->checkout($_SESSION['user']->id, $delivery, $payment))
                {
                    $phone = $this->db->select('wl_user_info', 'value', array('field' => 'phone', 'user' => $_SESSION['user']->id))->limit(1)->get();
                    if(empty($phone) && !empty($_POST['phone']))
                    {
                        $this->db->insertRow('wl_user_info', ['field' => 'phone', 'value' => $_POST['phone'], 'user' => $_SESSION['user']->id, 'date' => time()]);
                        $_SESSION['user']->phone = $_POST['phone'];
                    }

                    $this->load->library('mail');

                    $cart['date'] = date('d.m.Y H:i');
                    $cart['user_name'] = $_SESSION['user']->name;
                    $cart['user_email'] = $_SESSION['user']->email;
                    $cart['user_phone'] = (!empty($phone)) ? $phone->value : $_POST['phone'];
                    $cart['user_phone'] = $this->data->formatPhone($cart['user_phone']);
                    $cart['new_user'] = $new_user;
                    if($new_user && $new_user_password)
                        $cart['password'] = $new_user_password;
                    $cart['link'] = SITE_URL.$_SESSION['alias']->alias.'/'.$cart['id'];
                    $cart['admin_link'] = SITE_URL.'admin/'.$_SESSION['alias']->alias.'/'.$cart['id'];

                    // ціна не оновлюється для зареєстрованого клієнта
                    $products = $this->setProductsInfo($products, false);

                    $cart['total_formatted'] = $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $cart['total']);
                    if($cart['discount'])
                        $cart['discount_formatted'] = $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $cart['discount']);
                    $sum = 0;
                    foreach ($products as $product) {
                        if($product->price == $product->info->price)
                        {
                            $product->price_format = $product->info->price_format;
                            $product->sum_format = $product->info->sum_format;
                        }
                        else
                        {
                            $product->price_format = $this->load->function_in_alias($product->product_alias, '__formatPrice', $product->price);
                            $product->sum_format = $this->load->function_in_alias($product->product_alias, '__formatPrice', $product->price * $product->quantity);
                        }
                        if($product->discount)
                            $product->sumBefore_format = $this->load->function_in_alias($product->product_alias, '__formatPrice', $product->price * $product->quantity + $product->discount);
                        $sum += $product->price * $product->quantity + $product->discount;

                        if($product->storage_invoice && $product->storage_alias)
                        {
                            $reserve = array('invoice' => $product->storage_invoice, 'amount' => $product->quantity);
                            $this->load->function_in_alias($product->storage_alias, '__set_Reserve', $reserve);
                        }
                    }
                    $cart['sum_formatted'] = $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $sum);
                    $cart['products'] = $products;
                    $cart['delivery'] = '<strong>'.$delivery_name.'</strong> '.$delivery['text'];
                    if(!empty($delivery['price']))
                        $cart['delivery_price'] = $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $delivery['price']);
                    $cart['payment'] = $payment ?? '';

                    $email_manager_notify = $_SESSION['option']->email_manager ?? SITE_EMAIL;
                    
                    if(!empty($_SESSION['user']->email)) {
                        $this->mail->sendTemplate('checkout', $_SESSION['user']->email, $cart);
                    }
                    $this->mail->sendTemplate('checkout_manager', $email_manager_notify, $cart);

                    if(!$_SESSION['option']->usePassword && $user_auth_id || $loginViaPhone)
                    {
                        $_SESSION['user'] = new stdClass();
                        setcookie('auth_id', '', time() - 3600, '/');
                    }

                    if($payment && $payment->wl_alias > 0)
                    {
                        $pay = new stdClass();
                        $pay->id = $cart['id'];
                        $pay->total = $cart['total'];
                        $pay->payed = 0;
                        $pay->wl_alias = $_SESSION['alias']->id;
                        $pay->return_url = $_SESSION['alias']->alias.'/success?order='.$cart['id'];
                        if(!$_SESSION['option']->usePassword && $user_auth_id || $loginViaPhone)
                            $pay->return_url .= '&key='.$user_auth_id;
                        
                        $this->load->function_in_alias($payment->wl_alias, '__get_Payment', $pay);
                    }
                    else 
                    {
                        $this->wl_alias_model->setContent(2);
                        if (!empty($_SESSION['alias']->text) || !empty($_SESSION['alias']->meta)) {
                            $keys = array();
                            foreach ($cart as $key => $value) {
                                $name = '{cart.'.$key;
                                if(!is_object($value) && !is_array($value))
                                    $keys[$name.'}'] = $value;
                                else
                                    foreach ($value as $keyO => $valueO) {
                                        if(!is_object($valueO) && !is_array($valueO))
                                            $keys[$name.'.'.$keyO.'}'] = $valueO;
                                        else
                                            foreach ($valueO as $key1 => $value1) {
                                                if(!is_object($value1) && !is_array($value1))
                                                    $keys[$name.'.'.$keyO.'.'.$key1.'}'] = $value1;
                                            }
                                    }
                            }
                            if (!empty($_SESSION['alias']->meta) && strripos($_SESSION['alias']->meta, 'transactionProducts') !== false) {
                                $transactionProducts = '';
                                foreach ($cart['products'] as $product) {
                                    $transactionProducts .= "{
                                        'sku': '{$product->info->article_show}',
                                        'name': '{$product->info->name}',
                                        'category': '{$product->info->group_name}',
                                        'price': '{$product->price}',
                                        'quantity': '{$product->quantity}'
                                    },";
                                }
                                $keys['{{$transactionProducts}}'] = substr($transactionProducts, 0, -1);
                            }
                            $keys['{name}'] = $_SESSION['alias']->name;
                            $keys['{SITE_NAME}'] = SITE_NAME;
                            $keys['{SITE_URL}'] = SITE_URL;
                            $keys['{IMG_PATH}'] = IMG_PATH;
                            foreach (['text', 'meta'] as $key) {
                                foreach ($keys as $keyR => $valueR) {
                                    $_SESSION['alias']->$key = str_replace($keyR, $valueR, $_SESSION['alias']->$key);
                                }
                            }
                        }
                        $_SESSION['notify'] = new stdClass();
                        $_SESSION['notify']->title = $_SESSION['alias']->name;
                        $_SESSION['notify']->success = $_SESSION['alias']->text;
                        $_SESSION['notify']->meta = $_SESSION['alias']->meta;
                        if(!$_SESSION['option']->usePassword && $user_auth_id || $loginViaPhone)
                            $this->redirect($_SESSION['alias']->alias.'/success?order='.$cart['id'].'&key='.$user_auth_id);
                        else
                            $this->redirect($_SESSION['alias']->alias.'/success?order='.$cart['id']);
                    }
                }
            }
            else
            {
                $_SESSION['notify'] = new stdClass();
                $_SESSION['notify']->error = $this->validator->getErrors();
                $this->redirect();
            }
        }
        else
            $this->redirect($_SESSION['alias']->alias);
    }

    public function success()
    {
        if($order_id = $this->data->get('order'))
        {
            if(is_numeric($order_id) && $order_id > 0)
            {
                if($this->userIs() || !empty($_GET['key']))
                    $this->__view_order_by_id($order_id);
                else
                    $this->redirect('login');
            }
            else
                $this->load->page_404(false);
        }
        else
            $this->load->page_404(false);
    }

    public function checkout()
    {
        $this->load->smodel('cart_model');

        // for login by facebook or google
        $cart_user_id = $this->cart_model->getUser(false);
        if($this->userIs() && $cart_user_id != $_SESSION['user']->id)
        {
            $notify = '';
            if($this->db->getCount($this->cart_model->table('_products'), array('cart' => 0, 'user' => $_SESSION['user']->id)))
            {
                $notify = '<br><br><strong>'.$this->text('Увага! Ми помітили, що Ви раніше додавали товар до корзини. Перевірте корзину перед замовленням').'!</strong>';
                // $this->db->updateRow($this->cart_model->table('_products'), array('active' => 0), array('cart' => 0, 'user' => $_SESSION['user']->id));
            }
            $this->db->updateRow($this->cart_model->table('_products'), array('user' => $_SESSION['user']->id), array('cart' => 0, 'user' => $cart_user_id));
            $_SESSION['cart']->user = $_SESSION['user']->id;

            $_SESSION['notify'] = new stdClass();
            if(date('H') > 18 || date('H') < 6)
                $_SESSION['notify']->success = $this->text('Доброго вечора', 0).', <strong>'.$_SESSION['user']->name.'</strong>! '.$this->text('Дякуємо що повернулися').$notify;
            else
                $_SESSION['notify']->success = $this->text('Доброго дня', 0).', <strong>'.$_SESSION['user']->name.'</strong>! '.$this->text('Дякуємо що повернулися').$notify;
            // if(!empty($notify))
            //     $this->redirect($_SESSION['alias']->alias);
        }

        if($products = $this->cart_model->getProductsInCart())
        {
            if($this->userIs() && empty($_SESSION['user']->phone))
            {
                if($info = $this->db->select('wl_user_info', "value as phone", ['user' => $_SESSION['user']->id, 'field' => 'phone'])
                                ->limit(1)
                                ->get())
                    $_SESSION['user']->phone = $info->phone;
            }

            $products = $this->setProductsInfo($products);
            $subTotal = $total = $this->cart_model->getSubTotalInCart();
            $subTotal += $this->cart_model->discountTotal;

            $payments = $this->cart_model->getPayments(array('active' => 1));

            $shippings = $this->cart_model->getShippings(array('active' => 1));
            if($shippings && ($shippings[0]->pay >= 0 || $shippings[0]->pay > $total))
            {
                $total += $shippings[0]->price;
                $shippings[0]->priceFormat = $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $shippings[0]->price);
            }

            $bonusCodes = $this->cart_model->bonusCodes();
            if($bonusCodes && !empty($bonusCodes->info))
                foreach ($bonusCodes->info as $key => &$discount)
                    if(is_numeric($discount))
                        $discount = $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $discount);

            $this->wl_alias_model->setContent(1);
            $this->load->page_view('checkout_view', array('products' => $products,
                                                            'shippings' => $shippings,
                                                            'userShipping' => $this->cart_model->getUserShipping(),
                                                            'payments' => $payments,
                                                            'subTotal' => $subTotal,
                                                            'subTotalFormat' => $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $subTotal),
                                                            'discountTotal' => $this->cart_model->discountTotal,
                                                            'discountTotalFormat' => $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $this->cart_model->discountTotal),
                                                            'total' => $total,
                                                            'totalFormat' => $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $total),
                                                            'bonusCodes' => $bonusCodes));
        }
        else
            $this->redirect($_SESSION['alias']->alias);
    }

    // buy per one click
    public function buyProduct()
    {
        $this->load->library('validator');
        $this->validator->setRules($this->text('productKey'), $this->data->post('productKey'), 'required|3..50');
        if(!$this->userIs())
        {
            $this->validator->setRules($this->text('Контактний номер'), $this->data->post('phone'), 'required|phone');
            $this->validator->setRules($this->text('Ім\'я'), $this->data->post('first_name'), 'required|3..50');
            $this->validator->setRules($this->text('Прізвище'), $this->data->post('last_name'), 'required|3..50');
        }
        if(!empty($_POST['email']))
            $this->validator->setRules($this->text('email'), $this->data->post('email'), 'required|email');
        if($this->validator->run())
        {
            $this->load->smodel('cart_model');
            if($product = $this->addProduct(true))
            {
                $_POST['phone'] = !empty($_POST['phone']) ? $this->validator->getPhone($_POST['phone']) : '';
                $new_user = $new_user_password = $user_auth_id = false;
                if(!$this->userIs())
                {
                    $check = $this->checkEmail(true);
                    if($check['result'] && $check['user'])
                    {
                        if($_SESSION['option']->usePassword)
                        {
                            $_SESSION['notify'] = new stdClass();
                            $_SESSION['notify']->error = $check['message'];
                            $this->redirect($_SESSION['alias']->alias . '/confirm');
                        }
                        else
                        {
                            $user_auth_id = $check['user']->auth_id;
                            $this->wl_user_model->setSession($check['user']);
                            $this->cart_model->updateAdditionalUserFields($_SESSION['user']->id);
                        }
                    }
                    else
                    {
                        $this->load->model('wl_user_model');
                        $info = $additionall = array();
                        $info['status'] = 1;
                        $info['email'] = $this->data->post('email');
                        $info['phone'] = $this->data->post('phone');
                        $info['name'] = $this->data->post('first_name').' '.$this->data->post('last_name');
                        $info['photo'] = NULL;
                        if($_SESSION['option']->usePassword)
                            $info['password'] = $new_user_password = bin2hex(openssl_random_pseudo_bytes(4));
                        $additionall = array();
                        if(!empty($this->cart_model->additional_user_fields))
                            foreach ($this->cart_model->additional_user_fields as $key) {
                                $additionall[$key] = $this->data->post($key);
                            }
                        if($user = $this->wl_user_model->add($info, $additionall, $_SESSION['option']->new_user_type, $_SESSION['option']->usePassword, 'cart autoregister'))
                        {
                            $user_auth_id = $user->auth_id;
                            $this->wl_user_model->setSession($user);
                        }
                        $new_user = true;
                    }
                }
                // else
                //     $this->cart_model->updateAdditionalUserFields($_SESSION['user']->id);

                $delivery = [];
                $delivery['info']['recipientName'] = $this->data->post('first_name').' '.$this->data->post('last_name');
                $delivery['info']['recipientPhone'] = $this->data->post('phone');

                $cart = $this->cart_model->checkout($_SESSION['user']->id, $delivery, false, $product->sum);
                $this->cart_model->addProduct($product, $_SESSION['user']->id, $cart['id']);
                if($product->storage_invoice && $product->storage_alias)
                {
                    $reserve = array('invoice' => $product->storage_invoice, 'amount' => $product->quantity);
                    $this->load->function_in_alias($product->storage_alias, '__set_Reserve', $reserve);
                }

                $cart['date'] = date('d.m.Y H:i');
                $cart['user_name'] = $_SESSION['user']->name;
                $cart['user_email'] = $_SESSION['user']->email;
                $cart['user_phone'] = $this->data->formatPhone($_POST['phone']);
                $cart['new_user'] = $new_user;
                if($new_user && $new_user_password)
                    $cart['password'] = $new_user_password;
                $cart['link'] = SITE_URL.$_SESSION['alias']->alias.'/'.$cart['id'];
                $cart['admin_link'] = SITE_URL.'admin/'.$_SESSION['alias']->alias.'/'.$cart['id'];

                $cart['total_formatted'] = $cart['sum_formatted'] = $product->sum_format;
                if($cart['discount'])
                    $cart['discount_formatted'] = $this->load->function_in_alias($product->product_alias, '__formatPrice', $cart['discount']);

                $product->info = $product;
                $cart['products'] = [$product];
                $cart['delivery'] = $cart['delivery_price'] = $cart['payment'] = '';

                $email_manager_notify = $_SESSION['option']->email_manager ?? SITE_EMAIL;
                
                $this->load->library('mail');
                $this->mail->sendTemplate('checkout', $_SESSION['user']->email, $cart);
                $this->mail->sendTemplate('checkout_manager', $email_manager_notify, $cart);

                if(!$_SESSION['option']->usePassword && $user_auth_id)
                {
                    $_SESSION['user'] = new stdClass();
                    setcookie('auth_id', '', time() - 3600, '/');
                }

                $this->wl_alias_model->setContent(2);
                if (!empty($_SESSION['alias']->text) || !empty($_SESSION['alias']->meta)) {
                    $keys = array();
                    foreach ($cart as $key => $value) {
                        $name = '{cart.'.$key;
                        if(!is_object($value) && !is_array($value))
                            $keys[$name.'}'] = $value;
                        else
                            foreach ($value as $keyO => $valueO) {
                                if(!is_object($valueO) && !is_array($valueO))
                                    $keys[$name.'.'.$keyO.'}'] = $valueO;
                                else
                                    foreach ($valueO as $key1 => $value1) {
                                        if(!is_object($value1) && !is_array($value1))
                                            $keys[$name.'.'.$keyO.'.'.$key1.'}'] = $value1;
                                    }
                            }
                    }
                    if (!empty($_SESSION['alias']->meta) && strripos($_SESSION['alias']->meta, 'transactionProducts') !== false) {
                        $transactionProducts = '';
                        foreach ($cart['products'] as $product) {
                            $transactionProducts .= "{
                                'sku': '{$product->info->article_show}',
                                'name': '{$product->info->name}',
                                'category': '{$product->info->group_name}',
                                'price': '{$product->price}',
                                'quantity': '{$product->quantity}'
                            },";
                        }
                        $keys['{{$transactionProducts}}'] = substr($transactionProducts, 0, -1);
                    }
                    $keys['{name}'] = $_SESSION['alias']->name;
                    $keys['{SITE_NAME}'] = SITE_NAME;
                    $keys['{SITE_URL}'] = SITE_URL;
                    $keys['{IMG_PATH}'] = IMG_PATH;
                    foreach (['text', 'meta'] as $key) {
                        foreach ($keys as $keyR => $valueR) {
                            $_SESSION['alias']->$key = str_replace($keyR, $valueR, $_SESSION['alias']->$key);
                        }
                    }
                }
                $_SESSION['notify'] = new stdClass();
                $_SESSION['notify']->title = $_SESSION['alias']->name;
                $_SESSION['notify']->success = $_SESSION['alias']->text;
                $_SESSION['notify']->meta = $_SESSION['alias']->meta;
                if(!$_SESSION['option']->usePassword && $user_auth_id)
                    $this->redirect($_SESSION['alias']->alias.'/success?order='.$cart['id'].'&key='.$user_auth_id);
                else
                    $this->redirect($_SESSION['alias']->alias.'/success?order='.$cart['id']);
            }
        }
        else
        {
            $_SESSION['notify'] = new stdClass();
            $_SESSION['notify']->error = $this->validator->getErrors();
            $this->redirect();
        }
    }

    public function coupon()
    {
        if($code = $this->data->post('code'))
        {
            $this->load->smodel('cart_model');
            if($this->cart_model->applayBonusCode($code))
            {
                $_SESSION['notify'] = new stdClass();
                $_SESSION['notify']->success = $this->text('Бонус-код застосовано!');
            }
            else
            {
                $_SESSION['notify'] = new stdClass();
                $_SESSION['notify']->error = $this->text('Бонус-код невірний або застарів');
            }
        }
        else
        {
            $_SESSION['notify'] = new stdClass();
            $_SESSION['notify']->error = $this->text('Введіть бонус-код');
        }
        $this->redirect();
    }

    public function pay()
    {
        $accessKey = '';
        if(!$this->userIs() && $this->data->get('key'))
            $accessKey = '?key='.$this->data->get('key');

        if(isset($_POST['method']) && is_numeric($_POST['method']) && isset($_POST['cart']) && is_numeric($_POST['cart']))
        {
            if($cart = $this->db->getAllDataById('s_cart', $_POST['cart']))
            {
                $go = false;
                if($this->userIs() && $cart->user == $_SESSION['user']->id || $this->userCan())
                    $go = true;
                else if($key = $this->data->get('key'))
                    if($user = $this->db->getAllDataById('wl_users', $key, 'auth_id'))
                        if($cart->user == $user->id)
                            $go = true;
                if($go)
                {
                    $cart->return_url = $_SESSION['alias']->alias.'/'.$cart->id.$accessKey;
                    $cart->wl_alias = $_SESSION['alias']->id;

                    $this->load->function_in_alias($this->data->post('method'), '__get_Payment', $cart);
                }
                else
                    $this->load->notify_view(array('errors' => $this->text('Немає прав для перегляду даного замовлення.')));
                exit;
            }
        }

        if(isset($_GET['method']) && is_numeric($_GET['method']) && isset($_GET['cart']) && is_numeric($_GET['cart']))
        {
            if($cart = $this->db->getAllDataById('s_cart', $_GET['cart']))
            {
                $go = false;
                if($this->userIs() && $cart->user == $_SESSION['user']->id || $this->userCan())
                    $go = true;
                else if($key = $this->data->get('key'))
                    if($user = $this->db->getAllDataById('wl_users', $key, 'auth_id'))
                        if($cart->user == $user->id)
                            $go = true;
                if($go)
                {
                    $cart->return_url = $_SESSION['alias']->alias.'/'.$cart->id.$accessKey;
                    $cart->wl_alias = $_SESSION['alias']->id;

                    $this->load->function_in_alias($this->data->get('method'), '__get_Payment', $cart);
                }
                else
                    $this->load->notify_view(array('errors' => $this->text('Немає прав для перегляду даного замовлення.')));
                exit;
            }
        }

        $this->redirect();
    }

    public function get_Shipping_to_cart()
    {
        if($id = $this->data->post('shipping'))
        {
            $this->load->smodel('cart_model');
            $userShipping = $this->cart_model->getUserShipping();
            if($shipping = $this->cart_model->getShippings(array('id' => $id, 'active' => 1)))
            {
                if($userShipping)
                    $userShipping->initShipping = false;
                $this->load->function_in_alias($shipping[0]->wl_alias, '__get_Shipping_to_cart', $userShipping);
            }
        }
    }

    public function set__shippingToOrder()
    {
        if($order_id = $this->data->post('order_id'))
        {
            if($cart = $this->db->getAllDataById('s_cart', $order_id))
            {
                $go = false;
                if($this->userIs() && $cart->user == $_SESSION['user']->id || $this->userCan())
                    $go = true;
                else if($accessKey = $this->data->post('accessKey'))
                    if($user = $this->db->getAllDataById('wl_users', $accessKey, 'auth_id'))
                        if($cart->user == $user->id)
                            $go = true;
                if($go)
                {
                    $this->load->library('validator');
                    $this->validator->setRules($this->text('Ім\'я Прізвище отримувача'), $this->data->post('recipientName'), 'required');
                    $this->validator->setRules($this->text('Контактний номер'), $this->data->post('recipientPhone'), 'required|phone');
                    if($this->validator->run())
                    {
                        $_POST['recipientPhone'] = !empty($_POST['recipientPhone']) ? $this->validator->getPhone($_POST['recipientPhone']) : '';

                        $delivery = array('id' => 0, 'recipient' => '', 'info' => [], 'text' => '');
                        if($shippingId = $this->data->post('shipping-method'))
                            if(is_numeric($shippingId))
                                if($shipping = $this->db->getAllDataById($_SESSION['service']->table.'_shipping', array('id' => $shippingId, 'active' => 1)))
                                {
                                    $delivery['id'] = $shipping->id;
                                    if($shipping->wl_alias)
                                    {
                                        $info = $this->load->function_in_alias($shipping->wl_alias, '__set_Shipping_from_cart');
                                        if(!empty($info['info']))
                                            $delivery['info'] = $info['info'];
                                        if(!empty($info['text']))
                                            $delivery['text'] = $info['text'];
                                    }
                                    elseif($shipping->type < 3) // 3 => без адреси
                                    {
                                        if($city = $this->data->post('shipping-city'))
                                        {
                                            $info['city'] = $city;
                                            $delivery['text'] .= $this->text('Місто').': '.$city;
                                        }
                                        if($shipping->type == 1) // 1 => за адресою
                                            if($address = $this->data->post('shipping-address'))
                                            {
                                                $info['address'] = $address;
                                                $delivery['text'] .= '<br>'.$this->text('Адреса').': '.$address;
                                            }
                                        if($shipping->type == 2) // 2 => у відділення
                                            if($department = $this->data->post('shipping-department'))
                                            {
                                                $info['department'] = $department;
                                                $delivery['text'] .= ' '.$this->text('Відділення').': '.$department;
                                            }
                                        $delivery['info'] = $info;
                                    }

                                    $delivery['info']['recipientName'] = $this->data->post('recipientName');
                                    $delivery['info']['recipientPhone'] = $this->data->post('recipientPhone');
                                    if($shipping->pay >= 0)
                                    {
                                        $delivery['pay'] = $delivery['info']['pay'] = $shipping->pay;
                                        $delivery['price'] = $delivery['info']['price'] = $shipping->price;
                                    }
                                    $delivery['text'] .= '<br><br>'.$this->text('Отримувач').': <strong>'.$delivery['info']['recipientName'].', '.$this->data->formatPhone($delivery['info']['recipientPhone']).'</strong>';

                                    $update = [];
                                    $update['shipping_id'] = (isset($delivery['id'])) ? $delivery['id'] : 0;
                                    $update['shipping_info'] = (!empty($delivery['info'])) ? serialize($delivery['info']) : '';
                                    if(!empty($delivery['price']) && ($delivery['pay'] == 0 || $delivery['pay'] > $cart->total))
                                        $update['total'] = $cart->total + $delivery['price'];
                                    $this->db->updateRow($_SESSION['service']->table, $update, $cart->id);
                                }

                        if($link = $this->data->post('redirect'))
                            $this->redirect($link);

                        $accessKey = '';
                        if(!$this->userIs() && $this->data->post('accessKey'))
                            $accessKey = '?key='.$this->data->post('accessKey');
                        $this->redirect($_SESSION['alias']->alias.'/'.$cart->id.$accessKey);
                    }
                    else
                    {
                        $_SESSION['notify'] = new stdClass();
                        $_SESSION['notify']->error = $this->validator->getErrors();
                        $this->redirect();
                    }
                }
                else
                    $this->load->notify_view(array('errors' => $this->text('Немає прав для перегляду даного замовлення.')));
                exit;
            }
        }
        echo "error saving shipping data";
        exit;
    }

    public function getProductsInCart()
    {
        $this->load->smodel('cart_model');
        $res = array('count' => 0, 'subTotal' => 0, 'subTotalFormat' => '', 'discountTotal' => 0);
        if($products = $this->cart_model->getProductsInCart(0,0))
        {
            $res['products'] = [];
            $products = $this->setProductsInfo($products);
            foreach ($products as $product) {
                $openProduct = new stdClass();
                $openProduct->key = $product->key;
                $openProduct->product_options = $product->product_options;
                $openProduct->price = $product->price;
                $openProduct->price_format = $product->info->price_format;
                $openProduct->quantity = $product->quantity;
                $openProduct->discount = $product->discount;
                $openProduct->sum_format = $product->info->sum_format;
                $openProduct->id = $product->info->id;
                $openProduct->article = $product->info->article_show ?? $product->info->article;
                $openProduct->name = $product->info->name;
                $openProduct->link = $product->info->link;
                $openProduct->photo = $product->info->photo ?? false;
                $openProduct->admin_photo = !empty($product->info->admin_photo) ? IMG_PATH.$product->info->admin_photo : false;
                $openProduct->cart_photo = !empty($product->info->cart_photo) ? IMG_PATH.$product->info->cart_photo : false;
                $openProduct->options = $product->info->options ?? false;
                if(!empty($product->storage))
                {
                    $openProduct->storage = new stdClass();
                    $openProduct->storage->name = $product->storage->storage_name;
                    $openProduct->storage->time = $product->storage->storage_time;
                }
                $res['products'][] = $openProduct;
            }
            $res['count'] = $this->cart_model->getProductsCountInCart();
            $res['subTotal'] = $this->cart_model->getSubTotalInCart();
            $res['subTotalFormat'] = $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $res['subTotal']);
            if($this->cart_model->discountTotal)
                $res['discountTotal'] = $this->load->function_in_alias($products[0]->product_alias, '__formatPrice', $this->cart_model->discountTotal);
        }
        $this->load->json($res);
    }

    public function getCountProductsInCart()
    {
        $this->load->smodel('cart_model');
        $res = array('count' => 0);
        if($products = $this->cart_model->getProductsInCart(0,0))
            $res['count'] = $this->cart_model->getProductsCountInCart();
        $this->load->json($res);
    }

    public function __getById($id)
    {
        return $this->__view_order_by_id($id, true);
    }

    public function __show_btn_add_product($product)
    {
        if(!empty($product))
            $this->load->view('__btn_add_product_subview', array('product' => $product));
        else
            echo "<p>Увага! Відсутня інформація про товар! (для генерації кнопки <strong>Додати товар до корзини</strong>)</p>";
        return true;
    }

    public function __show_minicart()
    {
        $this->load->smodel('cart_model');
        $res = array('subTotal' => 0, 'subTotalFormat' => '', 'discountTotal' => 0);
        if($res['products'] = $this->cart_model->getProductsInCart())
        {
            $res['products'] = $this->setProductsInfo($res['products']);
            $res['subTotal'] = $this->cart_model->getSubTotalInCart();
            $res['subTotalFormat'] = $this->load->function_in_alias($res['products'][0]->product_alias, '__formatPrice', $res['subTotal']);
            if($this->cart_model->discountTotal)
                $res['discountTotal'] = $this->load->function_in_alias($res['products'][0]->product_alias, '__formatPrice', $this->cart_model->discountTotal);
        }
        $_SESSION['option']->uniqueDesign = false;
        $this->load->view('__minicart_subview', $res);
        return true;
    }

    public function __get_cart_statuses()
    {
        $this->load->smodel('cart_model');
        return $this->cart_model->getStatuses();
    }

    public function __get_user_orders($user)
    {
        $this->load->smodel('cart_model');
        return $this->cart_model->getCarts(array('user' => $user));
    }

    public function __get_Search($content)
    {
        return false;
    }

    public function __user_login()
    {
        $this->load->smodel('cart_model');
        $cart_user_id = $this->cart_model->getUser(false);
        if($this->userIs() && $cart_user_id && $cart_user_id != $_SESSION['user']->id)
        {
            $this->db->updateRow($this->cart_model->table('_products'), array('user' => $_SESSION['user']->id), array('cart' => 0, 'user' => $cart_user_id));
            $_SESSION['cart']->user = $_SESSION['user']->id;
        }
    }

    private function setProductsInfo($products, $getStorages = true)
    {
        foreach ($products as $product) {
            $where = array('id' => $product->product_id);
            if(!empty($product->product_options))
            {
                if(!is_array($product->product_options))
                   $product->product_options = unserialize($product->product_options);
                $changePrice = [];
                foreach ($product->product_options as $option) {
                    if(is_object($option) && $option->changePrice)
                        $changePrice[$option->id] = $option->value_id;
                }
                if(!empty($changePrice))
                    $where['options'] = $changePrice;
            }
            $where['additionalFileds'] = array('quantity' => $product->quantity);
            if($product->product_alias)
                $product->info = $this->load->function_in_alias($product->product_alias, '__get_Product', $where);
            else if(!empty($product->product_options))
            {
                $shop_alias = $products[0]->product_alias;
                if(empty($shop_alias))
                {
                    if($row = $this->db->select('s_cart_products', 'product_alias', ['product_alias' => '>0'])->limit(1)->get())
                        $shop_alias = $row->product_alias;
                }
                $options = unserialize($product->product_options);
                $product->info = new stdClass();
                $product->info->id = $product->id;
                $product->info->photo = $options['photo'];
                $product->info->cart_photo = $product->info->admin_photo = IMG_PATH.$options['cart_photo'];
                $product->info->article = $options['article'];
                $product->info->name = $options['name'];
                $product->info->link = $options['photo'] ?? '';
                $cart->subTotal += $product->price * $product->quantity + $product->discount;
                if(empty($shop_alias))
                {
                    $product->price_format = $this->load->function_in_alias($shop_alias, '__formatPrice', $product->price);
                    $product->sum_format = $this->load->function_in_alias($shop_alias, '__formatPrice', $product->price * $product->quantity);
                }
                else
                {
                    $product->price_format = $product->price;
                    $product->sum_format = $product->price * $product->quantity;
                }
                $product->product_options = false;
            }
            if($getStorages)
            {
                if($product->storage_invoice)
                {
                    if($product->storage = $this->load->function_in_alias($product->storage_alias, '__get_Invoice', $product->storage_invoice))
                    {
                        if($product->storage->price_out)
                        {
                            $product->price = $product->info->price = $product->storage->price_out;
                            $product->price_format = $product->info->price_format = $this->load->function_in_alias($product->product_alias, '__formatPrice', $product->price);
                        }
                        if($product->storage->price_in)
                            $product->price_in = $product->storage->price_in;
                        if($product->storage->amount_free < $product->quantity)
                            $product->quantity = $product->storage->amount_free;
                        $product->sum_format = $product->info->sum_format = $this->load->function_in_alias($product->product_alias, '__formatPrice', $product->price * $product->quantity);
                    }
                }
                $product = $this->cart_model->checkProductInfo($product, $product->info);
            }
        }
        return $products;
    }

}

?>