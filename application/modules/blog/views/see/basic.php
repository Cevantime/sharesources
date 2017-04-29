<?php $this->load->helper('readabledate'); ?>
<ul class="blogposts-list">

	<div class="blogpost-image-wrap">
		<img style="float: left;" src="<?php echo base_url($blogpost->image); ?>"/>
	</div>
	<h3 class="blogpost-title">
		<?php echo htmlentities($blogpost->title); ?>
	</h3>
	<p class="blogpost-description">
		<?php echo htmlentities($blogpost->description); ?>
	</p>
	<p class="blogpost-content">
		<?php echo htmlentities($blogpost->content); ?>
	</p>
	<p class="blogpost-footer">
		<span class="blogpost-date-add">
			publié <?php echo zero_date($blogpost->creation_time) ?>
		</span>
		par <?php echo $blogpost->author; ?>
		<?php if ($blogpost->creation_time != $blogpost->update_time): ?>
			<span class="blogpost-date-add">
				dernière mise à jour 
				<?php echo zero_date($blogpost->update_time) ?>
			</span>
		<?php endif; ?>
	</p>
	<p class="blogpost-action">
		<a href="">

		</a>
	</p>

</ul>