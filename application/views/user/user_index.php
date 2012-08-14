<h2>Users</h2>
<form id="search" action="user" method="post">
  <div id="controls"> <?php echo $controls; ?> </div>
  <input type="text" name="filter_criteria" />
  <input type="submit" name="submit" value="Search" />
</form>
<div id="list"><?php echo $user_list; ?></div>
