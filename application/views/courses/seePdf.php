<div>
	<img src="<?php echo base_url($course->image ? $course->image : $course->category_image); ?>" width="500" height="300" />
</div>

<br/>

<div class="tutorial course-content">
	<?php echo $course->content; ?>
</div>
