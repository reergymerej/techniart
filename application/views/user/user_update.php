<h2>Edit User</h2>
<div class="message shadow rounded"><?php echo $message; ?></div>
<?php
echo form_open('user/edit/' . $user['id']);
?>
<label>email</label>
<input type="text" name="email" value="<?php echo $user['email']; ?>" />
<label>password <span class="form_hint">(leave blank to keep current password)</span></label>
<input type="password" name="password" value="" />
<label>first name</label>
<input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" />
<label>last name</label>
<input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" />
<label>phone</label>
<input type="text" name="phone" value="<?php echo $user['phone']; ?>" />
<!--
<label>admin</label>
<input type="checkbox" name="admin[]" value="admin" <?php echo $user['admin'] ? 'checked="checked"' : '';  ?> />
-->
<input type="submit" name="submit" value="Save" />
<input type="submit" name="submit" value="Cancel" />
<?php
echo form_close();
?>