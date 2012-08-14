<h2>User Profile</h2>
<div class="message shadow rounded"><?php echo $message; ?></div>
<?php
echo form_open('user/edit/' . $user['id']);
?>
<label>email</label>
<input type="text" name="email" value="<?php echo $user['email']; ?>" disabled="disabled" />
<label>first name</label>
<input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" disabled="disabled" />
<label>last name</label>
<input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" disabled="disabled" />
<label>phone</label>
<input type="text" name="phone" value="<?php echo $user['phone']; ?>" disabled="disabled" />
<!--
<label>admin</label>
<input type="checkbox" name="admin[]" value="admin" <?php echo $user['admin'] ? 'checked="checked"' : '';  ?>  disabled="disabled" />
-->
<?php echo $controls; ?>
<?php
echo form_close();
?>