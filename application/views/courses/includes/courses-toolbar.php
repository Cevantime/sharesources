<div class="toolbar">
	<a class="btn btn-primary" href="<?php echo base_url('courses/calendar'); ?>">Revenir aux cours</a>
	<a class="btn btn-success send-msg" data-module="send_msg_author"><i class="fa fa-comment"></i> Envoyer un message à l'auteur</a>
	<?php if(user_can('see_pdfs','course', $course->id)): ?>
		<a class="btn btn-info" title="Voir la version PDF latex" target="_blank" href="<?php echo base_url('courses/see/' . $course->id . '?format=latex'); ?>" class="btn btn-default btn-sm">Format LATEX</a>
	<?php endif; ?>
	<?php if (user_can('update', 'course', $course->id)): ?>
		<a class="btn btn-success" href="<?php echo base_url('courses/edit/' . $course->id); ?>">Modifier</a>
	<?php endif; ?>
</div>
<hr/>