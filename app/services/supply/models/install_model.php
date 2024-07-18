<?php

class install {

	public $service = null;
	
	public $name = "supply";
	public $title = "Постачальники аналіз";
	public $description = "";
	public $group = "shop";
	public $table_service = "supply";
	public $table_alias = "";
	public $multi_alias = 0;
	public $order_alias = 15;
	public $admin_ico = 'fa-area-chart';
	public $version = "1.0";

	public $options = array('deviation_max_price' => '10');
	public $options_type = array('deviation_max_price' => 'number');
	public $options_title = array('deviation_max_price' => 'Відхилення від максимальної ціни, %');
	public $options_admin = array();
	public $sub_menu = array();
	public $sub_menu_access = array();

	public function alias($alias = 0, $table = '')
	{
		if($alias == 0) return false;

		return true;
	}

	public function alias_delete($alias = 0, $table = '', $uninstall_service = false)
	{
		return true;
	}

	public function setOption($option, $value, $table = '')
	{
		return true;
	}

	public function install_go()
	{
		$query = "CREATE TABLE IF NOT EXISTS `supply_storages` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `provider` varchar(50) NOT NULL,
                    `name` varchar(255) NOT NULL,
                    `link` varchar(255) NOT NULL,
                    `active` tinyint(1) NOT NULL,
                    `last_import_at` int(11) NOT NULL,
                    `import_flag` tinyint(1) NOT NULL COMMENT 'flag (1|0) to import on active stage',
                    `created_at` int(11) NOT NULL,
                    PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";
		$this->db->executeQuery($query);

        $query = "CREATE TABLE IF NOT EXISTS `supply_import_log` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `storage_id` int(11) NOT NULL,
                    `created_at` int(11) NOT NULL,
					`link` varchar(255) NOT NULL,
					`local_file` varchar(255) NOT NULL,
                    PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";
        $this->db->executeQuery($query);

        $query = "CREATE TABLE IF NOT EXISTS `supply_products` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `import_id` int(11) NOT NULL,
                    `article_key` varchar(100) NOT NULL,
                    `product_article` varchar(255) NOT NULL,
                    `product_brand` varchar(255) NOT NULL,
                    `price` float NOT NULL,
                    `availability` int(11) NOT NULL,
                    `product_title` varchar(255) NOT NULL,
                    PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";
        $this->db->executeQuery($query);

		$query = "ALTER TABLE `supply_products` ADD INDEX(`article_key`);";
        $this->db->executeQuery($query);

        $query = "CREATE TABLE `supply_minus_words` (
					`id` INT NOT NULL AUTO_INCREMENT,
					`word` VARCHAR(255) NOT NULL,
					PRIMARY KEY (`id`)
					) ENGINE = MyISAM;";
        $this->db->executeQuery($query);

		$query = "ALTER TABLE `supply_minus_words` ADD INDEX(`word`);";
        $this->db->executeQuery($query);

		$query = "CREATE TABLE `supply_minus_brands` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`brand` VARCHAR(255) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE = MyISAM;";
		$this->db->executeQuery($query);

		$query = "ALTER TABLE `supply_minus_brands` ADD INDEX(`brand`);";
		$this->db->executeQuery($query);

		$query = "CREATE TABLE `supply_minus_products` ( `product_id` INT NOT NULL , PRIMARY KEY (`product_id`)) ENGINE = MyISAM;";
		$this->db->executeQuery($query);

		return true;
	}

	public function uninstall($service_id)
	{
		$this->db->executeQuery("DROP TABLE IF EXISTS `supply_storages`");
		$this->db->executeQuery("DROP TABLE IF EXISTS `supply_import_log`");
		$this->db->executeQuery("DROP TABLE IF EXISTS `supply_products`");
		$this->db->executeQuery("DROP TABLE IF EXISTS `supply_minus_words`");
		$this->db->executeQuery("DROP TABLE IF EXISTS `supply_minus_brands`");
		$this->db->executeQuery("DROP TABLE IF EXISTS `supply_minus_products`");

		return true;
	}
	
}