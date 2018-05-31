<?php $this->load->helper('readabledate'); ?>
<div class="row" data-module="linker">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">library_books</i>
			</div>
			<div class="card-content">
				<h4 class="card-title"><?php echo htmlspecialchars($course->title); ?></h4>
				<?php $this->load->view('courses/includes/courses-toolbar',array('course'=> $course)); ?>

				<div class="course-banner">
					<img src="<?php echo base_url($course->image ? $course->image : $course->category_image); ?>" width="500" height="300" />
				</div>

				<br/>

				<div class="tutorial course-content">
					<?php echo $course->content; ?>
				</div>
				
				<hr>
				
				<div class="course-footer">
					publié par <span class="course-author"><?php echo htmlspecialchars($course->author_forname.' '.$course->author_name); ?></span>
					<span class="course-creation-time"><?php echo zero_date($course->creation_time); ?></span>
					<small>dernière mise à jour <span class="course-creation-time"><?php echo zero_date($course->update_time); ?></span></small>
				</div>

				<br/>
				<?php if (user_can('see_files', 'course', $course->id)): ?>
				<?php if ($course->files): ?>
					<hr>
					<div>
						Les ressources :
						<ul>
							<?php foreach ($course->files as $file): ?>
								<li><a target="_blank" href="<?php echo base_url($file->file); ?>"><i class="fa <?php echo filefaclass(json_decode($file->infos)); ?>"></i> <?php echo $file->name; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<hr>
				<?php endif; ?>
				<?php endif; ?>
				<hr>
				<?php $this->load->view('courses/includes/courses-toolbar',array('course'=> $course)); ?>
				<hr>
				<?php if($course->tagsList): ?>
				<div class="tags-list">
					<p>Tags associés:</p> 
					<ul>
						<?php foreach($course->tagsList as $tag): ?>
						<li class="tag">
							<a href="<?php echo base_url('courses/tag/'.$tag->alias); ?>"><?php echo htmlentities($tag->label); ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<div style="display: none" id="form-msg-wrapper">
	<?php $this->load->view('courses/includes/msg-form'); ?>
</div>

