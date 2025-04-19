<?php
/**
 * The front page template file.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<div id="primary">
	<?php get_template_part( 'template-parts/content', 'front' ); ?>
	</div>
	
<?php

get_footer();