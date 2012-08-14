<?php echo form_open("event_day/save/$event_id/$date", array('id'=>'save')); ?>
<table>
  <tr>
    <th>type</th>
    <th>name</th>
    <th>model</th>
    <th>price</th>
    <th># sold</th>
  </tr>
  <?php foreach($products as $p): ?>
  <tr>
    <td><?php echo $p['type']; ?></td>
    <td><?php echo $p['name']; ?></td>
    <td><?php echo $p['model']; ?></td>
    <td><?php echo $p['price']; ?></td>
    <td><input type="text" name="product[<?php echo $p['id']; ?>]" value="0" /></td>
  </tr>
  <?php endforeach; ?>
</table>
<input type="submit" value="Submit" />
</form>
