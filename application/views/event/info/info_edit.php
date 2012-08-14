<h1><?php echo $name; ?></h1>
<a href="<?php echo site_url("event/read/{$id}/#info"); ?>">&lt;&lt; back to event page</a>
<p>Edit the basic info for this event.</p>
<div class="message rounded shadow"><?php echo $message; ?></div>
<?php echo form_open("event_info/edit/$id"); ?>
<label>Name</label>
<input type="text" name="name" value="<?php echo $name; ?>" />
<label>Program Manager</label>
<input type="text" name="pm" value="<?php echo $pm; ?>" />
<label>Operations Manager</label>
<input type="text" name="apm" value="<?php echo $apm; ?>" />
<label>Account Manager</label>
<input type="text" name="am" value="<?php echo $am; ?>" />
<label>status</label>
<select name="status" class="required">
  <option <?php echo form_option('interested', $status); ?>>interested</option>
  <option <?php echo form_option('tenative', $status); ?>>tentative</option>
  <option <?php echo form_option('confirmed', $status); ?>>confirmed</option>
  <option <?php echo form_option('in progress', $status); ?>>in progress</option>
  <option <?php echo form_option('completed', $status); ?>>completed</option>
</select>
<label>Event Start Date</label>
<input type="text" name="date_start" value="<?php echo $date_start; ?>" class="required" />
<label>Event End Date</label>
<input type="text" name="date_end" value="<?php echo $date_end; ?>" />
<label>Site Visit</label>
<input type="text" name="site_visit" value="<?php echo $site_visit; ?>" />
<label>Display Date/Time</label>
<input type="text" name="display_date" value="<?php echo $display_date; ?>" />
<label>address</label>
<input type="text" name="address" value="<?php echo $address; ?>" />
<label>contact name</label>
<input type="text" name="contact_name" value="<?php echo $contact_name; ?>" />
<label>contact number</label>
<input type="text" name="contact_phone" value="<?php echo $contact_phone; ?>" />
<label>contact email</label>
<input type="text" name="contact_email" value="<?php echo $contact_email; ?>" />
<label># of employees</label>
<input type="text" name="employees" value="<?php echo $employees; ?>" />
<label>notes</label>
<textarea name="notes"><?php echo $notes; ?></textarea>
<input type="submit" name="submit" value="save" />
<input type="submit" name="submit" value="cancel" />
</form>
