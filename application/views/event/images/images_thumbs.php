<?php 
if( !empty($images) ){
	for($i=0; $i<count($images); $i++): ?>
<a href="<?php echo $images[$i]['name']; ?>" target="_blank" name="<?php echo $images[$i]['id']; ?>">
    <img src="<?php echo $images[$i]['thumb']; ?>" width="100" height="100" />
</a>
<?php endfor; 
}
?>