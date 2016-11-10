jQuery(function($){
    wp.customize( 'all_links_color', function( value ) {
        value.bind( function( to ) {
            $( 'a' ).css( 'color', to ); // ко всем ссылкам применяем заданный цвет
        } );
    });

    wp.customize( 'headers_h2_color', function( value ) {
        value.bind( function( to ) {
            $( 'h2' ).css( 'color', to ); // ко всем ссылкам применяем заданный цвет
        } );
    });
});