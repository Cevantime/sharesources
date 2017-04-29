<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">account_circle</i>
			</div>

			<div class="card-content">
				<h4 class="card-title">Les administrateurs</h4>
				<div class="toolbar">
					<?php if(user_can('add', 'administrator')): ?>
					<a class="btn btn-primary" href="<?php echo base_url('administrator/add'); ?>"><i class="fa fa-plus"></i> Nouvel administrateur</a>
					<?php endif; ?>
					<!--    <button class="btn btn-default">Import</button>
						<button class="btn btn-default">Export</button>-->
					<div class="btn-group">
					</div>
				</div>
				<div class="responsive-table">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Login</th>
								<th>Prénom</th>
								<th>Nom</th>
								<th>Email</th>
								<th style="width: 3.5em;"></th>
							</tr>
						</thead>
						<tbody>
							<?php if($users): ?>
							<?php foreach ($users as $administrator): ?>
								<tr>
									<td><img src="<?php echo imageresize(base_url($administrator->avatar ? $administrator->avatar : 'assets/local/images/default-avatar.png'), 25, 25) ; ?>"/></td>
									<td><?php echo $administrator->login ?></td>
									<td><?php echo $administrator->forname ?></td>
									<td><?php echo $administrator->name ?></td>
									<td><?php echo $administrator->email ?></td>
									<td class="td-actions">
										<?php if(user_can('edit','webforceadmin', $administrator->id)): ?>
										<a class="btn btn-success" href="<?php echo base_url('administrator/edit/' . $administrator->id) ?>"><i class="fa fa-pencil"></i></a>
										<?php endif; ?>
										<?php if(user_can('delete','webforceadmin', $administrator->id)): ?>
										<a href="#" class="btn btn-danger confirm" 
										   data-url="<?php echo base_url('administrator/delete/' . $administrator->id) ?>" 
										   data-header="Suppression d'un administrateur" 
										   data-body="<p>Attention!</p><p>Vous êtes sur le point de supprimer un administrateur.</p><p>Continuer?</p> ">
											<i class="fa fa-trash-o"></i>
										</a>
										<?php endif; ?>
									</td>
								</tr>

							<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

