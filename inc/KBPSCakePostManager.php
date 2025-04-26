<?php

class KBPSCakePostManager {


	public static function getCakesForSlider($args = []) {
		$image_size = $args['image_size'] ?? 'large';
		unset($args['image_size']);
		
		$query = new WP_Query(wp_parse_args($args, [
			'post_type' => 'cake',
			'posts_per_page' => -1,
			'orderby' => 'meta_value_num',
			'meta_key' => 'kbps_model'
		]));
	
		$cakes = [];
		
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$post_id = get_the_ID();
				
				$cakes[] = [
					'id' => $post_id,
					'title' => get_the_title(),
					'link' => get_permalink(),
					'image' => has_post_thumbnail() 
						? get_the_post_thumbnail_url(
							$post_id, 
							apply_filters('kbps_cake_image_size', $image_size, $post_id)
						)
						: KBPS_THEME_URI . 'assets/images/placeholders/cake_placeholder.png',
					'model' => get_post_meta($post_id, 'kbps_model', true)
				];
			}
			wp_reset_postdata();
		}
		
		return $cakes;
	}



	/**
	 * Get cakes for slider with customizable parameters
	 * 
	 * @param array $args {
	 *     Optional. Array of query parameters.
	 *     
	 *     @type int    $posts_per_page Number of cakes to return. Default -1 (all).
	 *     @type string $orderby        Order by parameter. Default 'meta_value_num'.
	 *     @type string $meta_key       Meta key for ordering. Default 'kbps_model'.
	 *     @type string $order          Sort order. Default 'ASC'.
	 *     @type array  $meta_query     Additional meta query parameters.
	 *     @type array  $tax_query      Taxonomy query parameters.
	 *     @type bool   $with_models    Only include cakes with model numbers. Default true.
	 *     @type string $image_size     WordPress image size. Default 'full'.
	 * }
	 * @return array Array of cake data or empty array if none found.
	 *
	 * $cakes = KBPSCakePostManager::getCakesForSlider();
	 *
	 *$cakes = KBPSCakePostManager::getCakesForSlider([
	 *		'posts_per_page' => 4,
	 *		'order' => 'DESC'
	 *	]);
	 *
	 * 
	 * $cakes = KBPSCakePostManager::getCakesForSlider([
	 *		'tax_query' => [
	 *			[
	 *				'taxonomy' => 'cake_type',
	 *				'field'    => 'slug',
	 *				'terms'    => 'wedding',
	 *			]
	 *		]
	 *	]);
	 * 
	 * $cakes = KBPSCakePostManager::getCakesForSlider([
     *		'with_models' => false
	 *	]);
	 * 
	 * $cakes = KBPSCakePostManager::getCakesForSlider([
     *	'image_size' => 'thumbnail'
	 * ]);
	 * 
	 */
/*
	public static function getCakesForSlider(array $args = []) {
		$defaults = [
			'post_type'      => 'cake',
			'posts_per_page' => -1,
			'orderby'        => 'meta_value_num',
			'meta_key'       => 'kbps_model',
			'order'          => 'ASC',
			'with_models'   => true,
			'image_size'     => 'full',
		];

		// Parse incoming args with defaults
		$parsed_args = wp_parse_args($args, $defaults);
		
		// Handle meta query for models if needed
		if ($parsed_args['with_models']) {
			$parsed_args['meta_query'] = isset($parsed_args['meta_query']) 
				? array_merge($parsed_args['meta_query'], [
					[
						'key'     => 'kbps_model',
						'compare' => 'EXISTS',
					]
				])
				: [
					[
						'key'     => 'kbps_model',
						'compare' => 'EXISTS',
					]
				];
		}

		// Remove custom params before WP_Query
		$with_models = $parsed_args['with_models'];
		$image_size = $parsed_args['image_size'];
		unset($parsed_args['with_models'], $parsed_args['image_size']);

		$query = new WP_Query($parsed_args);
		$cakes = [];

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$post_id = get_the_ID();
				$model = get_post_meta($post_id, 'kbps_model', true);

				$cakes[] = [
					'id'        => $post_id,
					'title'     => get_the_title(),
					'link'      => get_permalink(),
					'image'     => has_post_thumbnail() 
						? get_the_post_thumbnail_url($post_id, $image_size) 
						: KBPS_THEME_URI . 'assets/images/placeholders/cake_placeholder.png',
					'model'     => $model,
					'excerpt'   => get_the_excerpt(),
					'thumbnail' => has_post_thumbnail() 
						? get_the_post_thumbnail($post_id, $image_size) 
						: '',
				];
			}
			wp_reset_postdata();
		}

		return $cakes;
	}

*/


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
	/*
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

        PHP_END

			<a href="<?php echo esc_url( $postLink ); ?>">
				<img src="<?php echo esc_url( $imageUrl ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="kbps-thumbnail">
				<span class="cake-card__model-number"><?php echo esc_html($model); ?></span>
			</a>

        PHP_START
    }
	*/

	public static function getCakePostImageAndLink($post) {
		$postLink = get_permalink($post->ID);
		$title = get_the_title($post->ID);
		$model = get_post_meta($post->ID, 'kbps_model', true);
		$imageUrl = KBPS_THEME_URI . 'assets/images/placeholders/cake_placeholder.png';
	
		if (has_post_thumbnail($post->ID)) {
			$imageUrl = get_the_post_thumbnail_url($post->ID, 'large'); // Используем large для лучшего качества
		}
		?>
		<a href="<?php echo esc_url($postLink); ?>">
			<img src="<?php echo esc_url($imageUrl); ?>" alt="<?php echo esc_attr($title); ?>" class="kbps-thumbnail">
			<span class="cake-card__model-number"><?php echo esc_html($model); ?></span>
		</a>
		<?php
	}



	public static function getFrontPageCakes() {

		$models_option = get_option('kbps_front_cake_models', '');
		
		$requested_models = [];
		if (!empty($models_option)) {
			$requested_models = array_map('trim', explode(',', $models_option));
			$requested_models = array_slice($requested_models, 0, 4);
		}
		
		if (empty($requested_models)) {
			$query = new WP_Query([
				'post_type' => 'cake',
				'posts_per_page' => 4,
				'orderby' => 'meta_value_num',
				'meta_key' => 'kbps_model',
				'order' => 'ASC'
			]);
			
			if ($query->have_posts()) {
				return $query->posts;
			}
			return [];
		}
		
		$meta_query = [];
		foreach ($requested_models as $model) {
			$meta_query[] = [
				'key' => 'kbps_model',
				'value' => $model
			];
		}
		
		$query = new WP_Query([
			'post_type' => 'cake',
			'posts_per_page' => 4,
			'meta_query' => [
				'relation' => 'OR',
				...$meta_query
			],
			'orderby' => 'meta_value_num',
			'meta_key' => 'kbps_model'
		]);
		
		return $query->have_posts() ? $query->posts : [];
	}

}
