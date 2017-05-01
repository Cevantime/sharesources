
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">date_range</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Les sessions</h4>
				<div class="toolbar">
					<a class="btn btn-primary" href="<?php echo base_url('sessions/add'); ?>"><i class="fa fa-plus"></i> Nouvelle session</a>
					
				</div>
				<?php if($sessions): ?>
				<div class="responsive-table">
					<div class="material-datatables">
						<table class="table datatabled" data-module="datatable">
							<thead>
								<tr>
									<th>Avatar</th>
									<th>Login</th>
									<th>Name</th>
									<th>Date de début</th>
									<th>Date de fin</th>
									<th>Actions</th>
									<th>Accéder</th>
								</tr>
							</thead>
							<tbody data-module="bootstrap_toggle">
								<?php $user = user(); ?>
								<?php $teachsession = teachsession(); ?>
								<?php foreach ($sessions as $session): ?>
									<tr>
										<td><img src="<?php echo imageresize(base_url($session->avatar), 25, 25); ?>"/></td>
										<td><?php echo $session->login ?></td>
										<td><?php echo htmlspecialchars($session->name); ?></td>
										<td><?php echo date('d/m/Y', $session->date_start); ?></td>
										<td><?php echo date('d/m/Y', $session->date_end); ?></td>
										<td class="td-actions">
											<?php $this->load->view('sessions/includes/sessions-actions', array(
												'teachsession' => $teachsession,
												'session' => $session
											)); ?>
										</td>
										<td>
											<span class="mytogglebutton">
												<label>
													<input 
														type="checkbox" 
														data-toggle="toggle" 
														data-teach-session-id="<?php echo $session->id; ?>" 
														class="toggle-session-teacher-share"
														<?php if (user_can('update', 'teachsession', $session->id) && $this->teachsession->isSharedTo($session->id, $user)): ?>
														checked="checked"
														<?php endif; ?>>
													<span class="toggle"></span>
												</label>                
											</span>

										</td>
									</tr>

								<?php endforeach; ?>

							</tbody>
						</table>
					</div>
				</div>
				<?php else: ?>
					Aucune session
				<?php endif; ?>
				
			</div>
			<!-- end content-->
		</div>
		<!--  end card  -->
	</div>
	<!-- end col-md-12 -->
</div>