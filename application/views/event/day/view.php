<p><a href="<?php echo site_url("event/read/$event_id/#calendar"); ?>">&lt;&lt; back to event</a></p>
<p>submitted <?php echo $created; ?> by <a href=""><?php echo $user['email']; ?></a>.</p>
<table>
  <tr>
    <th>type</th>
    <th>name</th>
    <th>model</th>
    <th>price</th>
    <th># sold</th>
  </tr>
  <?php foreach($products as $p): ?>
  <tr>
    <td><?php echo $p['type']; ?></td>
    <td><?php echo $p['name']; ?></td>
    <td><?php echo $p['model']; ?></td>
    <td><?php echo $p['price']; ?></td>
    <td><?php echo $p['count']; ?></td>
  </tr>
  <?php endforeach; ?>
</table>