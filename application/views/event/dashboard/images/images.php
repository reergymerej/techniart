<h3>Images</h3>
<p class="images">
<?php 
if( !empty($images) ){
	for($i=0; $i<count($images); $i++): ?>
<a href="<?php echo $images[$i]['name']; ?>" target="_blank">
    <img src="<?php echo $images[$i]['thumb']; ?>" width="100" height="100" />
</a>
<?php endfor; 
}
?>
<br />
<?php echo $controls; ?></p>
