<table class="calendar">
  <tbody>
    <tr>
      <th>Sun</th>
      <th>Mon</th>
      <th>Tue</th>
      <th>Wed</th>
      <th>Thu</th>
      <th>Fri</th>
      <th>Sat</th>
    </tr>
    <?php 
		while( count($days) > 0): ?>
    <tr>
      <?php for($i=0; $i<7; $i++): 
	  	$day = array_shift($days);
	  ?>
      <td class="rounded <?php echo $day['event_day'];?>"><span class="date"><?php echo $day['pretty_date']; ?> </span>
        <p> <a href="<?php echo site_url("event_day/view/{$event_id}/{$day['date']}"); ?>"><?php echo $day['link_text']; ?></a> </p></td>
      <?php endfor; ?>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
