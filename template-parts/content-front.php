<section class="front-section">
    <div class="wedding-blocks-wrapper">
        <?php
        $front_cakes = KBPSCakePostManager::getFrontPageCakes();
        $block_texts = [
            'Svatební dort – nejen krásný a lahodný, ale také nádherná tradice, která se vyvíjela po staletí. Dort je nejen symbolem lásky, ale i velkolepým vyvrcholením svatby!',
            'Jaký je váš vysněný dort? Elegantní krémový, klasický fondánový, luxusní královský nebo trendovy dort s ovocem, s čerstvými nebo jedlými květinami?',
            'Každý svatební dort je unikátní dílo, které odráží osobnost novomanželů. Společně vytvoříme přesně takový dort, jaký si představujete pro svůj velký den.',
            'Naše dorty kombinují tradiční řemeslné postupy s moderními designovými trendy. Používáme pouze ty nejkvalitnější suroviny od ověřených dodavatelů.'
        ];
        
        for ($i = 0; $i < 4; $i++) : 
            $block_num = $i + 1;
            $cake = $front_cakes[$i] ?? null;
        ?>
            <div class="wedding-block block-<?php echo $block_num; ?>">
                <?php if ($cake) : ?>
                    <div class="wedding-block__big-photo-wrapper block-<?php echo $block_num; ?>">
                        <?php KBPSCakePostManager::getCakePostImageAndLink($cake); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($block_num == 1) : ?>
                    <div class="wedding-block__small-photo-wrapper block-<?php echo $block_num; ?>">
                        <?php KBPSStaticPhotoManager::getImage('wedding_01_01.jpg'); ?>
                    </div>
                <?php elseif ($block_num == 2) : ?>
                    <div class="wedding-block__small-photo-wrapper block-<?php echo $block_num; ?> photo-1">
                        <?php KBPSStaticPhotoManager::getImage('wedding_02_01.jpg'); ?>
                    </div>
                    <div class="wedding-block__small-photo-wrapper block-<?php echo $block_num; ?> photo-2">
                        <?php KBPSStaticPhotoManager::getImage('wedding_03_01.jpg'); ?>
                    </div>
                <?php else : ?>
                    <div class="wedding-block__small-photo-wrapper block-<?php echo $block_num; ?>">
                        <?php KBPSStaticPhotoManager::getImage('wedding_04_01.jpg'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="wedding-block__text-wrapper block-<?php echo $block_num; ?>">
                    <p class="wedding-block__text block-<?php echo $block_num; ?>">
                        <?php echo $block_texts[$i]; ?>
                    </p>
                </div>
            </div>
        <?php endfor; ?>
    </div>




    </div> <!-- wedding-block-wrapper -->

<?php //SLIDER
    $cakes = KBPSCakePostManager::getCakesForSlider([
        'posts_per_page' => 6,
        'order' => 'ASC'
    ]);

    get_template_part('template-parts/components/cake-slider', null, [
        'cakes'    => $cakes,
        'autoplay' => true
    ]);
?>


</section>
