<?php

class Meta_Box_Rate {
    public static function add_box() {
        add_meta_box( 'page_rate', __( 'Page Rate', 'dart-theme' ), array( 'Meta_Box_Rate', 'view' ), array( 'page' ), 'side', 'high' );
    }

    public static function view( $post ) {
        $value = get_post_meta( $post->ID, "page_rate", true );
        if( !$value )
            $value = -1;
        include( get_stylesheet_directory() . "/inc/meta-boxes/view/rate.php" );
    }

    public static function save( $postID ) {
        if( get_post_type( $postID )!=="page" )
            return;

        if( !empty( $_POST["page_rate"] ) ) {
            $values = array( -1, 1, 2, 3, 4, 5 );
            $value = intval( $_POST["page_rate"] );
            if( !in_array( $value, $values ) )
                $value = -1;
            update_post_meta( $postID, "page_rate", $value );
        }
    }
}