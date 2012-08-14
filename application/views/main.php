<h2>Login</h2>
<div class="message shadow rounded"><?php echo $message;?></div>
<?php echo form_open("main");?>
    <label for="email">email</label>
    <input type="text" name="email" value="<?php echo set_value('email') ?>" />
    <label for="password">password</label>
    <input type="password" name="password" value="<?php echo set_value('password'); ?>" />
    <?php echo form_submit('submit', 'Login');?>
<?php echo form_close();?>