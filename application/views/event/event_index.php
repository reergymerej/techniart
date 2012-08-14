<h2>Events</h2>
<div id="controls"> <?php echo $controls; ?> </div>
<form id="search" action="event" method="post">
  <table class="compact">
    <tr>
      <td><select name="filter_field", id="filter_field">
          <option value="name">Name</option>
          <option value="status">Status</option>
          <option value="date_start">Start Date</option>
          <option value="date_end">End Date</option>
          <option value="site_visit">Site Visit</option>
          <option value="display_date">Display Date/Time</option>
          <option value="address">Address</option>
          <option value="contact_name">Contact Name</option>
          <option value="contact_phone">Contact Phone</option>
          <option value="contact_email">Contact Email</option>
          <option value="notes">Notes</option>
          <option value="employees"># of Employees</option>
          <option value="pm">Project Manager</option>
          <option value="apm">Operations Manager</option>
          <option value="am">Account Manager</option>
        </select></td>
      <td><input type="text" name="filter_criteria" /></td>
      <td><input type="submit" name="submit" value="Search" /></td>
    </tr>
  </table>
</form>
<div id="list"><?php echo $event_list; ?></div>
