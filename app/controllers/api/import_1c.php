<?php

/**
 * for lifecars.com.ua
 */
class import_1c extends Controller
{

	private $folder = 'import/';
	private $shop_wl_alias = 8;
	private $manufacturer_option_id = 1;
	private $category_option_id = 2;
	private $price_id_1c = '000000001';
	private $site_manufactures = []; // [id_1c] = $id_site
	private $site_manufactures_alias = []; // [id_site] = $alias
	private $site_cars = [];
	private $site_categories = [];
	
	public function index()
	{
		if($file_name = $this->data->get('file'))
		{
			if (file_exists($this->folder.$file_name))
			{
				if($file = simplexml_load_file($this->folder.$file_name))
				{
					ini_set('max_execution_time', 1800);
					ini_set('max_input_time', 1800);
					ini_set('memory_limit', '1024M');
			
					$file_name = explode('_', $file_name);
					$all = end($file_name);
					$all = strtolower($all);
					$all = $all == 'all.xml' ? true : false;
					if($file_name[0] == 'VygruzkaNomenklatury')
						$this->parse_VygruzkaNomenklatury($file, $all);
					else if($file_name[0] == 'VygruzkaKategorij')
						$this->parse_VygruzkaKategorij($file);
					else if($file_name[0] == 'VygruzkaZalyshkiv')
						$this->parse_VygruzkaZalyshkiv($file, $all);
				}
			}
			else
				echo $file_name." not found";
		}
		else
			echo "param file required!";
	}

	private $update_VygruzkaNomenklatury = 0;
	private function parse_VygruzkaNomenklatury($file, $all)
	{
		// echo "<pre>";
		// print_r($file);
		if(!empty($file->Производители))
			$this->parse_manufacturers($file->Производители);

		if(!empty($file->Марки) && !empty($file->Автомобили))
			$this->parse_groups($file->Марки, $file->Автомобили);

		if(!empty($file->ПодчиненнаяНоменклатуры) && !empty($file->ПодчиненнаяНоменклатуры))
			$this->parse_products($file->ПодчиненнаяНоменклатуры, $all);

		if($this->update_VygruzkaNomenklatury > 0)
			$this->db->cache_delete_all(false, 'parts');
	}

	private function parse_manufacturers($manufacturers)
	{
		$searchKeys = $xml_manufacturers = [];

		if(isset($manufacturers->Производитель))
			foreach ($manufacturers->Производитель as $manufacturer) {
				$key = $this->xml_attribute($manufacturer, 'Код');
				if(!empty($key))
				{
					$searchKeys[] = $key;
					$xml_manufacturers[$key] = ['uk' => $this->xml_attribute($manufacturer, 'НаименованиеУкр'), 'ru' => $this->xml_attribute($manufacturer, 'НаименованиеРос')];
				}
			}

		if(!empty($searchKeys))
		{
			$my_manufactures = $this->db->select('s_shopshowcase_options as o', 'id, alias', ['group' => -$this->manufacturer_option_id, 'alias' => $searchKeys])
										->join('s_shopshowcase_options_name as uk', 'id as name_id_uk, name as name_uk', ['option' => '#o.id', 'language' => 'uk'])
										->join('s_shopshowcase_options_name as ru', 'id as name_id_ru, name as name_ru', ['option' => '#o.id', 'language' => 'ru'])
										->get('array');

			$last_position = 0;
			foreach ($searchKeys as $key) {
				$find = false;
				if(!empty($my_manufactures))
					foreach ($my_manufactures as $my_manufacturer) {
						if($my_manufacturer->alias == $key)
						{
							if(empty($this->site_manufactures[$key]))
							{
								$this->site_manufactures[$key] = $my_manufacturer->id;
								$this->site_manufactures_alias[$my_manufacturer->id] = $this->prepareArticleKey($xml_manufacturers[$key]['uk']);
							}
							if($my_manufacturer->name_uk != $xml_manufacturers[$key]['uk'])
							{
								$this->db->updateRow('s_shopshowcase_options_name', ['name' => $xml_manufacturers[$key]['uk']], $my_manufacturer->name_id_uk);
								$this->update_VygruzkaNomenklatury++;
							}
							if($my_manufacturer->name_ru != $xml_manufacturers[$key]['ru'])
							{
								$this->db->updateRow('s_shopshowcase_options_name', ['name' => $xml_manufacturers[$key]['ru']], $my_manufacturer->name_id_ru);
								$this->update_VygruzkaNomenklatury++;
							}
							$find = true;
							break;
						}
					}
				if(!$find)
				{
					if($last_position == 0)
						$last_position = $this->db->getCount('s_shopshowcase_options', -$this->manufacturer_option_id, 'group');
					$last_position++;
					$insert = ['wl_alias' => $this->shop_wl_alias, 'group' => -$this->manufacturer_option_id, 'alias' => $key, 'position' => $last_position, 'active' => 1];
					$id = $this->db->insertRow('s_shopshowcase_options', $insert);
					$this->site_manufactures[$key] = $id;
					$this->site_manufactures_alias[$id] = $this->prepareArticleKey($xml_manufacturers[$key]['uk']);
					$this->db->insertRow('s_shopshowcase_options_name', ['option' => $id, 'language' => 'uk', 'name' => $xml_manufacturers[$key]['uk']]);
					$this->db->insertRow('s_shopshowcase_options_name', ['option' => $id, 'language' => 'ru', 'name' => $xml_manufacturers[$key]['ru']]);
				}
			}
		}
	}

	private function parse_groups($manufacturers, $cars)
	{
		// print_r($manufacturers);
		$update_allGroups = false;
		$searchKeys = $xml_manufacturers = [];
		if(isset($manufacturers->Марка))
			foreach ($manufacturers->Марка as $manufacturer) {
				$key = $this->xml_attribute($manufacturer, 'Код');
				if(!empty($key))
				{
					$searchKeys[] = $key;
					$xml_manufacturers[$key] = ['uk' => $this->xml_attribute($manufacturer, 'НаименованиеУкр'), 'ru' => $this->xml_attribute($manufacturer, 'НаименованиеРос')];
				}
			}
		if(!empty($searchKeys))
		{
			$my_manufactures = $this->db->select('s_shopshowcase_groups as g', 'id, id_1c', ['wl_alias' => $this->shop_wl_alias, 'id_1c' => $searchKeys, 'parent' => 0])
										->join('wl_ntkd as uk', 'id as name_id_uk, name as name_uk', ['alias' => $this->shop_wl_alias, 'content' => '#-g.id', 'language' => 'uk'])
										->join('wl_ntkd as ru', 'id as name_id_ru, name as name_ru', ['alias' => $this->shop_wl_alias, 'content' => '#-g.id', 'language' => 'ru'])
										->get('array');

			$last_position = 0;
			$insert = ['wl_alias' => $this->shop_wl_alias, 'parent' => 0, 'active' => 1, 'hide' => 0, 'author_add' => 0, 'author_edit' => 0];
			$insert['date_add'] = $insert['date_edit'] = time();
			foreach ($searchKeys as $key) {
				$find = false;
				if(!empty($my_manufactures))
					foreach ($my_manufactures as $my_manufacturer) {
						if($my_manufacturer->id_1c == $key)
						{
							$xml_manufacturers[$key]['id'] = $my_manufacturer->id;
							if($my_manufacturer->name_uk != $xml_manufacturers[$key]['uk'])
							{
								$this->db->updateRow('wl_ntkd', ['name' => $xml_manufacturers[$key]['uk']], $my_manufacturer->name_id_uk);
								$this->load->function_in_alias($this->shop_wl_alias, '__after_edit', -$my_manufacturer->id, true);
							}
							if($my_manufacturer->name_ru != $xml_manufacturers[$key]['ru'])
							{
								$this->db->updateRow('wl_ntkd', ['name' => $xml_manufacturers[$key]['ru']], $my_manufacturer->name_id_ru);
								$this->load->function_in_alias($this->shop_wl_alias, '__after_edit', -$my_manufacturer->id, true);
							}
							$find = true;
							break;
						}
					}
				if(!$find)
				{
					if($last_position == 0)
						$last_position = $this->db->getCount('s_shopshowcase_groups', ['wl_alias' => $this->shop_wl_alias, 'parent' => 0]);
					$last_position++;
					$insert['id_1c'] = $key;
					$insert['alias'] = $this->data->latterUAtoEN($xml_manufacturers[$key]['uk']);
					$insert['position'] = $last_position;
					$id = $this->db->insertRow('s_shopshowcase_groups', $insert);
					$xml_manufacturers[$key]['id'] = $id;
					$this->db->insertRow('wl_ntkd', ['alias' => $this->shop_wl_alias, 'content' => -$id, 'language' => 'uk', 'name' => $xml_manufacturers[$key]['uk']]);
					$this->db->insertRow('wl_ntkd', ['alias' => $this->shop_wl_alias, 'content' => -$id, 'language' => 'ru', 'name' => $xml_manufacturers[$key]['ru']]);
					$update_allGroups = true;
				}
			}
		}

		if($update_allGroups)
			$this->db->cache_delete('allGroups', 'parts');

		// print_r($cars);
		$searchKeys = $xml_cars = [];
		if(isset($cars->Авто))
			foreach ($cars->Авто as $car) {
				$key = $this->xml_attribute($car, 'Код');
				$manufacturer = $this->xml_attribute($car, 'Марка');
				if(!empty($key) && !empty($manufacturer) && isset($xml_manufacturers[$manufacturer]))
				{
					$searchKeys[] = $key;
					$xml_cars[$key] = ['parent' => $xml_manufacturers[$manufacturer]['id'], 'uk' => $this->xml_attribute($car, 'НаименованиеУкр'), 'ru' => $this->xml_attribute($car, 'НаименованиеРос')];
				}
			}
		if(!empty($searchKeys))
		{
			$my_cars = $this->db->select('s_shopshowcase_groups as g', 'id, id_1c, parent', ['wl_alias' => $this->shop_wl_alias, 'id_1c' => $searchKeys])
										->join('wl_ntkd as uk', 'id as name_id_uk, name as name_uk', ['alias' => $this->shop_wl_alias, 'content' => '#-g.id', 'language' => 'uk'])
										->join('wl_ntkd as ru', 'id as name_id_ru, name as name_ru', ['alias' => $this->shop_wl_alias, 'content' => '#-g.id', 'language' => 'ru'])
										->get('array');

			$last_position = $last_position_parent = 0;
			$insert = ['wl_alias' => $this->shop_wl_alias, 'active' => 1, 'hide' => 0, 'author_add' => 0, 'author_edit' => 0];
			$insert['date_add'] = $insert['date_edit'] = time();
			foreach ($searchKeys as $key) {
				if(empty($xml_cars[$key]['parent']))
					continue;
				$parent = $xml_cars[$key]['parent'];
				$find = false;
				if(!empty($my_cars))
					foreach ($my_cars as $my_car) {
						if(!isset($this->site_cars[$my_car->id_1c]))
							$this->site_cars[$my_car->id_1c] = $my_car->id;
						if($my_car->id_1c == $key)
						{
							if($my_car->parent != $parent)
							{
								$this->db->updateRow('s_shopshowcase_groups', ['parent' => $xml_cars[$key]['parent']], $my_car->id);
								$this->load->function_in_alias($this->shop_wl_alias, '__after_edit', -$my_car->id, true);
							}
							if($my_car->name_uk != $xml_cars[$key]['uk'])
							{
								$this->db->updateRow('wl_ntkd', ['name' => $xml_cars[$key]['uk']], $my_car->name_id_uk);
								$this->load->function_in_alias($this->shop_wl_alias, '__after_edit', -$my_car->id, true);
							}
							if($my_car->name_ru != $xml_cars[$key]['ru'])
							{
								$this->db->updateRow('wl_ntkd', ['name' => $xml_cars[$key]['ru']], $my_car->name_id_ru);
								$this->load->function_in_alias($this->shop_wl_alias, '__after_edit', -$my_car->id, true);
							}
							$find = true;
							break;
						}
					}
				if(!$find)
				{
					if($last_position == 0 || $last_position_parent != $parent)
						$last_position = $this->db->getCount('s_shopshowcase_groups', ['wl_alias' => $this->shop_wl_alias, 'parent' => $parent]);
					$last_position++;
					$last_position_parent = $parent;
					$insert['id_1c'] = $key;
					$insert['alias'] = $this->data->latterUAtoEN($xml_cars[$key]['uk']);
					$insert['parent'] = $parent;
					$insert['position'] = $last_position;
					$id = $this->db->insertRow('s_shopshowcase_groups', $insert);
					$this->site_cars[$key] = $id;
					$this->db->insertRow('wl_ntkd', ['alias' => $this->shop_wl_alias, 'content' => -$id, 'language' => 'uk', 'name' => $xml_cars[$key]['uk']]);
					$this->db->insertRow('wl_ntkd', ['alias' => $this->shop_wl_alias, 'content' => -$id, 'language' => 'ru', 'name' => $xml_cars[$key]['ru']]);
					$this->load->function_in_alias($this->shop_wl_alias, '__after_edit', -$parent, true);
				}
			}
		}
	}

	private function parse_products($file_products, $all)
	{
		// $this->db->shopDBdump = true;
		// print_r($file_products);
		$searchKeys = $site_CatUseIn = $my_product_CatUseIn = $my_product_images = $my_analog_groups = $inserted_products_link = [];
		if(isset($file_products->ПодчиненнаяНоменклатура) && !$all)
		{
			foreach ($file_products->ПодчиненнаяНоменклатура as $product) {
				$key = $this->xml_attribute($product, 'Код');
				if(!empty($key))
					$searchKeys[] = $key;
			}
			$all_in_db = $this->db->getCount('s_shopshowcase_products');
			$hulf_in_db = ceil($all_in_db / 2);
			if(count($searchKeys) > $hulf_in_db)
			{
				echo "in xml: ".count($searchKeys)."; in db: {$all_in_db} products. use all mode (more 50%)";
				$all = true;
			}
		}
		$s_shopshowcase_options = $this->db->select('s_shopshowcase_options', 'id, alias', ['group' => -$this->category_option_id])->get('array');
		if($s_shopshowcase_options)
			foreach ($s_shopshowcase_options as $option) {
				$site_CatUseIn[$option->alias] = $option->id;
			}


		if(!empty($searchKeys) || $all)
		{
			$where = ['wl_alias' => $this->shop_wl_alias];
			if(!$all)
				$where['id_1c'] = $searchKeys;
			$my_products = $this->db->select('s_shopshowcase_products as p', 'id, id_1c, alias, article_show, group', $where)
										->join('s_shopshowcase_groups as g', 'id_1c as group_id_1c', '#p.group')
										->join('wl_ntkd as uk', 'id as name_id_uk, name as name_uk, list as list_uk, text as text_uk', ['alias' => $this->shop_wl_alias, 'content' => '#p.id', 'language' => 'uk'])
										->join('wl_ntkd as ru', 'id as name_id_ru, name as name_ru, list as list_ru, text as text_ru', ['alias' => $this->shop_wl_alias, 'content' => '#p.id', 'language' => 'ru'])
										->join('s_shopshowcase_product_options', 'id as row_manufacturer_id, value as manufacturer_id', ['option' => $this->manufacturer_option_id, 'product' => '#p.id'])
										->get('array');
			if(!empty($my_products))
			{
				if($all)
				{
					foreach ($my_products as $my_product) {
						$inserted_products_link[] = $my_product->alias;
					}

					if($s_shopshowcase_product_options = $this->db->select('s_shopshowcase_product_options', 'product, value', ['option' => $this->category_option_id])->get('array'))
						foreach ($s_shopshowcase_product_options as $po) {
							if(isset($my_product_CatUseIn[$po->product]))
								$my_product_CatUseIn[$po->product][] = $po->value;
							else
								$my_product_CatUseIn[$po->product] = [$po->value];
						}

					if($wl_images = $this->db->select('wl_images', 'content, id_1c', ['alias' => $this->shop_wl_alias, 'content' => '>0'])->get('array'))
						foreach ($wl_images as $image) {
							if(isset($my_product_images[$image->content]))
								$my_product_images[$image->content][] = $image->id_1c;
							else
								$my_product_images[$image->content] = [$image->id_1c];
						}
				}
				else
				{
					$ids = [];
					foreach ($my_products as $my_product) {
						$ids[] = $my_product->id;
						$inserted_products_link[] = $my_product->alias;
					}

					if($s_shopshowcase_product_options = $this->db->select('s_shopshowcase_product_options', 'product, value', ['option' => $this->category_option_id, 'product' => $ids])->get('array'))
						foreach ($s_shopshowcase_product_options as $po) {
							if(isset($my_product_CatUseIn[$po->product]))
								$my_product_CatUseIn[$po->product][] = $po->value;
							else
								$my_product_CatUseIn[$po->product] = [$po->value];
						}

					if($wl_images = $this->db->select('wl_images', 'content, id_1c', ['alias' => $this->shop_wl_alias, 'content' => $ids])->get('array'))
						foreach ($wl_images as $image) {
							if(isset($my_product_images[$image->content]))
								$my_product_images[$image->content][] = $image->id_1c;
							else
								$my_product_images[$image->content] = [$image->id_1c];
						}
				}
			}
			
			$last_position = 0;
			$insert = ['wl_alias' => $this->shop_wl_alias, 'active' => 1, 'price' => 0, 'old_price' => 0, 'promo' => 0, 'currency' => '', 'group' => 0, 'availability' => 0, 'author_add' => 0, 'author_edit' => 0];
			$insert['date_add'] = $insert['date_edit'] = time();

			foreach ($file_products->ПодчиненнаяНоменклатура as $xml_product) {
				$key = $this->xml_attribute($xml_product, 'Код');
				if(empty($key))
					continue;

				$find = false;
				if(!empty($my_products))
					foreach ($my_products as $my_product) {
						if($my_product->id_1c == $key)
						{
							$update_product = false;
							if($my_product->article_show != $xml_product->Артикул)
							{
								$this->db->updateRow('s_shopshowcase_products', ['article_show' => $xml_product->Артикул, 'article' => $this->prepareArticleKey($xml_product->Артикул)], $my_product->id);
								$update_product = true;
							}
							if($my_product->name_uk != $xml_product->ЗаголовокТайтл || $my_product->list_uk != $xml_product->Применение || $my_product->text_uk != $xml_product->Описание)
							{
								$this->db->updateRow('wl_ntkd', ['name' => $xml_product->ЗаголовокТайтл, 'list' => $xml_product->Применение, 'text' => $xml_product->Описание], $my_product->name_id_uk);
								$update_product = true;
							}
							if($my_product->name_ru != $xml_product->ЗаголовокТайтлРос || $my_product->list_ru != $xml_product->ПрименениеРос || $my_product->text_ru != $xml_product->ОписаниеРос)
							{
								$this->db->updateRow('wl_ntkd', ['name' => $xml_product->ЗаголовокТайтлРос, 'list' => $xml_product->ПрименениеРос, 'text' => $xml_product->ОписаниеРос], $my_product->name_id_ru);
								$update_product = true;
							}
							if(!empty($xml_product->Авто))
							{
								$xml_car = (string) $xml_product->Авто;
								if($my_product->group_id_1c != $xml_car && isset($this->site_cars[$xml_car]))
								{
									$this->db->updateRow('s_shopshowcase_products', ['group' => $this->site_cars[$xml_car]], $my_product->id);
									$update_product = true;
								}
							}
							if(!empty($xml_product->Производитель))
							{
								$manufacturer = (string) $xml_product->Производитель;
								if(!empty($this->site_manufactures[$manufacturer]))
									if($my_product->manufacturer_id != $this->site_manufactures[$manufacturer])
									{
										$update_product = true;
										if($my_product->row_manufacturer_id)
											$this->db->updateRow('s_shopshowcase_product_options', ['value' => $this->site_manufactures[$manufacturer]], $my_product->row_manufacturer_id);
										else
											$this->db->insertRow('s_shopshowcase_product_options', ['product' => $my_product->id, 'option' => $this->manufacturer_option_id, 'value' => $this->site_manufactures[$manufacturer]]);
										$my_product->manufacturer_id = $this->site_manufactures[$manufacturer];
										$alias = $this->site_manufactures_alias[$my_product->manufacturer_id].'-'.$this->data->latterUAtoEN($xml_product->Артикул);
										if($all)
										{
											if(in_array($alias, $inserted_products_link))
											{
												$i = 2;
												while (in_array($alias.'-'.$i, $inserted_products_link)) {
													$i++;
												}
												$alias = $alias.'-'.$i;
											}
										}
										else
										{
											if($this->db->select('s_shopshowcase_products', 'id', ['alias' => $alias])->get())
											{
												$i = 2;
												while ($this->db->select('s_shopshowcase_products', 'id', ['alias' => $alias.'-'.$i])->get()) {
													$i++;
												}
												$alias = $alias.'-'.$i;
											}
										}
										$this->db->updateRow('s_shopshowcase_products', ['alias' => $alias], $my_product->id);
										$inserted_products_link[] = $alias;
									}
							}
							if(!empty($xml_product->ВложеныеФайлы))
							{
								$ok_images = [];
								foreach ($xml_product->ВложеныеФайлы->ФайлКартинка as $xml_image) {
									$image_id_1c = $this->xml_attribute($xml_image, 'id');
									if(empty($image_id_1c))
										continue;
									$image_path = (string) $xml_image;
									if(empty($image_path))
										continue;
									if(empty($my_product_images[$my_product->id]) || !in_array($image_id_1c, $my_product_images[$my_product->id]))
									{
										if(file_exists('import/photos/'.$image_path))
										{
											if(isset($my_product_images[$my_product->id]))
												$my_product_images[$my_product->id][] = $image_id_1c;
											else
												$my_product_images[$my_product->id] = [$image_id_1c];
											$position = count($my_product_images[$my_product->id]);
											$this->checkImagePath('images/parts/'.$my_product->id);
											$path_to = 'images/parts/'.$my_product->id.'/'.$image_path;
											if(copy('import/photos/'.$image_path, $path_to))
											{
												$this->db->insertRow('wl_images', ['alias' => $this->shop_wl_alias, 
																					'content' => $my_product->id,
																					'file_name' => $image_path,
																					'title' => '',
																					'author' => 0,
																					'date_add' => time(),
																					'position' => $position,
																					'id_1c' => $image_id_1c
																				]);
												$update_product = true;
											}
										}
									}
								}
							}
							if(!empty($xml_product->ТоварныеКатегории))
							{
								$xml_cats = [];
								foreach ($xml_product->ТоварныеКатегории->Категория as $cat) {
									$cat = $this->xml_attribute($cat, 'Код');
									if(!empty($cat))
										if(isset($site_CatUseIn[$cat]))
											$xml_cats[] = $site_CatUseIn[$cat];
								}
								if(!empty($xml_cats))
									foreach ($xml_cats as $cat_id) {
										$find_cat = false;
										if(!empty($my_product_CatUseIn[$my_product->id]))
											if(in_array($cat_id, $my_product_CatUseIn[$my_product->id]))
												$find_cat = true;
										if(!$find_cat)
										{
											$my_product_CatUseIn[$my_product->id][] = $cat_id;
											$this->db->insertRow('s_shopshowcase_product_options', ['product' => $my_product->id, 'option' => $this->category_option_id, 'language' => '', 'value' => $cat_id]);
											$update_product = true;
										}
									}
								if(!empty($my_product_CatUseIn[$my_product->id]))
									foreach ($my_product_CatUseIn[$my_product->id] as $cat_index => $cat_id) {
										if(!in_array($cat_id, $xml_cats))
										{
											$this->db->deleteRow('s_shopshowcase_product_options', ['product' => $my_product->id, 'option' => $this->category_option_id, 'value' => $cat_id]);
											unset($my_product_CatUseIn[$my_product->id][$cat_index]);
											$update_product = true;
										}
									}
							}
							if($update_product)
								$this->load->function_in_alias($this->shop_wl_alias, '__after_edit', $my_product->id, true);
							$find = true;
							break;
						}
					}

				if(!$find)
				{
					$insert['id_1c'] = $key;
					$insert['article_show'] = (string) $xml_product->Артикул;
					$insert['article'] = $this->prepareArticleKey($insert['article_show']);
					$alias = $this->data->latterUAtoEN($insert['article_show']);
					if(!empty($xml_product->Производитель))
					{
						$manufacturer = (string) $xml_product->Производитель;
						if(!empty($this->site_manufactures[$manufacturer]))
						{
							$manufacturer_id = $this->site_manufactures[$manufacturer];
							$alias = $this->site_manufactures_alias[$manufacturer_id].'-'.$alias;
						}
					}
					if($all)
					{
						if(in_array($alias, $inserted_products_link))
						{
							$i = 2;
							while (in_array($alias.'-'.$i, $inserted_products_link)) {
								$i++;
							}
							$alias = $alias.'-'.$i;
						}
					}
					else
					{
						if($this->db->select('s_shopshowcase_products', 'id', ['alias' => $alias])->get())
						{
							$i = 2;
							while ($this->db->select('s_shopshowcase_products', 'id', ['alias' => $alias.'-'.$i])->get()) {
								$i++;
							}
							$alias = $alias.'-'.$i;
						}
					}
					$insert['alias'] = $inserted_products_link[] = $alias;
					
					if(!empty($xml_product->Авто))
					{
						$xml_car = (string) $xml_product->Авто;
						if(isset($this->site_cars[$xml_car]))
						{
							$insert['group'] = $this->site_cars[$xml_car];
							$this->load->function_in_alias($this->shop_wl_alias, '__after_edit', -$insert['group'], true);
						}
					}

					$id = $this->db->insertRow('s_shopshowcase_products', $insert);

					$this->db->insertRow('wl_ntkd', ['alias' => $this->shop_wl_alias, 'content' => $id, 'language' => 'uk', 'name' => $xml_product->ЗаголовокТайтл, 'list' => $xml_product->Применение, 'text' => $xml_product->Описание]);
					$this->db->insertRow('wl_ntkd', ['alias' => $this->shop_wl_alias, 'content' => $id, 'language' => 'ru', 'name' => $xml_product->ЗаголовокТайтлРос, 'list' => $xml_product->ПрименениеРос, 'text' => $xml_product->ОписаниеРос]);

					if(!empty($xml_product->Производитель))
					{
						$manufacturer = (string) $xml_product->Производитель;
						if(!empty($this->site_manufactures[$manufacturer]))
							$this->db->insertRow('s_shopshowcase_product_options', ['product' => $id, 'option' => $this->manufacturer_option_id, 'value' => $this->site_manufactures[$manufacturer]]);
					}
					if(!empty($xml_product->ВложеныеФайлы))
					{
						foreach ($xml_product->ВложеныеФайлы->ФайлКартинка as $xml_image) {
							$image_id_1c = $this->xml_attribute($xml_image, 'id');
							if(empty($image_id_1c))
								continue;
							$image_path = (string) $xml_image;
							if(empty($image_path))
								continue;
							if(file_exists('import/photos/'.$image_path))
							{
								if(isset($my_product_images[$id]))
									$my_product_images[$id][] = $image_id_1c;
								else
									$my_product_images[$id] = [$image_id_1c];
								$position = count($my_product_images[$id]);
								$this->checkImagePath('images/parts/'.$id);
								$path_to = 'images/parts/'.$id.'/'.$image_path;
								if(copy('import/photos/'.$image_path, $path_to))
									$this->db->insertRow('wl_images', ['alias' => $this->shop_wl_alias, 
																		'content' => $id,
																		'file_name' => $image_path,
																		'title' => '',
																		'author' => 0,
																		'date_add' => time(),
																		'position' => $position,
																		'id_1c' => $image_id_1c
																	]);
							}
						}
					}
					if(!empty($xml_product->ТоварныеКатегории))
					{
						foreach ($xml_product->ТоварныеКатегории->Категория as $cat) {
							$cat = $this->xml_attribute($cat, 'Код');
							if(!empty($cat))
								if(isset($site_CatUseIn[$cat]))
									$this->db->insertRow('s_shopshowcase_product_options', ['product' => $id, 'option' => $this->category_option_id, 'language' => '', 'value' => $site_CatUseIn[$cat]]);
						}
					}
				}
			}
		}
	}

	private function parse_VygruzkaKategorij($file)
	{
		$searchKeys = $xml_category = [];

		if(isset($file->Категории->Категория))
			foreach ($file->Категории->Категория as $category) {
				$key = $this->xml_attribute($category, 'Код');
				if(!empty($key))
				{
					$searchKeys[] = $key;
					$xml_category[$key] = ['uk' => $this->xml_attribute($category, 'Наименование'), 'ru' => $this->xml_attribute($category, 'НаименованиеРос')];
				}
			}

		if(!empty($searchKeys))
		{
			$my_category = $this->db->select('s_shopshowcase_options as o', 'id, alias', ['group' => -$this->category_option_id, 'alias' => $searchKeys])
										->join('s_shopshowcase_options_name as uk', 'id as name_id_uk, name as name_uk', ['option' => '#o.id', 'language' => 'uk'])
										->join('s_shopshowcase_options_name as ru', 'id as name_id_ru, name as name_ru', ['option' => '#o.id', 'language' => 'ru'])
										->get('array');

			$last_position = 0;
			foreach ($searchKeys as $key) {
				$find = false;
				if(!empty($my_category))
					foreach ($my_category as $category) {
						if($category->alias == $key)
						{
							if($category->name_uk != $xml_category[$key]['uk'])
							{
								$this->db->updateRow('s_shopshowcase_options_name', ['name' => $xml_category[$key]['uk']], $category->name_id_uk);
								$this->update_VygruzkaNomenklatury++;
							}
							if($category->name_ru != $xml_category[$key]['ru'])
							{
								$this->db->updateRow('s_shopshowcase_options_name', ['name' => $xml_category[$key]['ru']], $category->name_id_ru);
								$this->update_VygruzkaNomenklatury++;
							}
							$find = true;
							break;
						}
					}
				if(!$find)
				{
					if($last_position == 0)
						$last_position = $this->db->getCount('s_shopshowcase_options', -$this->category_option_id, 'group');
					$last_position++;
					$insert = ['wl_alias' => $this->shop_wl_alias, 'group' => -$this->category_option_id, 'alias' => $key, 'position' => $last_position, 'active' => 1];
					$id = $this->db->insertRow('s_shopshowcase_options', $insert);
					$this->db->insertRow('s_shopshowcase_options_name', ['option' => $id, 'language' => 'uk', 'name' => $xml_category[$key]['uk']]);
					$this->db->insertRow('s_shopshowcase_options_name', ['option' => $id, 'language' => 'ru', 'name' => $xml_category[$key]['ru']]);
				}
			}

			if($this->update_VygruzkaNomenklatury > 0)
				$this->db->cache_delete_all(false, 'parts');
		}
	}

	private $update_currency = false;
	private function import_Currency($xml)
	{
		foreach ($xml->Валюта as $currency) {
			$key = $this->xml_attribute($currency, 'Наименование');
			$rate = $this->xml_attribute($currency, 'Курс');
			if($key == 'USD')
			{
				$rate = str_replace(',', '.', $rate);
				if(empty($_SESSION['currency']['USD']) || $_SESSION['currency']['USD'] != $rate)
				{
					$this->update_currency = true;
					$data = [];
					$data['currency'] = $rate;
					$data['day'] = strtotime('today');
					$this->db->updateRow('s_currency', $data, 1);
					// $this->db->updateRow('s_currency', $currency, ['code' => 'USD']);

					$history['currency'] = 1; // ['code' => 'USD']
					$history['value'] = $rate;
					$history['day'] = $data['day'];
					$history['from'] = '1c';
					$history['update'] = time();
					$this->db->insertRow('s_currency_history', $history);

					echo "USD: {$rate} <br>";

					$_SESSION['currency']['USD'] = $rate;
					if(isset($_SESSION['__page_before_init'][9]))
			            $_SESSION['__page_before_init'][9] = 0;
			        $this->db->cache_delete('currency', 'wl_aliases');
			        $this->db->cache_delete_all(false, 'currency');
				}
			}
		}
	}

	private function parse_VygruzkaZalyshkiv($file, $all_products = false)
	{		
		if(isset($file->КурсыВалют->Валюта))
			$this->import_Currency($file->КурсыВалют);

		if(!isset($file->Склади->Склад))
			return false;

		$time = time();
		$all_products = false;
		
		if($all_products)
			$all_products = $this->db->select('s_shopshowcase_products as p', 'id, id_1c, price, currency, availability')->get('array');
		elseif(!empty($file->ОстаткиНоменклатуры))
		{
			$id_1c_list = [];
			foreach ($file->ОстаткиНоменклатуры->Номенклатура as $xml_product) {
				$id_1c = $this->xml_attribute($xml_product, 'Код');
				if(empty($id_1c) || empty($xml_product->Склади) || empty($xml_product->ВидыЦен))
					continue;
				$id_1c_list[] = $id_1c;
			}
			$all_products = $this->db->select('s_shopshowcase_products as p', 'id, id_1c, price, currency, availability', ['id_1c' => $id_1c_list])->get('array');
		}

		if(empty($all_products))
		{
			if(!$this->update_currency)
				if($file_name = $this->data->get('file'))
				{
					if (file_exists($this->folder.$file_name))
						unlink($this->folder.$file_name);
				}
			return false;
		}

		foreach ($file->ОстаткиНоменклатуры->Номенклатура as $xml_product) {
			$id_1c = $this->xml_attribute($xml_product, 'Код');
			if(empty($id_1c) || empty($xml_product->Склади) || empty($xml_product->ВидыЦен))
				continue;

			$price_in = $amount = 0;
			foreach ($xml_product->Склади->Склад as $storage) {
				$amount += $this->xml_attribute($storage, 'Остаток');
			}
			foreach ($xml_product->ВидыЦен->Цена as $price) {
				$key = $this->xml_attribute($price, 'Код');
				if($key == $this->price_id_1c)
				{
					$price_in = $this->xml_attribute($price, 'Цена');
					$price_in = str_replace(',', '.', $price_in);
				}
			}
			
			foreach ($all_products as $site_product) {
				if($site_product->id_1c == $id_1c)
				{
					$update = [];
					if($site_product->price != $price_in)
						$update['price'] = $price_in;
					if($site_product->availability != $amount)
						$update['availability'] = $amount;
					if($site_product->currency != 'USD')
						$update['currency'] = 'USD';
					if(!empty($update))
					{
						$update['date_edit'] = $time;
						$update['author_edit'] = 0;
						$this->db->updateRow('s_shopshowcase_products', $update, $site_product->id);
						// $this->load->function_in_alias($this->shop_wl_alias, '__after_edit', $site_product->id, true);
					}
					break;
				}
			}
		}
	}

	private function xml_attribute($object, $attribute)
	{
	    if(isset($object[$attribute]))
	        return (string) $object[$attribute];
	}

	private function prepareArticleKey($text)
	{
		$text = (string) $text;
		$text = trim($text);
		$text = mb_strtolower($text, "utf-8");
        $ua = array('-', '_', ' ', '`', '~', '!', '@', '#', '$', '%', '^', '&', '"', ',', '\.', '\?', '/', ';', ':', '\'', '[+]', '“', '”');
        $en = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        for ($i = 0; $i < count($ua); $i++) {
            $text = mb_eregi_replace($ua[$i], $en[$i], $text);
        }
        $text = mb_eregi_replace("[-]{2,}", '-', $text);
        return $text;
	}

	private function checkImagePath($path)
	{
		$folders = explode('/', $path);
		$path = '';
		foreach ($folders as $folder) {
			$path .= $folder;
			if(!is_dir($path))
            {
                if(mkdir($path, 0777) == false)
                {
                    $error++;
                    $filejson->files['error'] = 'Error create dir ' . $path;
                } 
            }
            $path .= '/';
		}
	}

}

?>