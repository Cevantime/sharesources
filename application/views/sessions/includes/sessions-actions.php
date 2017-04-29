<?php if (user_can('update', 'teachsession', $session->id)): ?>
	<a class="btn btn-success" title="Modifier la session" href="<?php echo base_url('sessions/edit/' . $session->id) ?>"><i class="fa fa-pencil"></i></a>
<?php endif; ?>
<?php
$isNotCurrentSession = !$teachsession;
$isNotCurrentSession |= ($teachsession && ($teachsession->id != $session->id))
?>
<?php if (user_can('set_current', 'teachsession', $session->id) && ($isNotCurrentSession)): ?>
	<a class="btn btn-info" title="Définir comme session courante" href="<?php echo base_url('sessions/setcurrent/' . $session->id) ?>"><i class="fa fa-sign-in"></i></a>
<?php endif; ?>
<?php if (user_can('delete', 'teachsession', $session->id)): ?>
	<a href="#" class="confirm btn btn-danger" 
	   data-url="<?php echo base_url('sessions/delete/' . $session->id) ?>" 
	   data-header="Suppression d'une session" 
	   data-body="<p>Attention!</p><p>Vous êtes sur le point de supprimer une session.</p><p>Continuer?</p> ">
		<i class="fa fa-trash-o"></i>
	</a>
<?php endif; ?>