<h2>Edit Product</h2>
<div class="message shadow rounded"><?php echo $message; ?></div>
<?php
echo form_open('product/edit/' . $product['id']);
?>
<label>type <span class="form_hint">(optional)</span></label>
<input type="text" name="type" value="<?php echo $product['type']; ?>" />
<label>name</label>
<input type="text" name="name" value="<?php echo $product['name']; ?>" />
<label>price</label>
<input type="text" name="price" value="<?php echo $product['price']; ?>" />
<label>model</label>
<input type="text" name="model" value="<?php echo $product['model']; ?>" />
<input type="submit" name="submit" value="Save" />
<input type="submit" name="submit" value="Cancel" />
</form>