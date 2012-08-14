<table class="sortable">
  <tbody>
    <tr>
      <th class="<?php echo table_sort($filter, 'email'); ?>">email</th>
      <th class="<?php echo table_sort($filter, 'first_name'); ?>">first</th>
      <th class="<?php echo table_sort($filter, 'last_name'); ?>">last</th>
      <th class="<?php echo table_sort($filter, 'phone'); ?>">phone</th>
    </tr>
    <?php foreach ($user_list as $user):?>
    <tr>
      <td><a href="<?php echo base_url() . 'index.php/user/read/' . $user['id']; ?>"><?php echo $user['email'];?></a></td>
      <td><?php echo $user['first_name'];?></td>
      <td><?php echo $user['last_name'];?></td>
      <td><?php echo $user['phone'];?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
