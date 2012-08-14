<div id="info_wrapper">
  <div class="left"><div class="right">
      <div id="map_canvas"></div>
      <p id="address"><?php echo $address; ?></p>
    </div>
    <p><span class="small">last modified <?php echo $date_modified; ?> by <a href="<?php echo site_url('user/read/' . $id_user); ?>"><?php echo $email; ?></a></span><br />
      <?php echo $controls; ?></p>
    
    <label>Program Manager</label>
    <p><?php echo $pm; ?></p>
    <label>Operations Manager</label>
    <p><?php echo $apm; ?></p>
    <label>Account Manager</label>
    <p><?php echo $am; ?></p>
    <label>Status</label>
    <p><?php echo $status; ?></p>
    <label>Event Dates</label>
    <p><?php echo $date_start; ?> - <?php echo $date_end; ?></p>
    <label>Site Visit</label>
    <p><?php echo $site_visit; ?></p>
    <label>Display Date/Time</label>
    <p><?php echo $display_date; ?></p>
    <label>Event Contact</label>
    <p><?php echo $contact_name; ?><br />
      <?php echo $contact_phone; ?><br />
      <?php echo $contact_email; ?> </p>
    <label># of Employees</label>
    <p><?php echo $employees; ?> </p>
    <label>notes</label>
    <p><?php echo $notes; ?></p>
  </div>
</div>
