<p>last modified <?php echo $modified_date; ?> by <a href="<?php echo site_url("user/read/{$modified_user['id']}"); ?>"><?php echo $modified_user['email']; ?></a></p>
<ul>
  <?php foreach( $products as $p ): ?>
  <li>(<?php echo $p['count_start']; ?>) - <a href="<?php echo site_url( 'product/read/' . $p['id'] ); ?>"><?php echo $p['name']; ?></a></li>
  <?php endforeach; ?>
</ul>
