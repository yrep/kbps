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

<div class="cake-slider swiper" data-autoplay="<?php echo $data['autoplay'] ? 'true' : 'false'; ?>">
    <div class="swiper-wrapper">
        <?php foreach ($data['cakes'] as $cake) : ?>
            <div class="swiper-slide">
                <?php 
                get_template_part('template-parts/components/cake-card', null, [
                    'id'    => $cake['id'],
                    'title' => $cake['title'],
                    'link'  => $cake['link'],
                    'image' => $cake['image'],
                    'model' => $cake['model']
                ]); ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="swiper-pagination"></div>
    

</div>