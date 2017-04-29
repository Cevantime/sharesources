<?php $this->load->helper('readabledate'); ?>
<div class="main-content">

	<div class="row blog-post">
		<div class="col-sm-9 main-content">
			
			<p class="muted small">
				dernière mise à jour 
				<?php echo zero_date($blogpost->update_time) ?>
				par <?php echo htmlentities($blogpost->author); ?>
			</p>
			<a href="#">
				<img alt="" src="<?php echo base_url($blogpost->image); ?>" class="img-responsive">
			</a>

			<?php echo htmlentities($blogpost->content); ?>
			
        </div>

	</div>

</div>