<?php if ($courses): ?>
	<?php $this->layout->block('css'); ?>
	<link href="<?php echo base_url('assets/vendor/css/bootstrap-toggle.min.css'); ?>" rel="stylesheet">
	<?php $this->layout->block(); ?>
	<?php $this->load->helper('filerender'); ?>
	<div class="responsive-table">
		<table width="100%" class="table table-striped table-bordered table-hover datatabled" data-module="datatable" id="dataTables-example">
			<thead>
				<tr>
					<th>Image</th>
					<th>Titre</th>
					<th>Description</th>
					<th>Auteur</th>
					<th>Date d'ajout</th>
					<th>Fichiers</th>
					<th>Actions</th>
					<th>Partager</th>
				</tr>
			</thead>
			<tbody data-module="bootstrap_toggle_share_course">
				<?php foreach ($courses as $course): ?>
					<?php if (user_can('see', 'course', $course)): ?>
						<tr class="odd gradeX">
							<td><img src='<?php echo imageresize($course->image ? $course->image : $course->category_image, 30, 30); ?>' /></td>
							<td><?php echo htmlentities($course->title); ?></td>
							<td><?php echo $course->description; ?></td>
							<td class="td-author">
								<img src="<?php echo imageresize($course->author_avatar ? $course->author_avatar : 'assets/local/images/default-avatar.png', 30, 30); ?>"/><br/>
								<span class="author-name"><?php echo $course->author_forname.' '.$course->author_name; ?></span>
							</td>
							<td>
								<?php echo date('Y/m/d', $course->creation_time); ?>
							</td>
							<td>
								<?php if (user_can('see_files', 'course', $course->id)): ?>
									<?php if ($course->files): ?>
										<?php foreach ($course->files as $file): ?>
											<a class="btn" target="_blank" style="margin: 5px; padding: 5px;"href="<?php echo base_url($file->file); ?>" >
												<i class="fa <?php echo filefaclass($file->infos); ?>"></i> <?php echo $file->name; ?>
											</a>
											<br/>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
							<td class="td-actions">
								<?php $this->load->view('courses/includes/course-actions', array('course' => $course)); ?>
							</td>
							<td>
								<?php if (teachsession() && user_can('share_to_teachsession', 'course', $course->id)): ?>
									<span class="mytogglebutton">
										<label>
											<input 
												type="checkbox" 
												class="toggle-share-course-session" 
												data-course-share-id="<?php echo $course->id; ?>" 
												<?php if ($course->shared): ?>checked<?php endif; ?>/>
											<span class="toggle"></span>
										</label>                
									</span>

								<?php elseif (!teachsession()) : ?>
									Session non-définie
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	Aucun cours à ce jour
<?php endif; ?>
