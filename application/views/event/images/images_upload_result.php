<p><a href="<?php echo site_url("event_images/index/{$event['id']}"); ?>">&lt;&lt; back to images</a></p>
<h2>upload result</h2>
<ul>
<?php foreach($result as $r): ?>
	<li><strong><?php echo $r['name']; ?></strong> - <span class="small"><?php echo $r['result']; ?></span></li>
<?php endforeach; ?>
</ul>