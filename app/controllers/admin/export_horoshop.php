<?php

class export_horoshop_admin extends Controller
{

	function index () {
		ini_set('max_execution_time', 1800);
		ini_set('max_input_time', 1800);
		ini_set('memory_limit', '1024M');

		$products = $this->db->select('s_shopshowcase_products as p', 'id, article_show, group, price, currency', ['wl_alias' => 8, 'active' => 1])
								->join('wl_ntkd as n_uk', 'name as name_uk, list as text_uk', ['alias' => '#p.wl_alias', 'content' => '#p.id', 'language' => 'uk'])
								->join('wl_ntkd as n_ru', 'name as name_ru, list as text_ru', ['alias' => '#p.wl_alias', 'content' => '#p.id', 'language' => 'ru'])
								->join('wl_images as i', 'file_name', ['alias' => '#p.wl_alias', 'content' => '#p.id', 'position' => 1])
								->join('s_shopshowcase_product_options as m', 'value as manufacturer_id', ['option' => 1, 'product' => '#p.id'])
								->join('s_shopshowcase_options_name as m_n', 'name as manufacturer_name', ['option' => '#m.value', 'language' => 'uk'])
								->get('array');

		$parts = $this->db->select('s_shopshowcase_product_options as part', 'product, value as id', ['option' => 2])
								->join('s_shopshowcase_options_name as part_n', 'name', ['option' => '#part.value', 'language' => 'uk'])
								->get('array');

		$currency = [];
		foreach ($this->db->select('s_currency', 'code, currency')->get('array') as $row) {
			$currency[$row->code] = $row->currency;
		}

		$groups = $this->db->select('s_shopshowcase_groups as g', 'id, parent')
							->join('wl_ntkd as n_uk', 'name', ['alias' => '#g.wl_alias', 'content' => '#-g.id', 'language' => 'uk'])
							->get('arrayIndexed');

		$this->generate_xlsx($products, $currency, $groups, $parts);
	}

	private function generate_xlsx ($products, $currency, $groups, $parts) {
		$cols = [
			'Артикул' => 'id',
			'Артикул для отображения на сайте' => 'article_show',
			'Название (UA)' => 'name_uk',
			'Название (RU)' => 'name_ru',
			'Бренд' => 'manufacturer_name',
			'Раздел' => 'group_name',
			'Цена' => 'price',
			'Валюта' => '=UAH',
			'Отображать' => '=Да',
			'Наличие' => '=В наявності',
			'Фото' => 'file_name',
			'Описание товара (UA)' => 'text_uk',
			'Описание товара (RU)' => 'text_ru',
			'Категорії' => 'parts_name'
		];

		$a = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$this->load->library('PHPExcel');

		// Set document properties
		$this->phpexcel->getProperties()
						->setCreator(SITE_NAME)
						->setLastModifiedBy(SITE_NAME)
						->setTitle("Products " . SITE_NAME);

		$this->phpexcel->setActiveSheetIndex(0);
		$this->phpexcel->getActiveSheet()->setTitle('Products');

		$x = 0; $y = 1;
		foreach ($cols as $col_title => $col_key) {
			$xy = $a[$x++] . $y;
			$this->phpexcel->getActiveSheet()->setCellValue($xy, $col_title);
		}

		$groups_name = [];

		foreach ($products as $product) {
			$product->price = ceil($product->price * $currency[$product->currency]);
			$product->file_name = empty($product->file_name) ? '' : IMG_PATH . "parts/{$product->id}/life_{$product->file_name}";
			if(isset($groups_name[$product->group])) {
				$product->group_name = $groups_name[$product->group];
			}
			else {
				$n = [];
				$parent = $product->group;
				while ($parent) {
					$n[] = $groups[$parent]->name;
					$parent = $groups[$parent]->parent;
				}
				$product->group_name = $groups_name[$product->group] = implode('/', array_reverse($n));
			}
			$product->parts_name = [];
			foreach ($parts as $_p) {
				if($_p->product == $product->id) {
					$product->parts_name[] = $_p->name;
				}
			}
			$product->parts_name = implode('/', $product->parts_name);
			$product->text_uk = "{$product->name_uk} застосовується у автомобілях {$product->text_uk}";
			$product->text_ru = "{$product->name_ru} применяется в автомобилях {$product->text_ru}";

			$y++;
			$x = 0;
			foreach ($cols as $col_title => $col_key) {
				$xy = $a[$x++] . $y;
				if(mb_substr($col_key, 0, 1) == '=') {
					$this->phpexcel->getActiveSheet()->setCellValue($xy, mb_substr($col_key, 1));
				}
				elseif (isset($product->$col_key)) {
					$this->phpexcel->getActiveSheet()->setCellValueExplicit($xy, $product->{$col_key}, PHPExcel_Cell_DataType::TYPE_STRING);
				}
			}
		}

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->phpexcel->setActiveSheetIndex(0);

		header('Cache-Control: max-age=0');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$date = date('dmY');
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . SITE_NAME . '-Products-' . $date . '.xlsx"');

		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

}