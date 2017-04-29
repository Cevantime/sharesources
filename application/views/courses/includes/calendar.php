<?php $this->load->helper('readabledate'); ?>
<?php $this->load->helper('colortext'); ?>
<div class="datepicker">
	<div class="datepicker-days" style="">
		<table class="table-condensed">
			<thead>
				<tr>
					<th colspan="7" class="datepicker-title" style="display: none;"></th>
				</tr>
				<tr>
					<th class="prev" data-monthyear="<?php echo $prevMonth; ?>" style="visibility: visible;">«</th>
					<th colspan="5" class="datepicker-switch"><?php echo ucfirst(month($firstOfMonth)); ?> <?php echo date('Y', $firstOfMonth); ?></th>
					<th class="next" data-monthyear="<?php echo $nextMonth; ?>" style="visibility: visible;">»</th>
				</tr>
				<tr>

					<th class="dow">l</th>
					<th class="dow">ma</th>
					<th class="dow">me</th>
					<th class="dow">j</th>
					<th class="dow">v</th>
					<th class="dow">s</th>
					<th class="dow">d</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$dayCount = 0; 
				$sessionFrom = teachsession('date_start');
				$sessionFrom -= ($sessionFrom % 86400) + 3600;
				$sessionTo = teachsession('date_end');
				$sessionTo -= ($sessionTo % 86400) + 3600;
				$today = date('Y-n-j');
				?>
				<tr>
				<?php foreach($days as $date => $day): ?>
					<td data-from="<?php echo $day['timestamp']; ?>" data-day="<?php echo date('Y-n-j', $day['timestamp']); ?>" class="<?php if($date == $today ): ?>today <?php endif; ?><?php 
						if($day['timestamp'] < $sessionFrom 
							|| $day['timestamp'] < $firstOfMonth): ?>old <?php endif; ?><?php 
						if($day['timestamp'] > $sessionTo 
								|| $day['timestamp'] > $lastOfMonth) :?>new <?php endif; ?>day"><span class="day-number"><?php echo date('j', $day['timestamp']); ?></span>
						<?php if($day['courses']): ?>
						<ul class="day-courses">
							<?php foreach($day['courses'] as $course):?>
							<?php $color = (isset($course->category_color) && $course->category_color) ? '#'.$course->category_color : '#ddd' ;?>
							<li title="<?php echo htmlspecialchars($course->title); ?>"
								style="background: <?php echo $color; ?>; color: <?php echo colortext($color); ?>"
								class="<?php echo alias($course->category_name); ?>"><?php echo htmlspecialchars($course->title); ?></li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>
					</td>
					<?php $dayCount++; 
					if($dayCount==7): ?>
				</tr><tr>
					<?php 
					$dayCount = 0;
					endif; ?>
				<?php endforeach; ?>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>