<?php

/*

 	Service "Shop Showcase 3.2"
	for WhiteLion 1.3

*/

class shopshowcase extends Controller {

	private $groups = array();
	private $marketing = array();

    function __construct()
    {
        parent::__construct();

    	$marketing = $this->db->cache_get('marketing');
		if($marketing === NULL)
        {
	        if($cooperation = $this->db->getAllDataByFieldInArray('wl_aliases_cooperation', $_SESSION['alias']->id, 'alias1'))
	        	foreach ($cooperation as $c) {
			        if($c->type == 'marketing')
			        	$this->marketing[] = $c->alias2;
		        }
		    $this->db->cache_add('marketing', $this->marketing);
		}
		else
			$this->marketing = $marketing;
    }

    function _remap($method, $data = array())
    {
        if (method_exists($this, $method))
            return $this->$method($data);
        else
        	$this->index($method);
    }

    public function index($uri)
    {
    	$this->load->smodel('shop_model');

		if(count($this->data->url()) > 1)
		{
			$type = null;
			$this->shop_model->getBreadcrumbs = true;
			$product = $this->shop_model->routeURL($this->data->url(), $type);

			if($type == 'product' && $product)
			{
				if($product->active == 0 && !$this->userCan())
					$this->load->page_404(false);

				$this->wl_alias_model->setContent($product->id);
				$_SESSION['alias']->name = $product->name;
				$_SESSION['alias']->breadcrumbs = $this->shop_model->breadcrumbs;
				if($videos = $this->wl_alias_model->getVideosFromText())
				{
					$this->load->library('video');
					$this->video->setVideosToText($videos);
				}
				$this->setProductPrice($product);
				if(!empty($product->similarProducts))
					$this->setProductsPrice($product->similarProducts);

				if(!empty($_SESSION['alias']->images))
					foreach ($_SESSION['alias']->images[0] as $key => $path) {
						if($key == 'path')
							$product->photo = $path;
						else
						{
							$key = substr($key, 0, -4) .'photo';
							$product->$key = $path;
						}
					}

				$this->load->page_view('detal_view', array('product' => $product));
			}
			elseif($_SESSION['option']->useGroups && $type == 'group' && $product)
			{
				if($product->active == 0 && !$this->userCan())
					$this->load->page_404(false);
				$group = clone $product;
				unset($product);

				$this->wl_alias_model->setContent(-$group->id);
				$_SESSION['alias']->breadcrumbs = $this->shop_model->breadcrumbs;
				$subgroups = $products = $filters = false;

				if($group->haveChild)
				{
					$subgroups = $this->db->cache_get('subgroups/group-'.$group->id);
					if($subgroups === NULL)
					{
						$subgroups = $this->shop_model->getGroups($group->id);
						$this->db->cache_add('subgroups/group-'.$group->id, $subgroups);
					}
				}

				if($_SESSION['option']->showProductsParentsPages || !$subgroups)
				{
					$filter = false;
					if(count($_GET) > 1)
						foreach ($_GET as $key => $value) {
							if($key != 'request' && $key != 'page')
							{
								$filter = true;
								break;
							}
						}
					if($filter)
						$products = $this->shop_model->getProducts($group->id);
					else
					{
						$cache_key = 'group-'.$group->id;
						if(isset($_SESSION['option']->paginator_per_page) && $_SESSION['option']->paginator_per_page > 0)
						{
							$page = 1;
							if(isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 1)
								$page = $_GET['page'];
							$cache_key .= '-page-'.$page;
						}

						$products = $this->db->cache_get('products_in_group/'.$cache_key);
						if($products === NULL)
						{
							$products = $this->shop_model->getProducts($group->id);
							$this->db->cache_add('products_in_group/'.$cache_key, $products);
						}
						else
						{
							if(count($products) >= $_SESSION['option']->paginator_per_page)
								$_SESSION['option']->paginator_total = $this->shop_model->getProductsCountInGroup($group->id);
							else
								$_SESSION['option']->paginator_total = count($products);
						}
					}
					if($products)
					{
						if($_SESSION['option']->showProductsParentsPages || !$group->haveChild)
							$filters = $this->shop_model->getOptionsToGroup($group->id);
						$this->setProductsPrice($products);
					}
				}

				$this->load->page_view('group_view', array('group' => $group, 'subgroups' => $subgroups, 'products' => $products, 'filters' => $filters, 'catalogAllGroups' => $this->shop_model->getGroups(-1)));
			}
			else
				$this->load->page_404(false);
		}
		else
		{
			$this->wl_alias_model->setContent();
			if($videos = $this->wl_alias_model->getVideosFromText())
			{
				$this->load->library('video');
				$this->video->setVideosToText($videos);
			}
			
			if($_SESSION['option']->useGroups)
			{
				$groups = $this->shop_model->getGroups(-1);
				$this->load->page_view('index_view', array('catalogAllGroups' => $groups));
			}
			else
			{
				$products = $this->shop_model->getProducts();
				$this->setProductsPrice($products);
				$this->load->page_view('group_view', array('products' => $products));
			}
		}
    }

	public function search()
	{
		if(isset($_GET['name']) || isset($_GET['group']))
		{
			if(isset($_GET['name']) && is_numeric($_GET['name']))
				$this->redirect($_SESSION['alias']->alias.'/'.$_GET['name']);

			$this->load->smodel('shop_model');
			$_SESSION['alias']->name = $_SESSION['alias']->title = $this->text('Пошук по назві');

			if(isset($_GET['name']))
				$_SESSION['alias']->name = $_SESSION['alias']->title = $this->text('Пошук по назві')." '{$this->data->get('name')}'";

			$group_id = 0;
			if(isset($_GET['group']))
			{
				$this->db->select($this->shop_model->table('_groups').' as g', 'id', $this->data->get('group'), 'link');
				$where = array('content' => '#-g.id');
				if($_SESSION['language']) $where['language'] = $_SESSION['language'];
				$this->db->join('wl_ntkd', 'name, title', $where);
				if($group = $this->db->get())
				{
					$_SESSION['alias']->name = $_SESSION['alias']->title = $this->text('Пошук', 0).' '.$group->title;
					$group_id = $group->id;
				}
			}

			$products = $this->shop_model->getProducts($group_id);
			$this->setProductsPrice($products);

			$this->load->page_view('group_view', array('products' => $products));
		}
		if(isset($_GET['id']) || isset($_GET['article']))
		{
			$this->load->smodel('shop_model');
			$id = 0;
			$key = 'id';
			if(isset($_GET['article']))
			{
				$id = $this->makeArticle($this->data->get('article'));
				$key = 'article';
			}
			else
				$id = $this->data->get('id');
			$product = $this->shop_model->getProduct($id, $key, false);
			$products = $this->shop_model->getProducts('%'.$id);

			if($this->userIs() && !$this->userCan())
			{
				if($product)
					$this->shop_model->searchHistory($product->id);
				else
					$this->shop_model->searchHistory(0, $id);
			}

			if($product || count($products) > 0)
			{
				$link = $product ? $product->link : $products[0]->link;
				$this->load->page_view('detal_view', array('product' => $product, 'products' => $products));
			}
			else
				$this->load->page_view('group_view', array('products' => null));
		}
	}

	public function ajaxGetProducts()
	{
		if(isset($_POST['params']))
			foreach ($_POST['params'] as $key => $value) {
				if(is_array($value))
				{
					foreach ($value as $secondValue) {
						$_GET[$key][] = $secondValue;
					}
				}
				else
					$_GET[$key] = $value;
			}

		$_GET['page'] = $this->data->post('page');
		$group = $this->data->post('group') > 0 ? $this->data->post('group') : '-1' ;

		$this->load->smodel('shop_model');
		$products = $this->shop_model->getProducts($group);
		$this->setProductsPrice($products);
		$this->load->json(array('products' => $products, 'page' => $_GET['page']+1, 'group' => $group));
	}

	public function ajaxUpdateProductPrice()
	{
		if ($product_id = $this->data->post('product')) {
			if (!empty($_POST['options']) && is_array($_POST['options'])) {
				$this->load->smodel('shop_model');
				$price = 0;
				if($product = $this->shop_model->getProductPriceWithOptions($product_id, $_POST['options']))
				{
					$this->setProductPrice($product);
					$price = $product->price;
				}
				$this->load->json(array('price' => $price, 'product' => $product_id));
			}
		}
	}

	public function export_prom()
	{
		if(isset($_GET['key']) && !empty($_SESSION['option']->exportKey) && $_SESSION['option']->exportKey == $_GET['key'])
		{
			ini_set('max_execution_time', 1800);
			ini_set('max_input_time', 1800);
			ini_set('memory_limit', '1024M');

			$this->load->library('ymlgenerator');
			$this->load->smodel('shop_model');
			$this->load->smodel('export_model');
			$this->export_model->init('prom');
			$products = $groups = array();

			$checkedGroups = -1;
	        if(!empty($_GET['group']) && is_numeric($_GET['group']))
	            $checkedGroups = $_GET['group'];
        
	        if($groups = $this->export_model->getGroups($checkedGroups))
	        {
                $checkedGroups = array();
                foreach ($groups as $group) {
                    $checkedGroups[] = $group->id;
                }

                $products = $this->export_model->getProducts($checkedGroups, false, $go);
		        $this->setProductsPrice($products);
				$this->ymlgenerator->createYml($products, $groups);
	        }
	        else
	        	echo "There are no active export groups";
		}
		else
			echo '<img src="'.SERVER_URL.'style/images/access_denied.jpg" width="100%">';
		exit;
	}

	public function export_google()
	{
		if(isset($_GET['key']) && !empty($_SESSION['option']->exportKey) && $_SESSION['option']->exportKey == $_GET['key'])
		{
			ini_set('max_execution_time', 1800);
			ini_set('max_input_time', 1800);
			ini_set('memory_limit', '1024M');

			$this->load->library('google_feed');
			$this->load->smodel('shop_model');
			$this->load->smodel('export_model');
			$this->export_model->init('google');
			$products = $groups = array();

			$checkedGroups = -1;
	        if(!empty($_GET['group']) && is_numeric($_GET['group']))
	            $checkedGroups = $_GET['group'];
        
	        if($groups = $this->export_model->getGroups($checkedGroups))
	        {
                $checkedGroups = array();
                foreach ($groups as $group) {
                    $checkedGroups[] = $group->id;
                }

                $products = $this->export_model->getProducts($checkedGroups);
		        $this->setProductsPrice($products);

				$this->google_feed->createXml($products, $groups);
	        }
	        else
	        	echo "There are no active export groups";
		}
		else
			echo '<img src="'.SERVER_URL.'style/images/access_denied.jpg" width="100%">';
		exit;
	}

	public function export_facebook()
	{
		if(isset($_GET['key']) && !empty($_SESSION['option']->exportKey) && $_SESSION['option']->exportKey == $_GET['key'])
		{
			ini_set('max_execution_time', 1800);
			ini_set('max_input_time', 1800);
			ini_set('memory_limit', '1024M');

			$this->load->library('facebook_feed');
			$this->load->smodel('shop_model');
			$this->load->smodel('export_model');
			$this->export_model->init('facebook');
			$products = $groups = array();

			$checkedGroups = -1;
	        if(!empty($_GET['group']) && is_numeric($_GET['group']))
	            $checkedGroups = $_GET['group'];
        
	        if($groups = $this->export_model->getGroups($checkedGroups))
	        {
                $checkedGroups = array();
                foreach ($groups as $group) {
                    $checkedGroups[] = $group->id;
                }

                $products = $this->export_model->getProducts($checkedGroups);
		        $this->setProductsPrice($products);

				$this->facebook_feed->createXml($products, $groups);
	        }
	        else
	        	echo "There are no active export groups";
		}
		else
			echo '<img src="'.SERVER_URL.'style/images/access_denied.jpg" width="100%">';
		exit;
	}

    public function __get_Search($content)
    {
    	$this->load->smodel('shop_search_model');
    	return $this->shop_search_model->getByContent($content);
    }

    public function __get_SiteMap_Links()
    {
        $data = $row = array();
        $row['link'] = $_SESSION['alias']->alias;
        $row['alias'] = $_SESSION['alias']->id;
        $row['content'] = 0;
        // $row['code'] = 200;
        // $row['data'] = '';
        // $row['time'] = time();
        // $row['changefreq'] = 'daily';
        // $row['priority'] = 5;
        $data[] = $row;

        $this->load->smodel('shop_search_model');
        if($products = $this->shop_search_model->getProducts_SiteMap())
        	foreach ($products as $product)
            {
            	if(!$product->skip)
            	{
	            	$row['link'] = $product->link;
	            	$row['content'] = $product->id;
	            	$data[] = $row;
	            }
            }

       	if($_SESSION['option']->useGroups)
	        if($groups = $this->shop_search_model->getGroups_SiteMap())
	        	foreach ($groups as $group)
	            {
	            	$row['link'] = $group->link;
	            	$row['content'] = -$group->id;
	            	$data[] = $row;
	            }

        return $data;
    }
    
    // $id['key'] може мати любий ключ _products. Рекомендовано: id, article, alias.
	public function __get_Product($id = 0)
	{
		$key = 'id';
		$additionalFileds = $options = false;
		if(is_array($id))
		{
			if(isset($id['options']) && is_array($id['options']))
				$options = $id['options'];
			if(isset($id['additionalFileds']) && is_array($id['additionalFileds']))
				$additionalFileds = $id['additionalFileds'];
			if(isset($id['key'])) $key = $id['key'];
			if(isset($id['id'])) $id = $id['id'];
			else if(isset($id['article'])) $id = $id['article'];
		}

		$this->load->smodel('shop_model');
		$this->shop_model->getBreadcrumbs = false;
		if($product = $this->shop_model->getProduct($id, $key))
		{
			if($options)
				$product = $this->shop_model->getProductPriceWithOptions($product, $options);
			if($additionalFileds)
				foreach ($additionalFileds as $key => $value) {
					$product->$key = $value;
				}
			$this->setProductPrice($product);
		}
		return $product;
	}

	public function __get_Products($data = array())
	{
		$group = -1;
		$noInclude = 0;
		$active = true;
		$getProductOptions = $additionalFileds = false;
		if(isset($data['article']) && $data['article'] != '')
		{
			$article = (string) $data['article'];
			$article = trim($article);
			$article = mb_strtoupper($article);
			$data['article'] = str_replace([' ', '-', '.', ',', '/'], '', $article);
			$group = '%'.$data['article'];
		}
		elseif(isset($data['group']) && (is_numeric($data['group']) || is_array($data['group']))) $group = $data['group'];
		if(isset($data['limit']) && is_numeric($data['limit'])) $_SESSION['option']->paginator_per_page = $data['limit'];
		if(isset($data['sort']) && $data['sort'] != '') $_SESSION['option']->productOrder = $data['sort'];
		if(isset($data['sale']) && $data['sale'] == 1) $_GET['sale'] = 1;
		if(isset($data['noInclude']) && $data['noInclude'] > 0) $noInclude = $data['noInclude'];
		if(isset($data['active']) && $data['active'] == false) $active = false;
		if(isset($data['getProductOptions']) && $data['getProductOptions'] == true) $getProductOptions = true;
		if(isset($data['additionalFileds']) && is_array($data['additionalFileds']))
			$additionalFileds = $data['additionalFileds'];

		$this->load->smodel('shop_model');
		$products = $this->shop_model->getProducts($group, $noInclude, $active, $getProductOptions);
		if($additionalFileds)
			foreach ($products as $product)
				foreach ($additionalFileds as $key => $value) {
					$product->$key = $value;
				}
		$this->setProductsPrice($products);

		return $products;
	}

	public function __get_Group($id = 0)
	{
		if(empty($id))
			return false;
		$this->load->smodel('shop_model');
		return $this->shop_model->getGroupByAlias($id, false, 'id');
	}

	public function __get_Groups($parent)
	{
		if(empty($parent))
			$parent = 0;
		$this->load->smodel('shop_model');
		return $this->shop_model->getGroups($parent, false);
	}

	public function __get_OptionsToGroup($group_id)
	{
		$this->load->smodel('shop_model');
		return $this->shop_model->getOptionsToGroup($group_id);
	}

	public function __get_Values_To_Option($id = 0)
	{
		$this->load->smodel('shop_model');
		$this->db->select($this->shop_model->table('_options').' as o', '*', -$id, 'group');
		$where = array('option' => '#o.id');
		if($_SESSION['language']) $where['language'] = $_SESSION['language'];
		$this->db->join($this->shop_model->table('_options_name'), 'name', $where);
		return $this->db->get('array');
	}

	public function __get_Option_Info($id = 0)
	{
		$this->load->smodel('shop_model');
		$this->db->select($this->shop_model->table('_options').' as o', '*', $id);
		$where = array('option' => '#o.id');
		if($_SESSION['language']) $where['language'] = $_SESSION['language'];
		$this->db->join($this->shop_model->table('_options_name'), 'name', $where);
		if($option = $this->db->get('single'))
		{
			$option->values = $this->__get_Values_To_Option($option->id);
			return $option;
		}
		return false;
	}

	public function __get_Price_With_options($info)
	{
		if (isset($info['product']) && isset($info['options']) && is_array($info['options'])) {
			$this->load->smodel('shop_model');
			if($product = $this->shop_model->getProductPriceWithOptions($info['product'], $info['options']))
			{
				$this->setProductPrice($product);
				return $product->price;
			}
		}
		return false;
	}

	public function __formatPrice($price)
	{
		$this->load->smodel('shop_model');
		return $this->shop_model->formatPrice($price);;
	}

	private function makeArticle($article)
	{
		$article = (string) $article;
		$article = trim($article);
		$article = strtoupper($article);
		$article = str_replace('-', '', $article);
		return str_replace(' ', '', $article);
	}

	private function setProductPrice(&$product)
	{
		$product->price_in = $product->price;
    	$product->old_price_in = $product->old_price;

		if($_SESSION['option']->useMarkUp > 0 && $product->markup)
		{
    		$product->price *= $product->markup;
    		$product->old_price *= $product->markup;
    	}

    	$product->price_before = $product->price;
		if(!empty($this->marketing) && $product)
			foreach ($this->marketing as $marketingAliasId) {
				$product = $this->load->function_in_alias($marketingAliasId, '__update_Product', $product);
			}
		$product->discount = $product->price_before - $product->price;
		if(!empty($_SESSION['currency']) && is_array($_SESSION['currency']) && isset($_SESSION['currency'][$product->currency]))
		{
			$product->price *= $_SESSION['currency'][$product->currency];
			$product->old_price *= $_SESSION['currency'][$product->currency];
			$product->discount *= $_SESSION['currency'][$product->currency];
		}
		
		$product->price_format = $this->shop_model->formatPrice($product->price);
		$product->old_price_format = $this->shop_model->formatPrice($product->old_price);
		if(!empty($product->quantity))
			$product->sum_format = $this->shop_model->formatPrice($product->price * $product->quantity);
	}

	private function setProductsPrice(&$products)
	{
		if($products)
		{
			foreach ($products as $product) {
				$product->price_in = $product->price;
		    	$product->old_price_in = $product->old_price;

				if($_SESSION['option']->useMarkUp > 0 && $product->markup)
				{
		    		$product->price *= $product->markup;
		    		$product->old_price *= $product->markup;
		    	}

		    	$product->price_before = $product->price;
			}
		
			if(!empty($this->marketing) && $products)
				foreach ($this->marketing as $marketingAliasId) {
					$products = $this->load->function_in_alias($marketingAliasId, '__update_Products', $products);
				}

			foreach ($products as $product) {
				$product->discount = $product->price_before - $product->price;
				if(!empty($_SESSION['currency']) && is_array($_SESSION['currency']) && isset($_SESSION['currency'][$product->currency]))
				{
					$product->price *= $_SESSION['currency'][$product->currency];
					$product->old_price *= $_SESSION['currency'][$product->currency];
					$product->discount *= $_SESSION['currency'][$product->currency];
				}
				
				$product->price_format = $this->shop_model->formatPrice($product->price);
				$product->old_price_format = $this->shop_model->formatPrice($product->old_price);
			}
		}
	}

	public function __setProduct_sPrice($products)
	{
		$this->load->smodel('shop_model');
		if(is_object($products))
			$this->setProductPrice($products);
		if(is_array($products))
			$this->setProductsPrice($products);
		return $products;
	}

}

?>