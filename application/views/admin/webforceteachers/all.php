
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">account_box</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Les formateurs</h4>
				<div class="toolbar">
					<a class="btn btn-primary" href="<?php echo base_url('teacher/add/' . str_replace('/', '-', $modelName)); ?>"><i class="fa fa-plus"></i> Nouveau formateur</a>
					<!--    <button class="btn btn-default">Import</button>
						<button class="btn btn-default">Export</button>-->
					<div class="btn-group">
					</div>
				</div>

				<?php if (!$users): ?>
					<p>
						Aucun formateur à ce jour
					</p>
				<?php else: ?>
				<div class="responsive-table">
				<table class="table datatabled" data-module="datatable">
					<thead>
						<tr>
							<th>#</th>
							<th>Prénom</th>
							<th>Nom</th>
							<th>Login</th>
							<th>Email</th>
							<th style="width: 3.5em;"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($users as $user): ?>
							<tr>
								<td><img src="<?php echo imageresize(base_url($user->avatar ? $user->avatar : 'assets/local/images/default-avatar.png'), 25, 25) ; ?>"/></td>
								<td><?php echo htmlspecialchars($user->forname) ?></td>
								<td><?php echo htmlspecialchars($user->name) ?></td>
								<td><?php echo htmlspecialchars($user->login) ?></td>
								<td><?php echo htmlspecialchars($user->email) ?></td>
								<td class="td-actions">
									<a class="btn btn-success" href="<?php echo base_url('teacher/edit/' . $user->id) ?>"><i class="fa fa-pencil"></i></a>
									<a href="#" class="btn btn-danger confirm" 
									   data-url="<?php echo base_url('teacher/delete/' . $user->id) ?>" 
									   data-header="Suppression d'un utilisateur" 
									   data-body="<p>Attention!</p><p>Vous êtes sur le point de supprimer un utilisateur.</p><p>Continuer?</p> ">
										<i class="fa fa-trash-o"></i>
									</a>
								</td>
							</tr>

						<?php endforeach; ?>

					</tbody>
				</table>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>