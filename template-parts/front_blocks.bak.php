<div class="wedding-block block-<?php echo $block_num; ?>">
                <div class="wedding-block__big-photo-wrapper block-<?php echo $block_num; ?>">
                    <?php KBPSCakePostManager::getCakePostImageAndLink($cake); ?>
                </div>



            <div>
            
            
            
            
            
            
            
            
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