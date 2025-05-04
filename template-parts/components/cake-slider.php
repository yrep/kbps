<?php
/**
 * Cake Slider Component
 * 
 * @param array $args {
 *     @type array $cakes Array of cake data
 *     @type bool  $autoplay Whether to autoplay slider
 * }
 */
if (!isset($args)) {
    $args = [];
}

$defaults = [
    'cakes'    => [],
    'autoplay' => true
];

$data = wp_parse_args($args, $defaults);

if (empty($data['cakes'])) {
    return;
}
?>

<div class="cake-slider-container">
    <div class="cake-slider swiper">
        <div class="swiper-wrapper">
            <?php
            $cakes = $data['cakes'];
            $slide_count = count($cakes);
            $min_slides = 9;
            
            if ($slide_count > 0) {
                $total_needed = max($min_slides, $slide_count);
                for ($i = 0; $i < $total_needed; $i++) {
                    $cake = $cakes[$i % $slide_count];
                    echo '<div class="swiper-slide">';
                    get_template_part('template-parts/components/cake-card', null, [
                        'id'    => $cake['id'],
                        'title' => $cake['title'],
                        'link'  => $cake['link'],
                        'image' => $cake['image'],
                        'model' => $cake['model']
                    ]);
                    echo '</div>';
                }
            }
            ?>
        </div>
        
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</div>