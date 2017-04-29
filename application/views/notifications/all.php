<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">message</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Les notifications</h4>
				<div class="toolbar">
					<a class="btn btn-success" href="<?php echo base_url('admin/addnotif'); ?>" title="Ajouter une notification">
						<i class="fa fa-plus"></i> Ajouter une notification
					</a>
				</div>
				<?php $this->load->view('notifications/includes/notifications-table', array('notifications' => $notifications)); ?>
			</div>
		</div>
	</div>
</div>
