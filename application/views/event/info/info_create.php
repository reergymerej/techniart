<h1>New Event</h1>
<p>To get started, please provide some basic info about the event.  These values can be changed later as needed.</p>
<div class="message rounded shadow"><?php echo $message; ?></div>
<?php echo form_open('event_info/create/'); ?>
<label>Name</label>
<input type="text" name="name" value="<?php echo set_value('name', $event['name']); ?>" class="required" />
<label>Program Manager</label>
<input type="text" name="pm" value="<?php echo set_value('pm', $event['pm']); ?>" />
<label>Operations Manager</label>
<input type="text" name="apm" value="<?php echo set_value('apm', $event['apm']); ?>" />
<label>Account Manager</label>
<input type="text" name="am" value="<?php echo set_value('am', $event['am']); ?>" />
<label>status</label>
<select name="status" class="required">
  <option value="interested" selected="selected">interested</option>
  <option value="tenative">tentative</option>
  <option value="confirmed">confirmed</option>
  <option value="in progress">in progress</option>
  <option value="completed">completed</option>
</select>
<label>Event Start Date</label>
<input type="text" name="date_start" value="<?php echo set_value('date_start', $event['date_start']); ?>" class="required" />
<label>Event End Date</label>
<input type="text" name="date_end" value="<?php echo set_value('date_end', $event['date_end']); ?>" />
<label>Site Visit</label>
<input type="text" name="site_visit" value="<?php echo set_value('site_visit', $event['site_visit']); ?>" />
<label>Display Date/Time</label>
<input type="text" name="display_date" value="<?php echo set_value('display_date', $event['display_date']); ?>" />
<label>address</label>
<input type="text" name="address" value="<?php echo set_value('address', $event['address']); ?>" />
<label>contact name</label>
<input type="text" name="contact_name" value="<?php echo set_value('contact_name', $event['contact_name']); ?>" />
<label>contact number</label>
<input type="text" name="contact_phone" value="<?php echo set_value('contact_phone', $event['contact_phone']); ?>" />
<label>contact email</label>
<input type="text" name="contact_email" value="<?php echo set_value('contact_email', $event['contact_email']); ?>" />
<label># of employees</label>
<input type="text" name="employees" value="<?php echo set_value('employees', $event['employees']); ?>" />
<label>notes</label>
<textarea name="notes"><?php echo set_value('notes', $event['notes']); ?></textarea>
<input type="submit" name="submit" value="save" />
<input type="submit" name="submit" value="cancel" />
</form>
