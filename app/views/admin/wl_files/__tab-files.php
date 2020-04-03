<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<form method="POST" action="<?=SITE_URL?>admin/wl_files/save" enctype="multipart/form-data" class="form-horizontal" onsubmit="saveing.style.display = block">
			<input type="hidden" name="alias" value="<?=$_SESSION['alias']->id?>">
			<input type="hidden" name="alias_folder" value="<?=$_SESSION['option']->folder?>">
			<input type="hidden" name="content" value="<?=$_SESSION['alias']->content?>">
			<div class="form-group">
		        <label>Виберіть файл/и: (pdf, doc, docx, xls, xlsx, ppt, pptx, mp4)</label>
		        <input type="file" name="file[]" class="form-control" multiple required>
			</div>
			<div class="form-group">
				<div class="center">
					<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Додати</button>
				</div>
			</div>
		</form>
	</div>
</div>

<ol id="sortable-files">
<?php
	$files = $this->db->getAllDataByFieldInArray('wl_files', array('alias' => $_SESSION['alias']->id, 'content' => $_SESSION['alias']->content), 'position');
	if($files)
		foreach ($files as $file) {
			echo("<li class=\"ui-state-default\" id=\"file-{$file->id}\"> "); ?>
			<a href="<?= $this->data->get_file_path($file)?>" target="_blank">
				<?php switch ($file->extension) {
					case 'pdf':
						echo '<i class="fa fa-file-pdf-o fa-2x" title="Дивитися"></i>';
						break;
					case 'doc':
					case 'docx':
						echo '<i class="fa fa-file-word-o fa-2x" title="Дивитися"></i>';
						break;
					case 'xls':
					case 'xlsx':
						echo '<i class="fa fa-file-excel-o fa-2x" title="Дивитися"></i>';
						break;
					case 'ppt':
					case 'pptx':
						echo '<i class="fa fa-file-excel-o fa-2x" title="Дивитися"></i>';
						break;
					case 'mp4':
						echo '<i class="fa fa-film fa-2x" title="Дивитися"></i>';
						break;
					
					default:
						echo '<i class="fa fa-file" aria-hidden="true" title="Дивитися"></i>';
						break;
				} ?>
			</a>
			<input type="text" id="text-file-<?=$file->id?>" value="<?=$file->text?>" class="form-control" onChange="saveFileText(<?=$file->id?>, this)" data-filename="<?=$file->name?>">
			<span>Додано: <?=date('d.m.Y H:i', $file->date_add)?></span>
			<?php echo(" <a class='btn btn-danger btn-xs' href=\"".SITE_URL."admin/wl_files/delete?id={$file->id}&folder={$_SESSION['option']->folder}\" onclick=\"return confirm('Видалити {$file->name}?')\" style='float: right !important; color: #fff'><i class=\"fa fa-trash\" aria-hidden=\"true\"></i> Видалити</a></li>");
		}
?>
</ol>

<style>
	.ui-state-default {
		margin: 2px;
		padding: 5px;
		font-size: 15px;
		height: 45px;
    	clear: both;
	}
	.ui-state-default > * {
		float: left !important;
	}
	.ui-state-default input {
		width: 50%;
	    margin: 0 10px;
	}
</style>

<script>
document.onreadystatechange = function () {
 	if (document.readyState == "complete") {
 		$( "#sortable-files" ).sortable({
			update: function( event, ui ) {
				$('#saveing-files').css("display", "block");
		        $.ajax({
		            url: "<?=SITE_URL?>admin/wl_files/change_position",
		            type: 'POST',
		            data: {
						alias: <?=$_SESSION['alias']->id?>,
						content: <?=$_SESSION['alias']->content?>,
		                id: ui.item.attr('id'),
		                position: ui.item.index(),
		                json: true
		            },
		            success: function(res){
		                if(res['result'] == false)
			                $.gritter.add({title:"Помилка!",text:res['error']});
			            else
			            	$.gritter.add({title:'Файли', text:"Порядок змінено"});
		                $('#saveing').css("display", "none");
		            },
		            error: function(){
		                alert("Помилка! Спробуйте ще раз!");
		                $('#saveing').css("display", "none");
		            },
		            timeout: function(){
		                alert("Помилка: Вийшов час очікування! Спробуйте ще раз!");
		                $('#saveing').css("display", "none");
		            }
		        });
			}
		});
		$( "#sortable" ).disableSelection();
   	}
 }
 function saveFileText(id, e){
    $('#saveing').css("display", "block");
    $.ajax({
        url: "<?=SITE_URL?>admin/wl_files/save_text",
        type: 'POST',
        data: {
            id: id,
            text: e.value,
            json: true
        },
        success: function(res){
            if(res['result'] == false)
                $.gritter.add({title:"Помилка!",text:res['error']});
            else
            	$.gritter.add({title:e.dataset.filename, text:"Дані успішно збережено!"});
            $('#saveing').css("display", "none");
        },
        error: function(){
            alert("Помилка! Спробуйте ще раз!");
            $('#saveing').css("display", "none");
        },
        timeout: function(){
            alert("Помилка: Вийшов час очікування! Спробуйте ще раз!");
            $('#saveing').css("display", "none");
        }
    });
}
</script>