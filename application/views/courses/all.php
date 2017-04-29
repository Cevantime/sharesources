<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">library_books</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Les cours</h4>
				<div class="toolbar">
					<?php if (user_can('add', 'course')): ?>
						<a class="btn btn-primary" href="<?php echo base_url('courses/bootstrap'); ?>"><i class="fa fa-plus"></i> Nouveau cours</a>
					<?php endif; ?>
				</div>
				<?php $this->load->view('courses/includes/courses-table', array('courses' => $courses)); ?>
				
			</div>
		</div>
	</div>
</div>
