<?php $this->load->helper('form'); ?>
<?php echo form_open(base_url('courses/sendmsg/'.$course->id)); ?>
<?php if(user_is('users')): ?>
<div class="form-group">
	<label>Votre prénom</label>
	<input type="text" class="form-control" name="from_forname">
</div>
<div class="form-group">
	<label>Votre nom</label>
	<input type="text" class="form-control" name="from_name" >
</div>
<?php endif; ?>
<div class="form-group">
	<label>Votre message</label>
	<textarea rows="10" name="content" class="form-control"></textarea>
</div>
<?php if(user_is('teacher')): ?>
<input type="hidden" name="from_id" value="<?php echo user_id(); ?>">
<?php endif; ?>
<input type="hidden" name="course_id" value="<?php echo $course->id; ?>">

<?php echo form_close(); ?>
