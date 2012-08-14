<h2>Products</h2>
<form id="search" action="product" method="post">
<div id="controls"> <?php echo $controls; ?> </div>
<input type="text" name="filter_criteria" />
<input type="submit" name="submit" value="Search" />
</form>
<div id="list"><?php echo $product_list; ?></div>