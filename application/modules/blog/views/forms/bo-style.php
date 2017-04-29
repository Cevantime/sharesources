<div class="row save-post">
	<div class="col-lg-9 col-md-12 main-content">
		<?php echo form_open_multipart(current_url(),array('class'=>'form_add_blogpost')); ?>
			<?php $this->load->view('blog/includes/bo-content'); ?>
			<button class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
		<?php echo form_close(); ?>
	</div>
</div>