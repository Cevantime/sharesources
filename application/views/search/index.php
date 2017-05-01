<?php if( ! isset($courses) && ! isset($teachers)): ?>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">not_interested</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Aucun résultat</h4>
				
				Aucun résultat necorrespond à votre recherche.
			</div>
		</div>
	</div>
</div>
<?php endif; ?>


<?php if(isset($courses)): ?>
	<?php $this->load->view('courses/all', array('courses' => $courses)); ?>
<?php endif; ?>

<?php if(isset($teachers)): ?>

	<?php $this->load->view('admin/webforceteachers/all', array('users' => $teachers, 'modelName' => 'webforceteacher')); ?>
<?php endif; ?>

<?php if(isset($sessions)): ?>

	<?php $this->load->view('sessions/includes/list-sessions', array('sessions' => $sessions)); ?>
<?php endif; ?>
