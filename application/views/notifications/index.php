<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<i class="material-icons">notifications</i>
		
		<?php if($notifications): ?>
		<span class="notification"><?php echo count($notifications); ?></span>
		<?php endif; ?>
		<p class="hidden-lg hidden-md">
			Notifications
			<b class="caret"></b>
		</p>
	</a>
	<?php if($notifications): ?>
	<ul class="dropdown-menu notifications" data-module="mark_notifs">
		<?php foreach($notifications as $notification): ?>
		<li>
			<a href="<?php echo base_url('notifications/see/'.$notification->id);?>">
				<span class="notif-mark" data-id="<?php echo $notification->id; ?>"><i class="fa fa-trash"></i></span>
				<span><?php echo $notification->text; ?></span>
			</a>
			
		</li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</li>