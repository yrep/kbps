<?php
/**
 * KBPS Page Manager
 * Management utility class for pages
 */
class KBPSPageManager {
    /**
     * Get page by slug
     * @param string $slug Page slug/URL path
     * @return WP_Post|null Post object if found, null otherwise
     */
    public static function getPageBySlug($slug) {
        return get_page_by_path($slug, OBJECT, 'page');
    }

    /**
     * Get page by ID
     * @param int $id Page ID
     * @return WP_Post|null Post object if found, null otherwise
     */
    public static function getPageById($id) {
        return get_post($id);
    }

    /**
     * Get page by title
     * @param string $title Page title
     * @return WP_Post|null Post object if found, null otherwise
     */
    public static function getPageByTitle($title) {
        return get_page_by_title($title, OBJECT, 'page');
    }

    /**
     * Create a new page
     * @param array $args {
     *     @type string $title Page title (required)
     *     @type string $slug Page slug (optional)
     *     @type string $content Page content (optional)
     *     @type string $template Page template file (optional)
     *     @type string $status Post status (default: publish)
     *     @type int $parent Parent page ID (optional)
     * }
     * @return int|WP_Error New page ID on success, WP_Error on failure
     */
    public static function addPage($args) {
        $defaults = [
            'title' => '',
            'slug' => '',
            'content' => '',
            'template' => '',
            'status' => 'publish',
            'parent' => 0
        ];
        $args = wp_parse_args($args, $defaults);

        if (empty($args['title'])) {
            return new WP_Error('missing_title', __('Page title is required', 'text-domain'));
        }

        if ($args['slug'] && self::getPageBySlug($args['slug'])) {
            return new WP_Error('page_exists', __('Page with this slug already exists', 'text-domain'));
        }

        if (self::getPageByTitle($args['title'])) {
            return new WP_Error('page_exists', __('Page with this title already exists', 'text-domain'));
        }

        $page_data = [
            'post_title' => $args['title'],
            'post_name' => $args['slug'],
            'post_content' => $args['content'],
            'post_status' => $args['status'],
            'post_type' => 'page',
            'post_parent' => $args['parent']
        ];

        $page_id = wp_insert_post($page_data, true);

        if (!is_wp_error($page_id) && $args['template']) {
            update_post_meta($page_id, '_wp_page_template', $args['template']);
        }

        return $page_id;
    }

    /**
     * Delete a page
     * @param int $page_id ID of page to delete
     * @return bool|WP_Error True on success, false/WP_Error on failure
     */
    public static function deletePage($page_id) {
        if (!self::getPageById($page_id)) {
            return new WP_Error('page_not_found', __('Page not found', 'text-domain'));
        }
        return wp_delete_post($page_id, true);
    }

    /**
     * Clear page content
     * @param int $page_id ID of page to clear
     * @return bool|int|WP_Error 1 on success, 0/WP_Error on failure
     */
    public static function clearPageContent($page_id) {
        $page = self::getPageById($page_id);
        if (!$page) {
            return new WP_Error('page_not_found', __('Page not found', 'text-domain'));
        }

        return wp_update_post([
            'ID' => $page_id,
            'post_content' => ''
        ]);
    }
}