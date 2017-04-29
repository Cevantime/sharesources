<?php $this->layout->block('css'); ?>
<link href="<?php echo base_url('assets/local/css/calendar.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/local/css/modules/loaders.css'); ?>" rel="stylesheet">
<?php $this->layout->block(); ?>
<div class="row">
	<div class="col-md-8">
		<div class="card"  data-module="change_date_shares">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">date_range</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Le calendrier</h4>
				<div id="calendar" data-module="calendar">
					<div style="text-align:center;"><div class="loader very-big-loader"></div></div>
				</div>
			</div>
		</div>
	</div>
	<div  id="course-details" class="col-md-4">
		
	</div>
</div>
