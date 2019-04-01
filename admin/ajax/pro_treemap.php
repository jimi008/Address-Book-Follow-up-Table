<?php


add_action( 'admin_enqueue_scripts','pro_treemap_enqueue');
function pro_treemap_enqueue()
{
    $screen = get_current_screen();
    if ('mv_address_book' === $screen->post_type || 'organisation' === $screen->post_type) {

        wp_localize_script(
            'pro_treemap_script',
            'pro_treemap_ajax_var',
            array(
                'ajaxurl'      => admin_url( 'admin-ajax.php' ),
                'ajaxnonce'   => wp_create_nonce( 'pro_treemap_security' ),
            )
        );
    }
}


add_action( 'wp_ajax_display_pro_treemap', 'display_pro_treemap' );

function display_pro_treemap() {

    check_ajax_referer( 'pro_treemap_security', 'nonce_data' );

    $personnes_organisations_id = 8502;

    $list_cat_param = array(
        'taxonomy' => 'nomenclature_beta',
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
        'child_of' => $personnes_organisations_id,
        'hierarchical' => true,
        'show_count' => true
    );

    wp_list_categories($list_cat_param);

    die();
}