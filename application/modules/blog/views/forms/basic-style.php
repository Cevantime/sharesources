<?php echo form_open_multipart(current_url(),array('class'=>'form_add_blogpost')); ?>
	<?php $this->load->view('blog/includes/basic-content'); ?>
	<p><input type="submit" value="Enregistrer" name="blogpost_add"/></p>
<?php echo form_close(); ?>