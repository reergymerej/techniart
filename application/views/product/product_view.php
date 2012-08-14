<h2>View Product</h2>
<div class="message shadow rounded"><?php echo $message; ?></div>
<?php
echo form_open('product/edit/' . $product['id']);
?>
<label>type <span class="form_hint">(optional)</span></label>
<input type="text" name="type" value="<?php echo $product['type']; ?>" disabled="disabled" />
<label>name</label>
<input type="text" name="name" value="<?php echo $product['name']; ?>" disabled="disabled" />
<label>price</label>
<input type="text" name="price" value="<?php echo $product['price']; ?>" disabled="disabled" />
<label>model</label>
<input type="text" name="model" value="<?php echo $product['model']; ?>" disabled="disabled" />
<?php echo $controls; ?>
</form>