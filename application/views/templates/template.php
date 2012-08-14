<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<link href="<?php echo $css; ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<?php foreach( $js as $j ): ?>
<script type="text/javascript" src="<?php echo $j; ?>"></script>
<?php endforeach; ?>
</head>
<body>
<div id="wrapper">
  <div id="header"> <div id="currentUser"><?php echo $currentUser; ?></div></div>
  <div id="nav" class="shadow rounded">
    <ul>
      <li><a href="<?php echo site_url('event'); ?>">events</a></li>
<!--
      <li><a href="<?php echo site_url('product'); ?>">products</a></li>
-->
      <li><a href="<?php echo site_url('user'); ?>">users</a></li>
      <li><a href="<?php echo site_url('main/logout'); ?>">logout</a></li>
    </ul>
  </div>
  <div id="content"><?php echo $content; ?></div>
  <div id="footer"></div>
</div>
</body>
</html>