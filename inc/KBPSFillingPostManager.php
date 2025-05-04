<?php

if ( ! class_exists( 'KBPSFillingPostManager' ) ) {
    class KBPSFillingPostManager {

        /**
         * Get Filling by ID.
         *
         * @param int $filling_id ID filling.
         * @return array|false Filling data or false.
         */
        public static function getFillingDataById( $filling_id ) {
            $filling_id = absint( $filling_id );

            if ( ! $filling_id ) {
                return false;
            }

            $filling_post = get_post( $filling_id );

            if ( ! $filling_post || $filling_post->post_type !== 'filling' || $filling_post->post_status !== 'publish' ) {
                return false;
            }

            $full_image_url = has_post_thumbnail( $filling_id )
                ? get_the_post_thumbnail_url( $filling_id, 'full' )
                : null;

            $description = apply_filters( 'the_content', $filling_post->post_content );

            return [
                'id'             => $filling_id,
                'title'          => $filling_post->post_title,
                'full_image_url' => $full_image_url,
                'description'    => $description,
                // 'details'     => get_post_meta( $filling_id, 'kbps_filling_details', true ),
            ];
        }
    }
}
