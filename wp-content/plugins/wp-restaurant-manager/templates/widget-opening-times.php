<?php
/**
 * Template: Opening times 
 *
 * @since  1.0.0
 * @version 1.0.0
 */
?>

<?php echo do_action('before_opening_times_widget', $params); ?>

<ul class="wprm-opening-times" id="wprm-opening-times">
	<?php if($params['monday']) : ?>
		<li class="monday"><?php _e('Monday','wprm');?>: <?php echo $params['monday']; ?></li>
	<?php endif; ?>
	<?php if($params['tuesday']) : ?>
		<li class="tuesday"><?php _e('Tuesday','wprm');?>: <?php echo $params['tuesday']; ?></li>
	<?php endif; ?>
	<?php if($params['wednesday']) : ?>
		<li class="wednesday"><?php _e('Wednesday','wprm');?>: <?php echo $params['wednesday']; ?></li>
	<?php endif; ?>
	<?php if($params['thursday']) : ?>
		<li class="thursday"><?php _e('Thursday','wprm');?>: <?php echo $params['thursday']; ?></li>
	<?php endif; ?>
	<?php if($params['friday']) : ?>
		<li class="friday"><?php _e('Friday','wprm');?>: <?php echo $params['friday']; ?></li>
	<?php endif; ?>
	<?php if($params['saturday']) : ?>
		<li class="saturday"><?php _e('Saturday','wprm');?>: <?php echo $params['saturday']; ?></li>
	<?php endif; ?>
	<?php if($params['sunday']) : ?>
		<li class="sunday"><?php _e('Sunday','wprm');?>: <?php echo $params['sunday']; ?></li>
	<?php endif; ?>
</ul>

<?php echo do_action('after_opening_times_widget', $params); ?>