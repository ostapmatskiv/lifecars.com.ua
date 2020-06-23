<?php

/**
 * for adatrade.com.ua
 */
class import_1c extends Controller
{

	private $folder = 'import/';
	private $shop_wl_alias = 8;
	private $manufacturer_option_id = 1;
	private $category_option_id = 2;
	private $site_manufactures = [];
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
					$file_name = explode('_', $file_name);
					$all = end($file_name);
					$all = strtolower($all);
					$all = $all == 'all.xml' ? true : false;
					if($file_name[0] == 'VygruzkaNomenklatury')
						$this->parse_VygruzkaNomenklatury($file, $all);
					else if($file_name[0] == 'VygruzkaKategorij')
						$this->parse_VygruzkaKategorij($file);
					else if($file_name[0] == 'VygruzkaZalyshkiv')
						$this->parse_VygruzkaZalyshkiv($file);
				}
			}
			else
				echo $file_name." not found";
		}
		else
			echo "param file required!";
	}

	private function parse_VygruzkaNomenklatury($file, $all)
	{
		// echo "<pre>";
		// print_r($file);
		if(!empty($file->Производители))
			$this->parse_manufacturers($file->Производители);

		if(!empty($file->Марки) && !empty($file->Автомобили))
			$this->parse_groups($file->Марки, $file->Автомобили);

		if(!empty($file->Товары) && !empty($file->Товары))
			$this->parse_products($file->Товары, $all);

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
										->join('s_shopshowcase_options_name', 'id as name_id, name', ['option' => '#o.id'])
										->get('array');

			$last_position = 0;
			foreach ($searchKeys as $key) {
				$find = false;
				if(!empty($my_manufactures))
					foreach ($my_manufactures as $my_manufacturer) {
						if($my_manufacturer->alias == $key)
						{
							if(empty($this->site_manufactures[$key]))
								$this->site_manufactures[$key] = $my_manufacturer->id;
							if($my_manufacturer->name != $xml_manufacturers[$key]['uk'])
								$this->db->updateRow('s_shopshowcase_options_name', ['name' => $xml_manufacturers[$key]['uk']], $my_manufacturer->name_id);
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
					$this->db->insertRow('s_shopshowcase_options_name', ['option' => $id, 'name' => $xml_manufacturers[$key]['uk']]);
				}
			}
		}
	}

	private function parse_groups($manufacturers, $cars)
	{
		// print_r($manufacturers);
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
										->join('wl_ntkd', 'id as name_id, name', ['alias' => $this->shop_wl_alias, 'content' => '#-g.id'])
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
							if($my_manufacturer->name != $xml_manufacturers[$key]['uk'])
								$this->db->updateRow('wl_ntkd', ['name' => $xml_manufacturers[$key]['uk']], $my_manufacturer->name_id);
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
					$this->db->insertRow('wl_ntkd', ['alias' => $this->shop_wl_alias, 'content' => -$id, 'name' => $xml_manufacturers[$key]['uk']]);
				}
			}
		}

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
										->join('wl_ntkd', 'id as name_id, name', ['alias' => $this->shop_wl_alias, 'content' => '#-g.id'])
										->get('array');

			$last_position = 0;
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
								$this->db->updateRow('s_shopshowcase_groups', ['parent' => $xml_cars[$key]['parent']], $my_car->id);
							if($my_car->name != $xml_cars[$key]['uk'])
								$this->db->updateRow('wl_ntkd', ['name' => $xml_cars[$key]['uk']], $my_car->name_id);
							$find = true;
							break;
						}
					}
				if(!$find)
				{
					if($last_position == 0)
						$last_position = $this->db->getCount('s_shopshowcase_groups', ['wl_alias' => $this->shop_wl_alias, 'parent' => $parent]);
					$last_position++;
					$insert['id_1c'] = $key;
					$insert['alias'] = $this->data->latterUAtoEN($xml_cars[$key]['uk']);
					$insert['parent'] = $parent;
					$insert['position'] = $last_position;
					$id = $this->db->insertRow('s_shopshowcase_groups', $insert);
					$this->site_cars[$key] = $id;
					$this->db->insertRow('wl_ntkd', ['alias' => $this->shop_wl_alias, 'content' => -$id, 'name' => $xml_cars[$key]['uk']]);
				}
			}
		}
	}

	private function parse_products($file_products, $all)
	{
		// $this->db->shopDBdump = true;
		// print_r($file_products);
		$searchKeys = $my_product_cars = $my_product_analogs = $my_analog_groups = [];
		if(isset($file_products->Номенклатура) && !$all)
			foreach ($file_products->Номенклатура as $product) {
				$key = $this->xml_attribute($product, 'Код');
				if(!empty($key))
					$searchKeys[] = $key;
			}

		if(!empty($searchKeys) || $all)
		{
			$where = ['wl_alias' => $this->shop_wl_alias];
			if(!$all)
				$where['id_1c'] = $searchKeys;
			$my_products = $this->db->select('s_shopshowcase_products as p', 'id, id_1c, article_show, group', $where)
										->join('wl_ntkd', 'id as name_id, name, text', ['alias' => $this->shop_wl_alias, 'content' => '#p.id'])
										->join('s_shopshowcase_product_options', 'id as row_manufacturer_id, value as manufacturer_id', ['option' => $this->manufacturer_option_id, 'product' => '#p.id'])
										->get('array');
			if(!empty($my_products))
			{
				if($all)
				{
					if($s_shopshowcase_product_group = $this->db->select('s_shopshowcase_product_group', 'product, `group`')->get('array'))
						foreach ($s_shopshowcase_product_group as $pg) {
							if(isset($my_product_cars[$pg->product]))
								$my_product_cars[$pg->product][] = $pg->group;
							else
								$my_product_cars[$pg->product] = [$pg->group];
						}

					if($s_shopshowcase_products_similar = $this->db->select('s_shopshowcase_products_similar as s', '`group`')
																	->join('s_shopshowcase_products as p', 'id, id_1c', '#s.product')
																	->get('array'))
						foreach ($s_shopshowcase_products_similar as $product) {
							if(!isset($my_analog_groups[$product->group]))
							{
								$my_analog_groups[$product->group] = [];
								$my_analog_groups[$product->group]['id_1c'] = [];
							}

							$my_analog_groups[$product->group]['id_1c'][] = $product->id_1c;
							$my_analog_groups[$product->group][$product->id_1c] = $product->id;
							$my_product_analogs[$product->id] = $product->group;
						}
				}
				else
				{
					$ids = [];
					foreach ($my_products as $my_product) {
						$ids[] = $my_product->id;
					}
					if($s_shopshowcase_product_group = $this->db->select('s_shopshowcase_product_group', 'product, `group`', ['product' => $ids])->get('array'))
						foreach ($s_shopshowcase_product_group as $pg) {
							if(isset($my_product_cars[$pg->product]))
								$my_product_cars[$pg->product][] = $pg->group;
							else
								$my_product_cars[$pg->product] = [$pg->group];
						}
					if($s_shopshowcase_products_similar = $this->db->getAllDataByFieldInArray('s_shopshowcase_products_similar', ['product' => $ids]))
					{
						$similar_groups = [];
						foreach ($s_shopshowcase_products_similar as $similar) {
							if(!in_array($similar->group, $similar_groups))
								$similar_groups[] = $similar->group;
						}
						$s_shopshowcase_products_similar = $this->db->select('s_shopshowcase_products_similar as s', '`group`', ['group' => $similar_groups])
																	->join('s_shopshowcase_products as p', 'id, id_1c', '#s.product')
																	->get('array');
						foreach ($s_shopshowcase_products_similar as $product) {
							if(!isset($my_analog_groups[$product->group]))
							{
								$my_analog_groups[$product->group] = [];
								$my_analog_groups[$product->group]['id_1c'] = [];
							}

							$my_analog_groups[$product->group]['id_1c'][] = $product->id_1c;
							$my_analog_groups[$product->group][$product->id_1c] = $product->id;
							$my_product_analogs[$product->id] = $product->group;
						}
					}
				}
			}
			
			$last_position = 0;
			$insert = ['wl_alias' => $this->shop_wl_alias, 'active' => 1, 'price' => 0, 'old_price' => 0, 'promo' => 0, 'currency' => '', 'group' => 0, 'availability' => 1, 'author_add' => 0, 'author_edit' => 0];
			$insert['date_add'] = $insert['date_edit'] = time();

			foreach ($file_products->Номенклатура as $xml_product) {
				$key = $this->xml_attribute($xml_product, 'Код');
				if(empty($key))
					continue;

				$find = false;
				if(!empty($my_products))
					foreach ($my_products as $my_product) {
						if($my_product->id_1c == $key)
						{
							if($my_product->article_show != $xml_product->Артикул)
								$this->db->updateRow('s_shopshowcase_products', ['article_show' => $xml_product->Артикул, 'article' => $this->prepareArticleKey($xml_product->Артикул)], $my_product->id);
							if($my_product->name != $xml_product->ЗаголовокТайтл || $my_product->text != $xml_product->Описание)
								$this->db->updateRow('wl_ntkd', ['name' => $xml_product->ЗаголовокТайтл, 'text' => $xml_product->Описание], $my_product->name_id);
							if(!empty($xml_product->Авто))
							{
								$xml_cars = [];
								foreach ($xml_product->Авто->Авто as $car) {
									$car = (string) $car;
									if(!empty($car))
										if(isset($this->site_cars[$car]))
											$xml_cars[] = $this->site_cars[$car];
								}
								if(!empty($xml_cars))
									foreach ($xml_cars as $xml_car) {
										$find_car = false;
										if(!empty($my_product_cars[$my_product->id]))
											if(in_array($xml_car, $my_product_cars[$my_product->id]))
												$find_car = true;
										if(!$find_car)
										{
											$my_product_cars[$my_product->id][] = $xml_car;
											$this->db->insertRow('s_shopshowcase_product_group', ['product' => $my_product->id, 'group' => $xml_car, 'position' => count($my_product_cars[$my_product->id]), 'active' => 1]);
										}
									}
								if(!empty($my_product_cars[$my_product->id]))
									foreach ($my_product_cars[$my_product->id] as $car_index => $car_id) {
										if(!in_array($car_id, $xml_cars))
										{
											$this->db->deleteRow('s_shopshowcase_product_group', ['product' => $my_product->id, 'group' => $car_id]);
											unset($my_product_cars[$my_product->id][$car_index]);
										}
									}
							}
							if(!empty($xml_product->Производитель))
							{
								$manufacturer = (string) $xml_product->Производитель;
								if(!empty($this->site_manufactures[$manufacturer]))
									if($my_product->manufacturer_id != $this->site_manufactures[$manufacturer])
									{
										if($my_product->row_manufacturer_id)
											$this->db->updateRow('s_shopshowcase_product_options', ['value' => $this->site_manufactures[$manufacturer]], $my_product->row_manufacturer_id);
										else
											$this->db->insertRow('s_shopshowcase_product_options', ['product' => $my_product->id, 'option' => $this->manufacturer_option_id, 'value' => $this->site_manufactures[$manufacturer]]);
										$my_product->manufacturer_id = $this->site_manufactures[$manufacturer];
									}
							}
							if(!empty($xml_product->Аналоги))
							{
								$xml_analogs = $find_add_id_1c_analogs = [];
								$ok_analogs = [$my_product->id_1c];
								foreach ($xml_product->Аналоги->Аналог_Номенклатура_Код as $analog) {
									$analog = (string) $analog;
									if(!empty($analog))
										$xml_analogs[] = $analog;
								}
								if(isset($my_product_analogs[$my_product->id]))
								{
									$analog_group = $my_product_analogs[$my_product->id];
									if(!empty($xml_analogs) && isset($my_analog_groups[$analog_group]))
									{
										foreach ($xml_analogs as $xml_analog_id_1c) {
											if(in_array($xml_analog_id_1c, $my_analog_groups[$analog_group]['id_1c']))
												$ok_analogs[] = $xml_analog_id_1c;
											else
												$find_add_id_1c_analogs[] = $xml_analog_id_1c;
										}
										if(!empty($find_add_id_1c_analogs))
										{
											if($analogs_site = $this->db->select('s_shopshowcase_products as p', 'id, id_1c', ['id_1c' => $find_add_id_1c_analogs])
																->join('s_shopshowcase_products_similar as s', 'id as similar_id, `group`', ['product' => '#p.id'])
																->get('array'))
												foreach ($analogs_site as $analog) {
													if($analog->similar_id)
													{
														if($analog->group != $analog_group)
															$this->db->updateRow('s_shopshowcase_products_similar', ['group' => $analog_group], $analog->similar_id);
													}
													else
														$this->db->insertRow('s_shopshowcase_products_similar', ['product' => $analog->id, 'group' => $analog_group]);
													$my_analog_groups[$analog_group]['id_1c'][] = $analog->id_1c;
													$ok_analogs[] = $analog->id_1c;
													$my_analog_groups[$analog_group][$analog->id_1c] = $analog->id;
													$my_product_analogs[$analog->id] = $analog_group;
												}
										}
										foreach ($my_analog_groups[$analog_group]['id_1c'] as $i => $id_1c) {
											if(!in_array($id_1c, $ok_analogs))
											{
												if(isset($my_analog_groups[$analog_group][$id_1c]))
												{
													$product_id = $my_analog_groups[$analog_group][$id_1c];
													$this->db->deleteRow('s_shopshowcase_products_similar', ['product' => $product_id]);
													unset($my_analog_groups[$analog_group]['id_1c'][$i], $my_analog_groups[$analog_group][$id_1c], $my_product_analogs[$product_id]);
												}
											}
										}
									}
									else
									{
										foreach ($my_analog_groups[$analog_group]['id_1c'] as $i => $id_1c) {
											if($id_1c == $my_product->id_1c)
											{
												$this->db->deleteRow('s_shopshowcase_products_similar', ['product' => $my_product->id]);
												unset($my_analog_groups[$analog_group]['id_1c'][$i], $my_analog_groups[$analog_group][$id_1c], $my_product_analogs[$my_product->id]);
												break;
											}
										}
									}
								}
								else if(!empty($xml_analogs))
								{
									if($analogs_site = $this->db->select('s_shopshowcase_products as p', 'id, id_1c', ['id_1c' => $xml_analogs])
																->join('s_shopshowcase_products_similar as s', 'id as similar_id, `group`', ['product' => '#p.id'])
																->get('array'))
									{
										$find_analog = false;
										foreach ($analogs_site as $analog) {
											$analog_group = $my_product_analogs[$analog->id] = $analog->group;
											if(isset($my_analog_groups[$analog_group]))
											{
												$this->db->insertRow('s_shopshowcase_products_similar', ['product' => $my_product->id, 'group' => $analog->group]);
												$my_analog_groups[$analog_group]['id_1c'][] = $my_product->id_1c;
												$ok_analogs[] = $my_product->id_1c;
												$my_analog_groups[$analog_group][$my_product->id_1c] = $my_product->id;
												$my_product_analogs[$my_product->id] = $analog_group;

												foreach ($xml_analogs as $xml_analog_id_1c) {
													if(in_array($xml_analog_id_1c, $my_analog_groups[$analog_group]['id_1c']))
													{
														$ok_analogs[] = $xml_analog_id_1c;
													}
													else
													{
														foreach ($analogs_site as $analog_analoga) {
															if($analog_analoga->id_1c == $xml_analog_id_1c)
															{
																if($analog_analoga->similar_id)
																{
																	if($analog->group != $analog_analoga->group)
																		$this->db->updateRow('s_shopshowcase_products_similar', ['group' => $analog->group], $analog_analoga->similar_id);
																}
																else
																	$this->db->insertRow('s_shopshowcase_products_similar', ['product' => $analog_analoga->id, 'group' => $analog->group]);
																$my_analog_groups[$analog_group]['id_1c'][] = $analog_analoga->id_1c;
																$ok_analogs[] = $analog_analoga->id_1c;
																$my_analog_groups[$analog_group][$analog_analoga->id_1c] = $analog_analoga->id;
																$my_product_analogs[$analog_analoga->id] = $analog_group;
																break;
															}
														}
													}
												}
												foreach ($my_analog_groups[$analog_group]['id_1c'] as $i => $id_1c) {
													if(!in_array($id_1c, $ok_analogs))
													{
														if(isset($my_analog_groups[$analog_group][$id_1c]))
														{
															$product_id = $my_analog_groups[$analog_group][$id_1c];
															$this->db->deleteRow('s_shopshowcase_products_similar', ['product' => $product_id]);
															unset($my_analog_groups[$analog_group]['id_1c'][$i], $my_analog_groups[$analog_group][$id_1c], $my_product_analogs[$product_id]);
														}
													}
												}

												$find_analog = true;
												break;
											}
										}
										if(!$find_analog)
										{
											$analog_group = 1;
							        		if($next = $this->db->getQuery('SELECT MAX(`group`) as nextGroup FROM `s_shopshowcase_products_similar`'))
							        			$analog_group = $next->nextGroup + 1;

											$my_analog_groups[$analog_group] = [];
											$my_analog_groups[$analog_group]['id_1c'] = [$my_product->id_1c];
											$my_analog_groups[$analog_group][$my_product->id_1c] = $my_product->id;
											$my_product_analogs[$my_product->id] = $analog_group;

											$this->db->insertRow('s_shopshowcase_products_similar', ['product' => $my_product->id, 'group' => $analog_group]);
											foreach ($analogs_site as $analog) {
												if(empty($analog->group))
													$this->db->insertRow('s_shopshowcase_products_similar', ['product' => $analog->id, 'group' => $analog_group]);
												else if($analog->group != $analog_group)
													$this->db->updateRow('s_shopshowcase_products_similar', ['group' => $analog_group], $analog->similar_id);
												$my_analog_groups[$analog_group]['id_1c'][] = $analog->id_1c;
												$my_analog_groups[$analog_group][$analog->id_1c] = $analog->id;
												$my_product_analogs[$analog->id] = $analog_group;
											}
										}
									}
								}
							}
							$find = true;
							break;
						}
					}
// echo "<pre>";
// print_r($my_analog_groups);
// print_r($my_product_analogs);
// exit;
				if(!$find)
				{
					$insert['article_show'] = (string) $xml_product->Артикул;
					$insert['article'] = $this->prepareArticleKey($insert['article_show']);
					$insert['id_1c'] = $key;
					$insert['alias'] = $this->data->latterUAtoEN($insert['article_show']);
					$id = $this->db->insertRow('s_shopshowcase_products', $insert);
					$this->db->insertRow('wl_ntkd', ['alias' => $this->shop_wl_alias, 'content' => $id, 'name' => $xml_product->ЗаголовокТайтл, 'text' => $xml_product->Описание]);

					$position = 1;
					if(!empty($xml_product->Авто))
						foreach ($xml_product->Авто->Авто as $car) {
									$car = (string) $car;
									if(!empty($car))
										if(isset($this->site_cars[$car]))
									$this->db->insertRow('s_shopshowcase_product_group', ['product' => $id, 'group' => $car, 'position' => $position++, 'active' => 1]);
						}
					if(!empty($xml_product->Производитель))
					{
						$manufacturer = (string) $xml_product->Производитель;
						if(!empty($this->site_manufactures[$manufacturer]))
							$this->db->insertRow('s_shopshowcase_product_options', ['product' => $id, 'option' => $this->manufacturer_option_id, 'value' => $this->site_manufactures[$manufacturer]]);
					}
					if(!empty($xml_product->Аналоги))
					{
						$xml_analogs = $list_to_update_MyProductAnalogs = [];
						$ok_analogs = [$key];
						foreach ($xml_product->Аналоги->Аналог_Номенклатура_Код as $analog) {
							$analog = (string) $analog;
							if(!empty($analog))
								$xml_analogs[] = $analog;
						}

						if(!empty($xml_analogs))
						{
							if($analogs_site = $this->db->select('s_shopshowcase_products as p', 'id, id_1c', ['id_1c' => $xml_analogs])
																->join('s_shopshowcase_products_similar as s', 'id as similar_id, `group`', ['product' => '#p.id'])
																->get('array'))
							{
								$find_analog = false;
								foreach ($analogs_site as $analog) {
									$analog_group = $my_product_analogs[$analog->id] = $analog->group;
									if(isset($my_analog_groups[$analog_group]))
									{
										$this->db->insertRow('s_shopshowcase_products_similar', ['product' => $id, 'group' => $analog->group]);
										$my_analog_groups[$analog_group]['id_1c'][] = $key;
										$ok_analogs[] = $key;
										$my_analog_groups[$analog_group][$key] = $id;
										$my_product_analogs[$id] = $analog_group;

										foreach ($xml_analogs as $xml_analog_id_1c) {
											if(in_array($xml_analog_id_1c, $my_analog_groups[$analog_group]['id_1c']))
											{
												$ok_analogs[] = $xml_analog_id_1c;
											}
											else
											{
												foreach ($analogs_site as $analog_analoga) {
													if($analog_analoga->id_1c == $xml_analog_id_1c)
													{
														if($analog_analoga->similar_id)
														{
															if($analog->group != $analog_analoga->group)
																$this->db->updateRow('s_shopshowcase_products_similar', ['group' => $analog->group], $analog_analoga->similar_id);
														}
														else
															$this->db->insertRow('s_shopshowcase_products_similar', ['product' => $analog_analoga->id, 'group' => $analog->group]);
														$my_analog_groups[$analog_group]['id_1c'][] = $analog_analoga->id_1c;
														$ok_analogs[] = $analog_analoga->id_1c;
														$my_analog_groups[$analog_group][$analog_analoga->id_1c] = $analog_analoga->id;
														$my_product_analogs[$analog_analoga->id] = $analog_group;
														break;
													}
												}
											}
										}
										foreach ($my_analog_groups[$analog_group]['id_1c'] as $i => $id_1c) {
											if(!in_array($id_1c, $ok_analogs))
											{
												if(isset($my_analog_groups[$analog_group][$id_1c]))
												{
													$product_id = $my_analog_groups[$analog_group][$id_1c];
													$this->db->deleteRow('s_shopshowcase_products_similar', ['product' => $product_id]);
													unset($my_analog_groups[$analog_group]['id_1c'][$i], $my_analog_groups[$analog_group][$id_1c], $my_product_analogs[$product_id]);
												}
											}
										}

										$find_analog = true;
										break;
									}
								}
								if(!$find_analog)
								{
									$analog_group = 1;
					        		if($next = $this->db->getQuery('SELECT MAX(`group`) as nextGroup FROM `s_shopshowcase_products_similar`'))
					        			$analog_group = $next->nextGroup + 1;

									$my_analog_groups[$analog_group] = [];
									$my_analog_groups[$analog_group]['id_1c'] = [$key];
									$my_analog_groups[$analog_group][$key] = $id;
									$my_product_analogs[$id] = $analog_group;

									$this->db->insertRow('s_shopshowcase_products_similar', ['product' => $id, 'group' => $analog_group]);
									foreach ($analogs_site as $analog) {
										if(empty($analog->group))
											$this->db->insertRow('s_shopshowcase_products_similar', ['product' => $analog->id, 'group' => $analog_group]);
										else if($analog->group != $analog_group)
											$this->db->updateRow('s_shopshowcase_products_similar', ['group' => $analog_group], $analog->similar_id);
										$my_analog_groups[$analog_group]['id_1c'][] = $analog->id_1c;
										$my_analog_groups[$analog_group][$analog->id_1c] = $analog->id;
										$my_product_analogs[$analog->id] = $analog_group;
									}
								}
							}
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
										->join('s_shopshowcase_options_name', 'id as name_id, name', ['option' => '#o.id'])
										->get('array');

			$last_position = 0;
			foreach ($searchKeys as $key) {
				$find = false;
				if(!empty($my_category))
					foreach ($my_category as $category) {
						if($category->alias == $key)
						{
							if($category->name != $xml_category[$key]['uk'])
								$this->db->updateRow('s_shopshowcase_options_name', ['name' => $xml_category[$key]['uk']], $category->name_id);
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
					$this->db->insertRow('s_shopshowcase_options_name', ['option' => $id, 'name' => $xml_category[$key]['uk']]);
				}
			}
		}
	}

	private function parse_VygruzkaZalyshkiv($file)
	{
		// echo "<pre>";
		
		if(!isset($file->Склади->Склад))
			return false;

		$site_storage = [];
		$s_shopstorage = $this->db->select('s_shopstorage', 'id, id_1c', ['active' => 1])->get('array');
		if($s_shopstorage)
			foreach ($s_shopstorage as $storage) {
				if(!empty($storage->id_1c))
					$site_storage[$storage->id_1c] = $storage->id;
			}
		unset($s_shopstorage);

		foreach ($file->Склади->Склад as $storage) {
			$key = $this->xml_attribute($storage, 'Код');
			if(!empty($key))
				if(!isset($site_storage[$key]))
				{
					$name = $this->xml_attribute($storage, 'Наименование');
					if(empty($name))
						continue;

					$insert = [];
					$insert['wl_aliases'] = $this->data->latterUAtoEN($name);
					$insert['service'] = 3; // id service shopstorages
					$insert['table'] = '';
					$insert['seo_robot'] = $insert['admin_sidebar'] = 0;
					$insert['admin_ico'] = 'fa-qrcode';
					$insert['admin_order'] = 90;
					$site_storage[$key] = $this->db->insertRow('wl_aliases', $insert);

					$insert = [];
					$insert['id'] = $site_storage[$key];
					$insert['id_1c'] = $key;
					$insert['name'] = $name;
					$insert['currency'] = 'USD';
					$insert['updateRows'] = $insert['updateCols'] = $insert['markup'] = NULL;
					$insert['date_add'] = time();
					$insert['user_add'] = $insert['active'] = 0;
					$this->db->insertRow('s_shopstorage', $insert);
				}
		}

		$time = time();
		$all_products = false;
		$insert_to_storage = [];
		$update_in_storage = [];
		foreach ($site_storage as $site_storage_id_1с => $site_storage_id) {
			$s_shopstorage_products = $this->db->select('s_shopstorage_products as s', 'id as storage_row_id, price_in, amount', ['storage' => $site_storage_id])
												->join('s_shopshowcase_products as p', 'id, id_1c', '#s.product')
												->get('array');

			if(!empty($s_shopstorage_products))
			{
				foreach ($file->ОстаткиНоменклатуры->Номенклатура as $xml_product) {
					$id_1c = $this->xml_attribute($xml_product, 'Код');
					if(empty($id_1c) || empty($xml_product->Склади) || empty($xml_product->ВидыЦен))
						continue;
					$price_in = $amount = 0;
					foreach ($xml_product->Склади->Склад as $storage) {
						$key = $this->xml_attribute($storage, 'Код');
						if($key == $site_storage_id_1с)
							$amount = $this->xml_attribute($storage, 'Остаток');
					}
					foreach ($xml_product->ВидыЦен->Цена as $price) {
						$key = $this->xml_attribute($price, 'Код');
						if($key == $site_storage_id_1с)
						{
							$price_in = $this->xml_attribute($price, 'Цена');
							$price_in = str_replace(',', '.', $price_in);
						}
					}
					$find = false;
					foreach ($s_shopstorage_products as $site_product) {
						if($site_product->id_1c == $id_1c)
						{
							$find = true;
							$update = [];
							if($site_product->price_in != $price_in)
								$update['price_in'] = $price_in;
							if($site_product->amount != $amount)
								$update['amount'] = $amount;
							if(!empty($update))
							{
								$update['date_in'] = $update['date_edit'] = $time;
								$update['manager_edit'] = 0;
								$this->db->updateRow('s_shopstorage_products', $update, $site_product->storage_row_id);
								$update_in_storage++;
							}

							// find storage dublicates
							foreach ($s_shopstorage_products as $sp) {
								if($site_product->id == $sp->id && $site_product->storage_row_id != $sp->storage_row_id)
									$this->db->deleteRow('s_shopstorage_products', $sp->storage_row_id);
							}
							break;
						}
					}
					if(!$find && $amount > 0 && $price_in > 0)
						if($site_product = $this->db->select('s_shopshowcase_products as p', 'id', ['id_1c' => $id_1c])->get())
						{
							$insert = [];
							$insert['storage'] = $site_storage_id;
							$insert['product'] = $site_product->id;
							$insert['price_in'] = $price_in;
							$insert['price_out'] = $insert['amount_reserved'] = $insert['date_out'] = $insert['manager_add'] = $insert['manager_edit'] = 0;
							$insert['amount'] = $amount;
							$insert['date_in'] = $insert['date_add'] = $insert['date_edit'] = $time;
							$insert_to_storage[] = $insert;
						}
				}
			}
			else
			{
				if(!$all_products)
					$all_products = $this->db->select('s_shopshowcase_products as p', 'id, id_1c')->get('array');
				if(!empty($all_products))
				{
					foreach ($file->ОстаткиНоменклатуры->Номенклатура as $xml_product) {
						$id_1c = $this->xml_attribute($xml_product, 'Код');
						if(empty($id_1c) || empty($xml_product->Склади) || empty($xml_product->ВидыЦен))
							continue;
						$price_in = $amount = 0;
						foreach ($xml_product->Склади->Склад as $storage) {
							$key = $this->xml_attribute($storage, 'Код');
							if($key == $site_storage_id_1с)
								$amount = $this->xml_attribute($storage, 'Остаток');
						}
						if($amount > 0)
							foreach ($xml_product->ВидыЦен->Цена as $price) {
								$key = $this->xml_attribute($price, 'Код');
								if($key == $site_storage_id_1с)
								{
									$price_in = $this->xml_attribute($price, 'Цена');
									$price_in = str_replace(',', '.', $price_in);
								}
							}
						if($amount > 0 && $price_in > 0)
							foreach ($all_products as $site_product) {
								if($site_product->id_1c == $id_1c)
								{
									$insert = [];
									$insert['storage'] = $site_storage_id;
									$insert['product'] = $site_product->id;
									$insert['price_in'] = $price_in;
									$insert['price_out'] = $insert['amount_reserved'] = $insert['date_out'] = $insert['manager_add'] = $insert['manager_edit'] = 0;
									$insert['amount'] = $amount;
									$insert['date_in'] = $insert['date_add'] = $insert['date_edit'] = $time;
									$insert_to_storage[] = $insert;
									break;
								}
							}
					}
				}
			}
		}
		if(!empty($insert_to_storage))
		{
			$keys = ['storage', 'product', 'price_in', 'price_out', 'amount', 'amount_reserved', 'date_in', 'date_out', 'manager_add', 'date_add', 'manager_edit', 'date_edit'];
			$this->db->insertRows('s_shopstorage_products', $keys, $insert_to_storage);
		}
		echo "inserted ".count($insert_to_storage).', updated: '.count($update_in_storage);
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

}

?>