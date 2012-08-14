<table class="sortable">
  <tbody>
    <tr>
      <th class="<?php echo table_sort($filter, 'name'); ?>">event name</th>
      <th class="<?php echo table_sort($filter, 'date_start'); ?>">start</th>
      <th class="<?php echo table_sort($filter, 'date_end'); ?>">end</th>
      <th class="not-sortable"><?php echo $search_field_label; ?></th>
    </tr>
    <?php foreach( $event_list as $event ): ?>
    <tr>
      <td><a href="<?php echo site_url(); ?>/event/read/<?php echo $event['id']; ?>"><?php echo $event['name']; ?></a></td>
      <td><?php echo $event['date_start']; ?></td>
      <td><?php echo $event['date_end']; ?></td>
      <td><?php echo $event[$search_field]; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
