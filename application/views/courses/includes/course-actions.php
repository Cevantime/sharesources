<?php if(user_can('see', 'course', $course->id)): ?>
    <a class="btn btn-info" title="Lire le cours" href="<?php echo base_url('courses/see/' . $course->id); ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    <a class="btn btn-success" title="Dupliquer le cours" href="<?php echo base_url('courses/duplicate/' . $course->id); ?>" class="btn btn-default btn-sm"><i class="fa fa-copy"></i></a>
<?php endif; ?>
<?php if(user_can('see_pdfs','course', $course->id)): ?>
	<a class="btn btn-info" title="Voir la version PDF" target="_blank" href="<?php echo base_url('courses/see/' . $course->id . '?format=pdf'); ?>" class="btn btn-default btn-sm"><i class="fa fa-file-pdf-o"></i></a>
	<a class="btn btn-info" title="Voir la version PDF latex" target="_blank" href="<?php echo base_url('courses/see/' . $course->id . '?format=latex'); ?>" class="btn btn-default btn-sm"><img style="width: 40px; height: 18px;" src="<?php echo base_url('assets/vendor/images/latex.png'); ?>"></a>
<?php endif; ?>
<?php if (user_can('update', 'course', $course->id)): ?>
	<a class="btn btn-success" title="Modifier le cours" href="<?php echo base_url('courses/edit/' . $course->id); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
<?php endif; ?>
<?php if (user_can('delete', 'course', $course->id)): ?>
	<a class="btn btn-danger confirm" title="Supprimer ce cours" href="#" class="confirm btn btn-danger btn-sm" 
	   data-url="<?php echo base_url('courses/delete/' . $course->id) ?>" 
	   data-header="Suppression d'un cours" 
	   data-body="<p>Attention!</p><p>Vous êtes sur le point de supprimer un cours.</p><p>Continuer?</p> ">
		<i class="fa fa-trash-o"></i>
	</a>
<?php endif; ?>
			
