<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">library_books</i>
			</div>			
			<div class="card-content">
				<h4 class="card-title">Les cours</h4>
				<?php echo form_open_multipart(current_url(), array('class' => 'form_add_blogpost')); ?>
				<?php $this->load->view('blog/includes/bo-content'); ?>
				<button class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
