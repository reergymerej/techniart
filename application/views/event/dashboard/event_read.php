<h1><?php echo $event_name; ?></h1>
<h2>Planning</h2>
<div class="collapsible planning">
  <p>This section is used to prepare for the event.  These values are only editable for admins and users assigned to this event.</p>
  <a href="#" name="info"></a>
  <h3>Event Info</h3>
  <?php echo $info; ?> <a href="#" name="users"></a> 
  <!--
  <h3>Users</h3>
  <?php echo $usersContent; ?> <a href="#" name="products"></a>
  <h3>Products</h3>
  <?php echo $products; ?> 
-->
</div>
<!--  
<h2>Z</h2>
<div class="collapsible execution">
  <p>This section allows the users to submit the daily information.  All users can view this data, but only admins and users assigned to this event can submit.</p>
  <a href="#" name="calendar"></a>
  <h3>Calendar</h3>
  <?php echo $calendar; ?>
--> 
<a href="#" name="images"></a> <?php echo $images; ?>
<h2>Summary</h2>
<div class="collapsible summary">
  <p>This contains the final info for the event.</p>
  <a href="#" name="summary"></a> <?php echo $summary; ?>
  <div id="event_controls"><?php echo $eventControls; ?></div>
</div>
</div>
