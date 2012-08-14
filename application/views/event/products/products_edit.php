<h1><?php echo $event['name']; ?></h1>
<a href="<?php echo site_url("event/read/{$event['id']}/#products"); ?>">&lt;&lt; back to event page</a>
<p>Pick which products will be sold during this show.</p>
<h2>products</h2>
<?php echo form_open('event_products/edit/' . $event['id']); ?>
<table>
  <tbody>
    <tr>
      <th>type</th>
      <th>name</th>
      <th>price</th>
      <th>model</th>
      <th>count</th>
    </tr>
<?php foreach($products as $p): ?>
    <tr>
      <td><?php echo $p['type']; ?></td>
      <td><?php echo $p['name']; ?></td>
      <td><?php echo $p['price']; ?></td>
      <td><?php echo $p['model']; ?></td>
      <td><input type="text" name="<?php echo $p['id']; ?>" value="0" /></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
<input type="submit" name="submit" value="Save" />
<input type="submit" name="submit" value="Cancel" />
</form>