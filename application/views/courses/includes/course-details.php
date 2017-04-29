<?php $this->load->helper('filerender'); ?>
<div class="card">	
	<div class="card-header card-header-icon" data-background-color="normal">
		<i class="material-icons">library_books</i>
	</div>
	<div class="card-content">
		<h4 class="card-title">Les cours du <?php echo date('d/m/Y', $from); ?></h4>
		<?php if ($courses): ?>
			<ul class="nav nav-pills nav-stacked">
				<?php foreach ($courses as $course): ?>
					<li>
						<a href="#course-<?php echo $course->id; ?>-tab"  data-toggle="tab">
							<?php echo htmlspecialchars($course->title); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<br/>
			<div id="my-tab-content" class="tab-content">
				<?php foreach ($courses as $course): ?>
					<div class="tab-pane"  id="course-<?php echo $course->id; ?>-tab" data-tabs="tabs">
						<div class="panel panel-default">
							<!-- Default panel contents -->
							<div class="panel-heading"><?php echo htmlspecialchars($course->title); ?></div>
							<div class="panel-body">
								<?php echo $course->description; ?>
							</div>
							<?php if(user_can('see_files','course', $course)): ?>
							<?php if ($course->files): ?>
								<ul class="list-group">
									<?php foreach ($course->files as $file): ?>
										<li class="list-group-item">
											<a target="_blank" href="<?php echo base_url($file->file); ?>" >
												<i class="fa <?php echo filefaclass($file->infos); ?>"></i> <?php echo $file->name; ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php endif; ?>
							<div class="panel-footer">
								<?php if (user_can('see', 'course', $course->id)) : ?>

									<p class="text-center table">
										<span class="td-actions">
											<?php $this->load->view('courses/includes/course-actions', array('course' => $course)); ?>
										</span>
									</p>
								<?php endif; ?>
								<?php if (user_can('share_to_teachsession', 'course', $course->id)): ?>
									<?php echo form_open(); ?>
									<div class="form-group">
										<label>
											Changer la date de partage:
										</label>
										<input type="text" data-course-id="<?php echo $course->id; ?>" class="form-control datepicker-field change-share-date" id="InputName" name="date_start" value="<?php echo date('d/m/Y', $course->date); ?>"/>
									</div>
									</form>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			Aucun cours pour cette date.
		<?php endif; ?>

	</div>
</div>
<h3></h3>



