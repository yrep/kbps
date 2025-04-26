<section class="front-section">
    <div class="wedding-blocks-wrapper">
        <div class="wedding-block block-1">
            <div class="wedding-block__big-photo-wrapper block-1">
            <?php
            KBPSCakePostManager::getCakePostImageAndLinkByModel(1);
            ?>
            </div>
            <div class="wedding-block__small-photo-wrapper block-1">
            <?php
            KBPSStaticPhotoManager::getImage('wedding-photo-01');
            ?>
            </div>
            <div class="wedding-block__text-wrapper block-1">
                <p class="wedding-block__text block-1">
                Svatební dort – nejen krásný a lahodný, ale také nádherná tradice, která se vyvíjela po staletí. Dort je nejen symbolem lásky, ale i velkolepým vyvrcholením svatby!
                </p>
            </div>
        </div>
        <div class="wedding-block block-2">
            <div class="wedding-block__big-photo-wrapper block-2">
            <?php
            KBPSCakePostManager::getCakePostImageAndLinkByModel(2);
            ?>
            </div>
            <div class="wedding-block__small-photo-wrapper block-2 photo-1">
            <?php
            KBPSStaticPhotoManager::getImage('wedding-photo-01');
            ?>
            </div>
            <div class="wedding-block__small-photo-wrapper block-2 photo-2">
            <?php
            KBPSStaticPhotoManager::getImage('wedding-photo-01');
            ?>
            </div>
            <div class="wedding-block__text-wrapper block-2">
                <p class="wedding-block__text block-2">
                Jaký je váš vysněný dort? Elegantní krémový, klasický fondánový, luxusní královský nebo trendovy dort s ovocem, s čerstvými nebo jedlými květinami?
                </p>
            </div>
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
