<div class="row">
	<div class="col-md-6">
		<div class="row m-b-10">
			<div class="col-md-5 text-right">Id на сайті</div>
		    <div class="col-md-7"> <strong><?=$product->id?></strong> </div>
	    </div>
	    <?php /*
	    <div class="row m-b-10">
			<div class="col-md-5 text-right">Id 1c</div>
		    <div class="col-md-7"> <strong><?=$product->id_1c?></strong> </div>
	    </div> */ ?>
	    <div class="row m-b-10">
			<div class="col-md-5 text-right">Власна адреса посилання</div>
		    <div class="col-md-7"> <a href="<?=SITE_URL.$product->link?>"><?=$url.'/'?><strong><?=$product->alias?></strong></a> </div>
	    </div>

		<?php if($_SESSION['option']->ProductUseArticle) { ?>
			<div class="row m-b-10">
				<div class="col-md-5 text-right">Артикул</div>
			    <div class="col-md-7"> <strong><?=$product->article_show?></strong> </div>
		    </div>
		<?php } if(!$changePriceTab && ($_SESSION['user']->admin || !empty($marketing))) { ?>
			<div class="row m-b-10">
				<div class="col-md-5 text-right">Базова вартість</div>
			    <div class="col-md-7">
			    	<strong><?=$product->price .' '.$product->currency?></strong>
			    	<?php if($product->old_price > $product->price) { ?>
			    		<del title='Стара ціна (до акції)'> <?=$product->old_price .' '.$product->currency?> </del>
			    	<?php } ?>
			    </div>
		    </div>
		    <?php if(!empty($product->markup)) { ?>
		    	<div class="row m-b-10">
					<div class="col-md-5 text-right">Активна націнка</div>
				    <div class="col-md-7"> <strong><?=$product->markup?></strong> </div>
			    </div>
		<?php } } if($_SESSION['option']->useAvailability) { ?>
			<div class="row m-b-10">
				<div class="col-md-5 text-right">Наявність</div>
			    <div class="col-md-7"> <strong> <?=$product->availability_name?></strong> </div>
		    </div>
		<?php }

		if(file_exists(__DIR__ . DIRSEP .'__product_additionall_fields-info.php'))
			require_once '__product_additionall_fields-info.php';

		if($_SESSION['option']->useGroups && $groups && $product->group) {
			function parentsLink(&$parents, $all, $parent, $link)
			{
				if($parent > 0)
				{
					$link = $all[$parent]->alias .'/'.$link;
					$parents[] = $parent;
					if($all[$parent]->parent > 0) $link = parentsLink ($parents, $all, $all[$parent]->parent, $link);
					return $link;
				}
			}
			function makeLink($all, $parent, $link)
			{
				if($parent > 0)
				{
					$link = $all[$parent]->alias .'/'.$link;
					if($all[$parent]->parent > 0) $link = parentsLink ($parents, $all, $all[$parent]->parent, $link);
				}
				return $link;
			} ?>

			<div class="row m-b-10">
				<div class="col-md-5 text-right">Група/и</div>
			    <div class="col-md-7">
			<?php if($_SESSION['option']->ProductMultiGroup) {
				foreach ($product->group as $g) {
					if (empty($list[$g]))
						continue;
					$g = $list[$g];

					reset($_SESSION['alias']->breadcrumb);
					$link = SITE_URL.'admin/'.$_SESSION['alias']->alias;
					$name = key($_SESSION['alias']->breadcrumb);
	            	echo "<a href=\"{$link}\" target=\"_blank\">{$name}</a>/";
	            	if($g->parent > 0) {
	            		$parents = array();
	            		$g->link = SITE_URL.'admin/'.$_SESSION['alias']->alias .'/'. parentsLink($parents, $list, $g->parent, $g->alias);
	            		if($parents)
	            		{
	            			krsort ($parents);
	            			foreach ($parents as $parent) {
	            				$link = SITE_URL.'admin/'.$_SESSION['alias']->alias .'/'. makeLink($list, $list[$parent]->parent, $list[$parent]->alias);
	            				echo "<a href=\"{$link}\" target=\"_blank\">{$list[$parent]->name}</a>/";
	            			}
	            		}
	            	}
	            	else
	            		$g->link = SITE_URL.'admin/'.$_SESSION['alias']->alias .'/'. $g->alias;
	            	echo "<strong><a href=\"{$g->link}\" target=\"_blank\"><strong>{$g->name}</strong></a></strong>";
	            }
			}
			else if(!empty($product->parents))
					foreach ($product->parents as $parent) {
						$link = SITE_URL.'admin/'.$_SESSION['alias']->alias .'/'. makeLink($list, $parent->parent, $parent->alias);
						if($product->group != $parent->id)
							echo "<a href=\"{$link}\" target=\"_blank\">{$parent->name}</a>/";
						else
							echo "<a href=\"{$link}\" target=\"_blank\"><strong>{$parent->name}</strong></a>";
					}
			echo "</div></div>"; 
		} ?>
			<div class="row m-b-10">
				<div class="col-md-5 text-right">Стан</div>
			    <div class="col-md-7"> <strong> <?=($product->active == 1)?'Товар активний':'Товар тимчасово відключено'?> </strong> </div>
		    </div>
	</div>
	<div class="col-md-6">
		<?php if($product->options)
			foreach ($product->options as $option) if(!empty($option->value)) { ?>
			<div class="row m-b-10">
				<div class="col-md-5 text-right"><?=$option->name?></div>
			    <div class="col-md-7">
			    	<?php if(!empty($option->photo) && file_exists(substr($option->photo, strlen(SITE_URL)))) echo "<img src='{$option->photo}' style='width:30px'>"; ?>
			    	<?php if(is_array($option->value)) {
			    		$names = [];
			    		foreach ($option->value as $value) {
			    			$names[] =$value->name;
			    		}
			    		$names = implode(', ', $names);
			    		echo "<strong> {$names} </strong>";
			    	} else echo "<strong> {$option->value} </strong>"; ?>
			    	<?=$option->sufix?>
			    </div>
		    </div>
		<?php } ?>
	</div>
</div>
<?php if(!empty($marketing))
		foreach($marketing as $tab) {
			if($tab->key == 'price_per_type')
				continue;
			echo '<div class="row">';
			echo "<h3>{$tab->name}</h3>";
			echo $tab->content;
			echo "</div>";
		}
?>


<script type="text/javascript">
	function showUninstalForm () {
		if($('#uninstall-form').is(":hidden")){
			$('#uninstall-form').slideDown("slow");
		} else {
			$('#uninstall-form').slideUp("fast");
		}
	}
</script>