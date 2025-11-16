<?php

class KBPSRecipeProductManager {
    
    public function __construct() {
        add_action('save_post_kbps_recipe', array($this, 'handle_recipe_save'), 20, 3);
        add_action('before_delete_post', array($this, 'delete_associated_product'));
    }

    public function handle_recipe_save($post_id, $post, $update) {
        if ($post->post_status !== 'publish' || wp_is_post_revision($post_id)) {
            return;
        }

        $existing_product_id = get_post_meta($post_id, '_associated_product_id', true);
        
        if (!$existing_product_id) {
            $this->create_product_from_recipe($post_id);
        } else {
            $this->update_product_from_recipe($post_id, $existing_product_id);
        }
    }

    private function create_product_from_recipe($recipe_id) {
        $recipe = get_post($recipe_id);
        
        $product = new WC_Product_Simple();
        $product->set_name($recipe->post_title);
        $product->set_description($recipe->post_content);
        $product->set_short_description(wp_trim_words($recipe->post_content, 50));
        $product->set_virtual(true);
        $product->set_downloadable(true);
        $product->set_slug('recipe-' . $recipe_id);
        $product->set_status('publish');
        
        $product->set_category_ids([$this->get_recipe_category_id()]);
        
        $product_id = $product->save();
        
        update_post_meta($recipe_id, '_associated_product_id', $product_id);
        update_post_meta($product_id, '_associated_recipe_id', $recipe_id);
        
        return $product_id;
    }

    private function get_recipe_category_id() {
        $category = get_term_by('slug', 'recipes-courses', 'product_cat');
        
        if (!$category) {
            $category = wp_insert_term(
                'Recipes & Courses',
                'product_cat',
                [
                    'slug' => 'recipes-courses',
                    'description' => 'Digital recipes and cooking courses'
                ]
            );
            return $category['term_id'];
        }
        
        return $category->term_id;
    }

    public function ensure_downloadable_files($product_id) {
        $product = wc_get_product($product_id);
        $recipe_id = get_post_meta($product_id, '_associated_recipe_id', true);
        
        if (!$recipe_id || !$product->is_downloadable()) {
            return;
        }

        $downloads = $product->get_downloads();
        
        if (empty($downloads)) {
            $file_url = get_post_meta($recipe_id, '_recipe_file_url', true);
            
            if ($file_url) {
                $download = new WC_Product_Download();
                $download->set_name('Recipe File');
                $download->set_id(wp_generate_uuid4());
                $download->set_file($file_url);
                
                $product->set_downloads([$download]);
                $product->save();
            }
        }
    }

    public function delete_associated_product($post_id) {
        if (get_post_type($post_id) !== 'kbps_recipe') {
            return;
        }

        $product_id = get_post_meta($post_id, '_associated_product_id', true);
        if ($product_id) {
            wp_delete_post($product_id, true);
        }
    }

    private function update_product_from_recipe($recipe_id, $product_id) {
        $recipe = get_post($recipe_id);
        $product = wc_get_product($product_id);
        
        if ($product) {
            $product->set_name($recipe->post_title);
            $product->set_description($recipe->post_content);
            $product->save();
            
            $this->ensure_downloadable_files($product_id);
        }
    }
}