<?php if ($notifications): ?>
	<div class="responsive-table">
		<table width="100%" class="table table-striped table-bordered table-hover datatabled" data-module="datatable" id="dataTables-example">
			<thead>
				<tr>
					<th>#</th>
					<th>Message</th>
					<th>Url</th>
					<th>Partage</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody data-module="bootstrap_toggle_share_course">
				<?php foreach ($notifications as $notification): ?>
					<?php if (user_can('see', 'notification', $notification->id)): ?>
						<tr class="odd gradeX">
							<td><?php echo $notification->id; ?></td>
							<td><?php echo htmlspecialchars($notification->infos->text); ?></td>
							<td><?php echo $notification->infos->url; ?></td>
							<td><?php echo Notification::$VALUE_LABEL_ASSOC[$notification->visibility]; ?></td>
							<td class="td-actions">
								<?php if (user_can('edit', 'notification', $notification->id)): ?>
								<a class="btn btn-success" href="<?php echo base_url('admin/editnotif/'.$notification->id); ?>"><i class="fa fa-pencil"></i></a>
								<?php endif; ?>
								<?php if (user_can('delete', 'notification', $notification->id)): ?>
								<a class="btn btn-danger confirm" title="Supprimer cette notification" href="#" class="confirm btn btn-danger btn-sm" 
									data-url="<?php echo base_url('admin/deletenotif/' . $notification->id) ?>" 
									data-header="Suppression d'une notification" 
									data-body="<p>Attention!</p><p>Vous êtes sur le point de supprimer une notification.</p><p>Continuer?</p> ">
									 <i class="fa fa-trash-o"></i>
								 </a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	Aucune notification à ce jour
<?php endif; ?>
