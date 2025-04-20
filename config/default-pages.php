<?php
/**
 * Default Pages setup
 * 
 * Format:
 * 'key' => [
 *     'title'    => 'Page Title',
 *     'slug'     => 'page-slug',
 *     'template' => 'template-name',
 *     'content'  => '',
 *     'status'   => 'publish',
 *     'parent'   => 0
 * ]
 */

return [
    'wedding_cakes' => [
        'title' => 'Svatební dorty',
        'slug' => 'svatebni-dorty',
        'template' => 'page-wedding-cakes.php',
    ],
    'christmas_offer' => [
        'title' => 'Speciální nabídka',
        'slug' => 'specialni-nabidka',
        'template' => 'page.php',
    ],
    'cakes' => [
    'title' => 'Dorty',
    'slug' => 'dorty',
    'type' => 'page',
    'template' => 'template-cakes.php',
    'content' => ''
    ],
    /*
    'tasting_box' => [
        'title' => 'Degustační box',
        'slug' => 'degustacni-box',
    ],
    */
    'information' => [
        'title' => 'Informace',
        'slug' => 'informace',
        'template' => 'page-information.php',
    ],
    'shop' => [
    'title' => 'Cukrárna',
    'slug' => 'cukrarna',
    'is_woocommerce_shop' => true,
    'status' => 'publish',
    'content' => '' // Auto woocommerce content
    ]
];