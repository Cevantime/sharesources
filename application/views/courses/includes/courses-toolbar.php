<div class="toolbar">
	<a class="btn btn-primary" href="<?php echo base_url('courses/calendar'); ?>">Revenir aux cours</a>
	<a class="btn btn-success send-msg" data-module="send_msg_author"><i class="fa fa-comment"></i> Envoyer un message à l'auteur</a>
	<?php if (user_can('update', 'course', $course->id)): ?>
		<a class="btn btn-success" href="<?php echo base_url('courses/edit/' . $course->id); ?>">Modifier</a>
	<?php endif; ?>
</div>