<?php $_SESSION['alias']->js_load[] = 'assets/switchery/switchery.min.js'; ?>
<link rel="stylesheet" href="<?=SITE_URL?>assets/switchery/switchery.min.css" />

<?php $productOrder = false;
if(isset($_SESSION['option']->productOrder) && empty($_GET['sort']))
{
	$_SESSION['option']->productOrder = trim($_SESSION['option']->productOrder);
	$order = explode(' ', $_SESSION['option']->productOrder);
	if((count($order) == 2 && $order[0] == 'position' && in_array($order[1], array('asc', 'ASC', 'desc', 'DESC'))) || (count($order) == 1 && $order[0] == 'position'))
		$productOrder = true;
}
?>
<div class="table-responsive">
    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
        <thead>
            <tr>
            	<?php if(!isset($search) && $productOrder) echo "<th></th>"; ?>
				<th><?=($_SESSION['option']->ProductUseArticle) ? 'Артикул /' : ''?> Назва</th>
				<th><div class="btn-group">
					<?php $sort = array('' => 'Ціна авто', 'price_down' => 'Від дешевих до дорогих ↑', 'price_up' => 'Від дорогих до дешевих ↓'); ?>
					<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
						<?=(isset($_GET['sort']) && isset($sort[$_GET['sort']])) ? $sort[$_GET['sort']] : 'Ціна'?> <?=(!empty($_SESSION['currency'])) ? '' : '(y.o.)'?> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<?php foreach ($sort as $key => $value) { ?>
							<li><a href="<?=$this->data->get_link('sort', $key)?>"><?=$value?></a></li>
						<?php } ?>
					</ul>
				</div></th>
				<?php if($_SESSION['option']->useAvailability == 1) { 
					$this->db->select($this->shop_model->table('_availability').' as a');
					$name = array('availability' => '#a.id');
					if($_SESSION['language']) $name['language'] = $_SESSION['language'];
					$this->db->join($this->shop_model->table('_availability_name'), 'name', $name);
					$availability = $this->db->get();
					?>
					<th>Наявність</th>
				<?php } if($_SESSION['option']->useGroups == 1 && $_SESSION['option']->ProductMultiGroup == 1) { ?>
					<th>Групи</th>
				<?php } ?>
				<th>Автор / Редаговано</th>
				<?php if(!isset($search)) { ?>
					<th><div class="btn-group">
						<?php $sort = array('' => 'Авто', 'active_on' => 'Активні згори ↑', 'active_off' => 'Активні знизу ↓'); ?>
						<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
							<?=(isset($_GET['sort']) && isset($sort[$_GET['sort']])) ? $sort[$_GET['sort']] : 'Стан'?> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<?php foreach ($sort as $key => $value) { ?>
								<li><a href="<?=$this->data->get_link('sort', $key)?>"><?=$value?></a></li>
							<?php } ?>
						</ul>
					</div></th>
				<?php } ?>
            </tr>
        </thead>
        <tbody>
        	<?php foreach($products as $a) { ?>
				<tr id="<?=($_SESSION['option']->ProductMultiGroup && isset($a->position_id)) ? $a->position_id : $a->id?>">
					<?php if(!isset($search) && $productOrder) { ?>
						<td class="move sortablehandle"><i class="fa fa-sort"></i></td>
					<?php } ?>
					<td>
						<?php if(!empty($a->admin_photo)) {?>
						<a href="<?=SITE_URL.'admin/'.$a->link?>"><img src="<?= IMG_PATH.$a->admin_photo?>" width="90" class="pull-left p-r-10" alt=""></a>
						<?php } ?>
						<a href="<?=SITE_URL.'admin/'.$a->link?>">
							<?=($_SESSION['option']->ProductUseArticle) ? '<strong>'.mb_strtoupper($a->article_show).'</strong>' : ''?> 
							<?=empty($a->name)?$a->id : $a->name?></a>

						<div class="dropdown pull-right">
						  <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						    Дія <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu">
						    <li><a href="<?=SITE_URL.'admin/'.$a->link?>?edit"><i class="fa fa-pencil"></i> Редагувати</a></li>
						    <li><a href="<?=SITE_URL.$a->link?>"><i class="fa fa-eye"></i> Дивитися на сайті</a></li>
						    <li role="separator" class="divider"></li>
						    <li><a href="#deleteProduct" class="text-danger" data-toggle="modal" data-pid="<?=$a->id?>" data-name="<?=$a->name?>"><i class="fa fa-trash"></i> Видалити</a></li>
						  </ul>
						</div>
					</td>
					<td>
						<?=$a->price?> <?=(!empty($a->currency)) ? $a->currency : 'y.o.'?>
						<?php if($a->old_price) {
							echo "<br><del title='Стара ціна'>{$a->old_price} ";
							echo (!empty($a->currency)) ? $a->currency : 'y.o.';
							echo "</del>";
						} ?>
					</td>
					<?php if($_SESSION['option']->useAvailability == 1) { ?>
						<td>
							<select onchange="changeAvailability(this, <?=$a->id?>)" class="form-control">
								<?php if(!empty($availability)) foreach ($availability as $c) {
									echo('<option value="'.$c->id.'"');
									if($c->id == $a->availability) echo(' selected');
									echo('>'.$c->name.'</option>');
								} ?>
							</select>
						</td>
					<?php }
					if($_SESSION['option']->useGroups == 1 && $_SESSION['option']->ProductMultiGroup == 1) {
						echo("<td>");
						$active = 0;
						if(!empty($a->group) && is_array($a->group)) {
							$allG = count($a->group); $iG = 0;
                            foreach ($a->group as $g) {
                                echo('<a href="'.SITE_URL.'admin/'.$g->link.'">'.$g->name.'</a>');
                                if(++$iG < $allG)
                                	echo ", ";
                                if($g->active)
                                    $active++;
                            }
                        }
                         else {
                            echo("Не визначено");
                        }
                        echo("</td>");
                    	}
                    ?>
					<td><?=$a->author_edit ? '<a href="'.SITE_URL.'admin/wl_users/'.$a->author_edit.'">'.$a->user_name.'</a> <br>' : ''?> <?=date("d.m.Y H:i", $a->date_edit)?></td>
					
					<?php if(!isset($search)) {
						if($productOrder || isset($_GET['sort'])) { ?>
							<td>
								<input type="checkbox" data-render="switchery" <?=($a->active == 1) ? 'checked' : ''?> value="1" onchange="changeActive(this, <?=$a->id?>, <?=(isset($group)) ? $group->id : 0 ?>)" />
							</td>
						<?php } else {
							$color = 'success';
	                        $color_text = 'активний';
							if($_SESSION['option']->useGroups && $_SESSION['option']->ProductMultiGroup && !empty($a->group) && is_array($a->group))
	                        {
	                            if($active == 0)
	                            {
	                                $color = 'danger';
	                                $color_text = 'відключено';
	                            }
	                            elseif($active < count($a->group))
	                            {
	                                $color = 'warning';
	                                $color_text = 'частково активний';
	                            }
	                        }
						?>
							<td class="<?=$color?>"><?=$color_text?></td>
					<?php } } ?>
				</tr>
			<?php } ?>
        </tbody>
    </table>
</div>
<div class="pull-right">Товарів у групі: <strong><?=$_SESSION['option']->paginator_total?></strong>. <?php if(!isset($search)){?>Активних товарів: <strong><?=$_SESSION['option']->paginator_total_active?></strong><?php } ?></div>
<?php
$this->load->library('paginator');
echo $this->paginator->get();
?>

<div class="modal fade" tabindex="-1" role="dialog" id="deleteProduct">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title text-danger">Видалити <strong></strong>?</h4>
      		</div>
      		<div class="modal-body text-danger">
	        	<i class="fa fa-trash fa-2x pull-left"></i>
	          	<p>Ви впевнені що бажаєте видалити <?=$_SESSION['admin_options']['word:product']?>?</p>
      		</div>
      		<div class="modal-footer">
      			<form action="<?=SITE_URL.'admin/'.$_SESSION['alias']->alias?>/delete" method="POST">
		      		<input type="hidden" name="id" value="0">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Скасувати</button>
			        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Видалити</button>
        		</form>
      		</div>
		</div>
  	</div>
</div>

<?php $_SESSION['alias']->js_load[] = "js/{$_SESSION['alias']->alias}/admin-products-list.js"; ?>