<!-- begin container -->
<div class="container">
    <!-- begin row -->
    <div class="row row-space-30">
        <h1><?=html_entity_decode($_SESSION['alias']->name)?></h1>
        <p><?=html_entity_decode($_SESSION['alias']->text)?></p>
    </div>
    <!-- end row -->

    <?php if($groups = $this->load->function_in_alias('blog', '__get_Groups'))
    {
    	echo "<ul>";
    	foreach ($groups as $group) {
            $group->link = SITE_URL.$group->link;
    		echo "<li><a href=\"{$group->link}\">{$group->name}</a></li>";
    	}
    	echo "</ul>";
    } ?>

    <pre>
    	<?php // print_r($group) ?>
    </pre>
</div>