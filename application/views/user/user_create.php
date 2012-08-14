<h2>Create User</h2>
<div class="message shadow rounded"><?php echo $message; ?></div>
<?php
echo form_open('user/create/');
?>
<label>email</label>
<input type="text" name="email" value="<?php echo set_value('email'); ?>" />
<label>password</label>
<input type="password" name="password" value="<?php echo set_value('password', 'password'); ?>" />
<label>first name</label>
<input type="text" name="first_name" value="<?php echo set_value('first_name'); ?>" />
<label>last name</label>
<input type="text" name="last_name" value="<?php echo set_value('last_name'); ?>" />
<label>phone</label>
<input type="text" name="phone" value="<?php echo set_value('phone'); ?>" />
<!--
<label>admin</label>
<input type="checkbox" name="admin[]" value="admin" <?php echo set_checkbox('admin[]', 'admin'); ?> />
-->
<input type="submit" name="submit" value="Create" />
<?php
echo form_close();
?>