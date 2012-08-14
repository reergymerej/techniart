<h1><?php echo $name; ?></h1>
<p><a href="<?php echo site_url("event/read/$id#summary"); ?>">&lt;&lt;back to event page</a></p>
<p>Finishing this event will prevent making additional changes. <strong>This cannot be undone.</strong></p>
<?php echo form_open("event_summary/create/$id"); ?>
<label>Final Notes</label>
<textarea name="notes"></textarea>
<input type="submit" name="submit" value="Finish Event" />
<input type="submit" name="submit" value="Cancel" />
</form>
