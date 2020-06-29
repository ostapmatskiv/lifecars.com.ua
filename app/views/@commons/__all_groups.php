<?php if(empty($catalogAllGroups)) {
	if($_SESSION['alias']->alias == 'shop')
	{
		if(empty($this->shop_model))
		{
			$this->load->smodel('shop_model');
			$this->shop_model->init();
		}
		$catalogAllGroups = $this->shop_model->allGroups;
	}
	else
	{
		$catalogAllGroups = $this->db->cache_get('allGroups', 'shop');
		if($catalogAllGroups === NULL)
			$catalogAllGroups = $this->db->select('s_shopshowcase_groups as g', 'id, alias, parent', ['active' => 1, 'hide' => 0])
							->join('wl_ntkd', 'name', ['alias' => 8, 'content' => '#-g.id', 'language' => $_SESSION['language']])
							->join('wl_images', 'file_name as photo', ['alias' => 8, 'content' => '#-g.id', 'position' => 1])
							->order('position')->get();
	}
} ?>