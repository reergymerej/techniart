<h2>Create Product</h2>
<div class="message shadow rounded"><?php echo $message; ?></div>
<?php
echo form_open('product/create/');
?>
<label>type <span class="form_hint">(optional)</span></label>
<input type="text" name="type" value="<?php echo set_value('type'); ?>" />
<label>name</label>
<input type="text" name="name" value="<?php echo set_value('name'); ?>" />
<label>price</label>
<input type="text" name="price" value="<?php echo set_value('price'); ?>" />
<label>model</label>
<input type="text" name="model" value="<?php echo set_value('model'); ?>" />
<input type="submit" name="submit" value="Save" />
<input type="submit" name="submit" value="Cancel" />
</form>