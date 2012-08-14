<h1><?php echo $name; ?></h1>
<p>Select which users to associate with this event.  Hold <strong>ctrl</strong> to select more than one.</p>
<?php echo form_open('event_users/edit/' . $id); ?>
<label>users</label>
<select name="users[]" multiple="multiple">
  <?php foreach($users as $u): ?>
  <option value="<?php echo $u['id']; ?>" <?php echo $u['selected']; ?>><?php echo $u['email']; ?></option>
  <?php endforeach; ?>
</select>
<input type="submit" name="submit" value="Save" />
<input type="submit" name="submit" value="Cancel" />
</form>
