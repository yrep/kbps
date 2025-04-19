<?php

class CakePostManager {

	/**
	 * HTML with Cake Featured image with link by slug
	 *
	 * @param string $slug
	 */
	public static function getFeaturedImageWithLink( string $slug ) {
		$post = get_page_by_path( $slug, OBJECT, 'cake' );

		$image_url = KBPS_THEME_URI . 'assets/images/placeholders/cake_placeholder.png';
		$post_link = '#';
		$title = 'Post is not found';

		if ( $post instanceof WP_Post ) {
			$post_link = get_permalink( $post->ID );
			$title = get_the_title( $post->ID );

			if ( has_post_thumbnail( $post->ID ) ) {
				$image_url = get_the_post_thumbnail_url( $post->ID, 'full' );
			}
		}

		?>
			<a href="<?php echo esc_url( $post_link ); ?>">
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="kbps-thumbnail">
			</a>

		<?php
	}
}
