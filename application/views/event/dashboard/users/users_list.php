<p>last modified <?php echo $modified_date; ?> by <a href="<?php echo site_url("user/read/{$modified_user['id']}"); ?>"><?php echo $modified_user['email']; ?></a></p>
<ul>
  <?php foreach( $users as $u ): ?>
  <li><a href="<?php echo site_url( 'user/read/' . $u['id'] ); ?>"><?php echo $u['email']; ?></a></li>
  <?php endforeach; ?>
</ul>