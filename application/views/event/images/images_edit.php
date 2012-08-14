<p><a href="<?php echo site_url("event/read/{$event['id']}/#images"); ?>">&lt;&lt; back to event page</a></p>
<h2>images for <?php echo $event['name']; ?></h2>
<h3>Upload New Images</h3>
<p>Upload up to 5 files at a time using the fields below. <span class="small">(accepts jpg, jpeg, png, gif files up to 2MB)</span></p>
<p><?php echo form_open( "event_images/upload/{$event['id']}", array('enctype'=>'multipart/form-data', 'method'=>'post') ); ?>
<input type="file" name="image[]" /> 
<input type="file" name="image[]" /> 
<input type="file" name="image[]" /> 
<input type="file" name="image[]" /> 
<input type="file" name="image[]" /> 
<input type="submit" name="submit" value="Upload" />
</form></p>
<h3>Delete Images</h3>
<p>Click an image below to delete it.</p>
<?php echo form_open( "event_images/delete/{$event['id']}", array('id'=>'delete_form') ); ?>
</form>
<p id="delete"><?php echo $images; ?></p>