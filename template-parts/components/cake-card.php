<?php
/**
 * Cake Card Component
 * 
 * @param array $args {
 *     @type int    $id
 *     @type string $title
 *     @type string $link
 *     @type string $image
 *     @type string $model
 * }
 */
if (!isset($args)) {
    $args = [];
}

$defaults = [
    'id'    => 0,
    'title' => __('Cake', 'text-domain'),
    'link'  => '#',
    'image' => get_theme_file_uri('assets/images/placeholders/cake.png'),
    'model' => 'N/A'
];

$data = wp_parse_args($args, $defaults);
?>

<div class="slider cake-card">
    <a href="<?php echo esc_url($data['link']); ?>" class="slider cake-card__link">
        <img src="<?php echo esc_url($data['image']); ?>" 
            alt="<?php echo esc_attr($data['title']); ?>" 
            class="slider cake-card__image">
        
        <div class="slider cake-card__model" 
            data-model="<?php echo esc_attr($data['model']); ?>">
            <?php echo esc_html($data['model']); ?>
        </div>
    </a>
    
    <h3 class="slider cake-card__title">
        <?php echo esc_html($data['title']); ?>
    </h3>
</div>