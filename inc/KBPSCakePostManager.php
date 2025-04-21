<?php

class KBPSCakePostManager {

	/**
	 * HTML with Cake Featured image with link by slug
	 *
	 * @param string $slug
	 */
	public static function getFeaturedImageWithLink( string $slug ) {
		$post = get_page_by_path( $slug, OBJECT, 'cake' );

		$imageUrl = KBPS_THEME_URI . 'assets/images/placeholders/cake_placeholder.png';
		$postLink = '#';
		$title = 'Post is not found';

		if ( $post instanceof WP_Post ) {
			$postLink = get_permalink( $post->ID );
			$title = get_the_title( $post->ID );

			if ( has_post_thumbnail( $post->ID ) ) {
				$imageUrl = get_the_post_thumbnail_url( $post->ID, 'full' );
			}
		}

		?>
			<a href="<?php echo esc_url( $postLink ); ?>">
				<img src="<?php echo esc_url( $imageUrl ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="kbps-thumbnail">
			</a>

		<?php
	}


	/**
     * Get Cake post Image, Link and Model
     *
     * @param string $model_number Model number
     * @return void
     */
    public static function getCakePostImageAndLinkByModel(string $modelNumber) {
        
        $query = new WP_Query([
            'post_type'  => 'cake',
            'meta_key'   => 'kbps_model',
            'meta_value' => $modelNumber,
            'posts_per_page' => 1,
        ]);

        if (!$query->have_posts()) {
            echo '<p>Cake with model "' . esc_html($modelNumber) . '" not found.</p>';
            return;
        }

        $post = $query->posts[0];
        $postLink = get_permalink($post->ID);
        $title = get_the_title($post->ID);
        $model = get_post_meta($post->ID, 'kbps_model', true);
        $imageUrl = KBPS_THEME_URI . 'assets/images/placeholders/cake_placeholder.png';

        if (has_post_thumbnail($post->ID)) {
            $imageUrl = get_the_post_thumbnail_url($post->ID, 'thumbnail'); // thumbnail, medium, large, full
        }

        ?>

			<a href="<?php echo esc_url( $postLink ); ?>">
				<img src="<?php echo esc_url( $imageUrl ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="kbps-thumbnail">
				<span class="cake-card__model-number"><?php echo esc_html($model); ?></span>
			</a>

        <?php
    }

}
