<h1><?php echo $name; ?></h1>
<p>This will remove all data associated with <?php echo $name; ?> (images, daily sales, etc.). <strong>This cannot be undone.</strong></p>
<p><a href="<?php echo site_url("event/read/$id#summary"); ?>">cancel</a><a href="<?php echo site_url("event/delete/$id/1"); ?>" class="buttonLink">OK, delete it.</a></p>
