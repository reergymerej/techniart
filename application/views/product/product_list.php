<table class="sortable">
  <tbody>
    <tr>
      <th class="<?php echo table_sort($filter, 'name'); ?>">product name</th>
      <th class="<?php echo table_sort($filter, 'price'); ?>">price</th>
      <th class="<?php echo table_sort($filter, 'model'); ?>">model</th>
      <th class="<?php echo table_sort($filter, 'type'); ?>">type</th>
    </tr>
    <?php foreach( $product_list as $p ): ?>
    <tr>
      <td><a href="<?php echo site_url(); ?>/product/read/<?php echo $p['id']; ?>"><?php echo $p['name']; ?></a></td>
      <td><?php echo $p['price']; ?></td>
      <td><?php echo $p['model']; ?></td>
      <td><?php echo $p['type']; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
