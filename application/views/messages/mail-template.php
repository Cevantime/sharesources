Vous avez reçu un nouveau message 
<?php if(user_is('teacher')) : ?> du formateur <?php else : ?> de l'apprenant <?php endif; ?><?php echo $forname.' '.$name; ?> (<?php echo user('email'); ?>) à propos du cours "<strong><?php echo htmlentities($course->title); ?></strong>" : 
<br/>
<br/>
<p style="font-style: italic"><?php echo str_replace("\n",'<br/>',htmlentities($message)); ?></p>
<br/>
<br/>
Se rendre au <a href="<?php echo base_url('courses/see/'.$course->id); ?>">cours en question</a>

