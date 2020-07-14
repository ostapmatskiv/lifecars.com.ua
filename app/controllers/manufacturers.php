<?php 

/**
  * for lifecars
  */
 class manufacturers extends Controller
 {

 	function _remap($method, $data = array())
    {
        if (method_exists($this, $method))
        {
            if(empty($data))
                $data = null;
            return $this->$method($data);
        }
        else
            $this->index($method);
    }
 	
 	public function index($uri='')
 	{
 		$manufactures = $this->db->select('s_shopshowcase_options as o', 'id, photo', ['group' => -1])
                                    ->join('s_shopshowcase_options_name as n', 'name', ['option' => '#o.id', 'language' => $_SESSION['language']])
                                    ->order('name', 'n')
                                    ->get('array');
        $links = $names = [];
        if($manufactures)
        	foreach ($manufactures as &$manufacturer) {
        		$link = $this->data->latterUAtoEN($manufacturer->name);
        		if(array_key_exists($link, $links))
        		{
        			$i = 2;
        			while (array_key_exists($link.'-'.$i, $links)) {
        				$i++;
        			}
        			$link = $link.'-'.$i;
        		}
        		$manufacturer->link = $link;
        		$links[$link] = $manufacturer->id;
        		$names[$manufacturer->id] = $manufacturer->name;
        	}
 		if (empty($uri)) {
 			$this->wl_alias_model->setContent();
 			$this->load->page_view('manufacturers/index_view', ['manufactures' => $manufactures]);
 		}
 		elseif(array_key_exists($uri, $links))
 		{
 			$id = $links[$uri];
 			$_GET['1-manufacturer'][] = $id;
 			$this->wl_alias_model->init('parts');
 			$products = $this->load->function_in_alias('parts', '__get_Products');
 			$_SESSION['alias']->alias = 'manufacturers';
 			$_SESSION['alias']->name = $_SESSION['alias']->title = $names[$id];
 			$this->load->page_view('search_view', array('products' => $products));
 		}
 		else
 			$this->load->page_404(false);
 	}

 } ?>