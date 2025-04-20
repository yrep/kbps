<?php
/**
 * The template for displaying all pages.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $kbpsCore;

get_header(); ?>

	<div id="primary" <?php $kbpsCore->getClass('primary'); ?>>

<?php
	while (have_posts()) : the_post();
    the_title('<h1 class="entry-title">', '</h1>');
    the_content();
    
    // Дополнительные функции вывода
    the_post_thumbnail(); // Миниатюра
    //edit_post_link(); // Ссылка на редактирование
  endwhile;
?>


	</div>

<?php get_footer(); ?>
