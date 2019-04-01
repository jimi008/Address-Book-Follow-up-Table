<?php
/*
Plugin Name:  Mooveat Address Book
Plugin URI:   https://mooveat.io
Description:  Manage and modify address book with help of ACF
Version:      1.0.0
Author:       Laurent Nicolas & Zahir Ahmed
Author URI:   http://mooveat.io
License:      GPLv3
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

$request_uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$request_query_string = $_SERVER['QUERY_STRING'];
$is_mv_address_book_page = strpos( $request_query_string, 'post_type=mv_address_book' )!==false;
$is_organisation_page = strpos( $request_query_string, 'post_type=organisation' )!==false;
$is_admin = strpos( $request_uri, '/wp-admin/' )!==false;

require_once plugin_dir_path( __FILE__ ) . 'includes/register-cpt-address-book.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/register-cpt-organisation.php';

if($is_admin){
    require_once plugin_dir_path( __FILE__ ) . 'includes/register-admin-columns.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-mooveat-address-book.php';

    run_mooveat_address_book();

}

function run_mooveat_address_book() {

    $spmm = new Mooveat_Address_Book();
    $spmm->run();

}

function my_restrict_manage_posts($post_type) {

    global $post_type;

    if('mv_address_book' !== $post_type){
        return; //check to make sure this is your cpt
    }

//    $selected = '';
//    $request_attr = 'mv_cpo';
//    if ( isset($_REQUEST[$request_attr] ) ) {
//        $selected = $_REQUEST[$request_attr];
//    }


    $allcpo = $allcso = $allcto = $allcsg = $labels = $alllab = $allcpg
        = $all_restoco_types = $all_types_cuisine = $all_ech_admin = $all_types_profil
        = $allcl4 = $allcl5 = $allcl6 = $all_stade_abo
        =  array();

    $args = array(
        'numberposts'	=> -1,
        'post_type'		=> 'mv_address_book'
    );

    function valuelabel_field_options_init($acf_grp,$acf_field){ // for select multiple fields
        $all_options = array();
        $grp_field = get_field($acf_grp);
        $field_val = $grp_field[$acf_field];

        /*if($acf_grp == 'type_profil_grp'){
            error_log(print_r($field_val,true));
        }*/

        if(!empty($field_val) && is_array($field_val)){
            foreach ($field_val as $field_val_item){
                $all_options[$field_val_item['label']] = $field_val_item['value'];
            }
        }
        //error_log(print_r($grp_field,true));
        return $all_options;
    }

    function valuelabel_single_field_options_init($acf_grp,$acf_field){ // for select single fields
        $all_options = array();
        $grp_field = get_field($acf_grp);
        $field_val = $grp_field[$acf_field];

        /*if($acf_grp == 'type_profil_grp'){
            error_log(print_r($field_val,true));
        }*/

        if(!empty($field_val) && !empty($field_val['value'])){
            $all_options[$field_val['label']] = $field_val['value'];
        }
        //error_log(print_r($grp_field,true));
        return $all_options;
    }



    function procat_field_options_init($acf_grp,$acf_field){
        $all_options = array();
        $acf_group = get_field($acf_grp);
        $acf_field = $acf_group[$acf_field];

        if (!empty($acf_field)) {
            $acf_field_term = get_term($acf_field['value'], 'nomenclature_beta' );
            if(!empty($acf_field_term)){
                $term_label = $acf_field_term->name;
                $all_options[ $term_label ] = $acf_field['value'];
            }
        }
        return $all_options;
    }

    $contacts_query = new WP_Query( $args );
    $contacts_query->set('meta_query',array());

    if ( $contacts_query->have_posts() ) {
        while ($contacts_query->have_posts()) {
            $contacts_query->the_post();

            $allcpo = array_merge($allcpo, procat_field_options_init('mv_cat_orga_grp','categorie_principale_organisation'));
            $allcso = array_merge($allcso, procat_field_options_init('mv_cat_orga_grp','categorie_secondaire_organisation'));
            $allcto = array_merge($allcto, procat_field_options_init('mv_cat_orga_grp','categorie_tertiaire_organisation'));
            $allcl4 = array_merge($allcl4, procat_field_options_init('mv_cat_orga_grp','cat_pro_level_4'));
            $allcl5 = array_merge($allcl5, procat_field_options_init('mv_cat_orga_grp','cat_pro_level_5'));
            $allcl6 = array_merge($allcl6, procat_field_options_init('mv_orga_name_grp','cat_pro_level_6'));

            $cpg_group = get_field('mv_ab_tags_grp');
            $cpg = $cpg_group['categories_produits'];
            if (!empty($cpg)) {
                foreach ($cpg as $cpg_item) {
                    $cpg_item_id = intval($cpg_item['value']);
                    if($cpg_item_id>0){
                        $c_cpg = get_term($cpg_item_id, 'nomenclature_beta' );
                        $cpg_label = $c_cpg->name;
                        if(!empty($cpg_label)){
                            $allcpg[ $cpg_label ] = $cpg_item['value'];
                        }
                    }
                }
            }

            $lab_group = get_field('mv_ab_tags_grp');
            $lab = $lab_group['label_taxonomy'];
            if (!empty($lab)) {
                foreach ($lab as $lab_item){
                    if(is_integer($lab_item->term_id)){
                        $alllab[ $lab_item->name ] = $lab_item->term_id;
                    }
                }
            }

            $all_restoco_types = array_merge($all_restoco_types,valuelabel_field_options_init('mv_ab_tags_grp','type_restauration_collective'));
            $all_types_cuisine = array_merge($all_types_cuisine,valuelabel_field_options_init('mv_ab_tags_grp','type_cuisine'));
            $all_ech_admin = array_merge($all_ech_admin,valuelabel_field_options_init('mv_ab_tags_grp','echelle_administration'));


            $all_types_profil = array_merge($all_types_profil,valuelabel_single_field_options_init('type_profil_grp','type_profil_personne_organisation'));
            $all_stade_abo = array_merge($all_stade_abo,valuelabel_single_field_options_init('mv_ab_tags_grp','fonction_dans_la_filiere'));


            /*$type_restoco_group = get_field('mv_ab_tags_grp');
            //error_log(print_r($type_restoco_group,true));
            $type_restoco = $type_restoco_group['type_restauration_collective'];
            if(!empty($type_restoco)){
                foreach ($type_restoco as $type_restoco_item){
                    $all_restoco_types[$type_restoco_item['label']] = $type_restoco_item['value'];
                }
            }

            $cpg_group = get_field('mv_ab_tags_grp');
            $cpg = $cpg_group['categories_produits'];
            if (!empty($cpg)) {
                foreach ($cpg as $cpg_single) {
                    $allcsg[ $cpg_single ] = $cpg_single;
                }
            }

            $label_group = get_field('label_grp');
            $label = $label_group['label'];
            if (!empty($label)) {
                foreach ($label as $label_single) {
                    $labels[ $label_single ] = $label_single;
                }
            }

            $key = get_post_meta( get_the_ID(), 'cp_values', true );
            var_dump($key);
            */

        }
    }

        /* Restore original Post Data */
        wp_reset_postdata();


    function set_select2_field($select_name, $options_array, $title){
        echo '<label for="' . $select_name . '" class="screen-reader-text">' . $title . '</label>';
        echo '<select name="' . $select_name . '[]" id="' . $select_name . '" class="multi-select" style="width:140px;" multiple placeholder="'. $title . '">';

        $current_s = isset($_GET[$select_name])? $_GET[$select_name]:'';
        if ($current_s) {
            foreach ( $current_s as $key => $value ){
                $current_s[] = $value;
            }
        }

        foreach ($options_array as $label => $value) {
            if (is_array($current_s)) {
                $current_selected = false;
                if (in_array($value, $current_s)) {
                    $current_selected = true;
                }
            }
            else{
                $current_selected = false;
            }
            $selected = $current_selected ? 'selected="selected"' : "";
            if($label!='-'):
                echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
            endif;
        }

        echo '</select>';
    }

    set_select2_field('mv_cpo', $allcpo, 'Catégorie niveau 1');
    set_select2_field('mv_cso', $allcso, 'Catégorie niveau 2');
    set_select2_field('mv_cto', $allcto, 'Catégorie niveau 3');
    set_select2_field('mv_cl4', $allcl4, 'Catégorie niveau 4');
    set_select2_field('mv_cl5', $allcl5, 'Catégorie niveau 5');
    set_select2_field('mv_cl6', $allcl6, 'Organisation (cat. niv. 6)');

    set_select2_field('mv_cpg', $allcpg, 'Catégorie produit');
    set_select2_field('mv_label', $alllab, 'Label');

    set_select2_field('mv_restoco', $all_restoco_types, 'Type restau co.');
    set_select2_field('mv_type_cuisine', $all_types_cuisine, 'Type cuisine');
    set_select2_field('mv_ech_admin', $all_ech_admin, 'Échelle admin');
    set_select2_field('mv_type_profil', $all_types_profil, 'Profil');
    set_select2_field('mv_stade_abo', $all_stade_abo, 'Stade abonnement');

}
add_action('restrict_manage_posts','my_restrict_manage_posts');

function valuelabel_field_query_builder($get_var,$ac_grp){
    $mv_getvar = isset( $_GET[$get_var] ) ? $_GET[$get_var] : '';

    $meta_query = array();
    if( !empty( $mv_getvar ) && isset($mv_getvar) ) {
        $meta_query = array('relation' => 'OR');
        foreach ($mv_getvar as $mv_item){
            array_push($meta_query, array(
                'key'		=> $ac_grp,
                'value'		=> '"'.$mv_item.'"',
                'compare'	=> 'LIKE',
            ));
        }
    }
    return $meta_query;
}

function procat_field_query_builder($get_var,$ac_grp){
    $mv_cat = isset( $_GET[$get_var] ) ? $_GET[$get_var] : '';

    $meta_query = array();
    if( !empty( $mv_cat ) && isset($mv_cat) ) {
        $meta_query = array('relation' => 'OR');
        array_push($meta_query, array(
            'key' => $ac_grp,
            'value' => $mv_cat,
            'compare' => 'IN'
        ));
    }
    return $meta_query;
}

/** if submitted filter by post meta */
//add_filter( 'parse_query', 'wpse45436_posts_filter' );
add_filter( 'pre_get_posts', 'address_book_posts_filter' );

function address_book_posts_filter( $query ){

    if ( ! is_admin() ) {
        return;
    }

    global $pagenow; $type = '';

    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }

    if ( 'mv_address_book' == $type && is_admin()  && $pagenow=='edit.php' && $query->is_main_query() ) {

        $main_meta_query = array('relation' => 'AND');

        //$qv = &$query->query_vars;
        //$qv['meta_query'] = array();

        array_push($main_meta_query,procat_field_query_builder('mv_cpo','mv_cat_orga_grp_categorie_principale_organisation'));
        array_push($main_meta_query,procat_field_query_builder('mv_cso','mv_cat_orga_grp_categorie_secondaire_organisation'));
        array_push($main_meta_query,procat_field_query_builder('mv_cto','mv_cat_orga_grp_categorie_tertiaire_organisation'));

        array_push($main_meta_query,procat_field_query_builder('mv_cl4','mv_cat_orga_grp_cat_pro_level_4'));
        array_push($main_meta_query,procat_field_query_builder('mv_cl5','mv_cat_orga_grp_cat_pro_level_5'));
        array_push($main_meta_query,procat_field_query_builder('mv_cl6','mv_orga_name_grp_cat_pro_level_6'));

        /*$mv_cpo = isset( $_GET['mv_cpo'] ) ? $_GET['mv_cpo'] : '';
        if( !empty( $mv_cpo ) && isset($mv_cpo) ) {
            $qv['meta_query'][] = array(
                'key' => 'mv_cat_orga_grp_categorie_principale_organisation',
                'value' => $mv_cpo,
                'compare' => 'IN'
            );
        }*/

        $mv_cpg = isset( $_GET['mv_cpg'] ) ? $_GET['mv_cpg'] : '';
        if( !empty( $mv_cpg ) && isset($mv_cpg) ) {
            $meta_query_cpg = array('relation' => 'OR');
            foreach ($mv_cpg as $mv_cpg_item){
                array_push($meta_query_cpg, array(
                    'key'		=> 'mv_ab_tags_grp_categories_produits',
                    'value'		=> '"'.$mv_cpg_item.'"',
                    'compare'	=> 'LIKE',

                ));
            }
            array_push($main_meta_query,$meta_query_cpg);
        }


        $mv_label = isset( $_GET['mv_label'] ) ? $_GET['mv_label'] : '';
        if( !empty( $mv_label ) && isset($mv_label) ) {
            $meta_query_label = array('relation' => 'OR');
            foreach ($mv_label as $mv_label_item){
                array_push($meta_query_label, array(
                    'key' => 'mv_ab_tags_grp_label_taxonomy',
                    'value' => '"'.$mv_label_item.'"',
                    'compare' => 'LIKE'
                ));
            }
            array_push($main_meta_query,$meta_query_label);
        }

        /*$mv_restco = isset( $_GET['mv_restoco'] ) ? $_GET['mv_restoco'] : '';
        if( !empty( $mv_restco ) && isset($mv_restco) ) {
            foreach ($mv_restco as $mv_restco_item){
                $qv['meta_query'][] = array(
                    'key'		=> 'mv_ab_tags_grp_type_restauration_collective',
                    'value'		=> '"'.$mv_restco_item.'"',
                    'compare'	=> 'LIKE',

                );
            }
        }*/

        array_push($main_meta_query,valuelabel_field_query_builder('mv_restoco','mv_ab_tags_grp_type_restauration_collective'));
        array_push($main_meta_query,valuelabel_field_query_builder('mv_type_cuisine','mv_ab_tags_grp_type_cuisine'));
        array_push($main_meta_query,valuelabel_field_query_builder('mv_ech_admin','mv_ab_tags_grp_echelle_administration'));
        array_push($main_meta_query,valuelabel_field_query_builder('mv_type_profil','type_profil_grp_type_profil_personne_organisation'));
        array_push($main_meta_query,valuelabel_field_query_builder('mv_stade_abo','v_ab_tags_grp_fonction_dans_la_filiere'));

        array_push($main_meta_query, $query->get('meta_query'));

        $query->set('meta_query',$main_meta_query);

        //echo '<div style="position: fixed; background: #fff; padding: 20px; overflow:scroll; height: 500px;"><div><pre>';
        //print_r($query);
        //echo '</pre></div></div>';

    }
}



function convert_acf_multiselect_to_standard_wp($post_id) {

    if (get_post_type() != 'mv_address_book') {
        return;
    }
        // use a different field name for your converted value
        $cp_meta_key = 'cp_values';
        $label_meta_key = 'label_values';
        // clear any previously stored values
        delete_post_meta($post_id, $cp_meta_key);
        delete_post_meta($post_id, $label_meta_key);
        // get new acf value

        $cp_group = get_field('mv_ab_tags_grp', $post_id);
        $cp_values = $cp_group['categories_produits'];


        if (is_array($cp_values) && count($cp_values)) {
            foreach ($cp_values as $value) {
                add_post_meta($post_id, $cp_meta_key, $value, false);
            }
        }

        $label_group = get_field('label_grp', $post_id);
        $label_values = $label_group['label'];

        if (is_array($label_values) && count($label_values)) {
            foreach ($label_values as $value) {
                add_post_meta($post_id, $label_meta_key, $value, false);
            }
        } // end if

}
add_filter('acf/save_post', 'convert_acf_multiselect_to_standard_wp', 20);

function cptui_register_my_taxes_nomenclature_beta() {

    /**
     * Taxonomy: Beta Nomenclature items.
     */

    $labels = array(
        "name" => __( "Beta Nomenclature items", "mooveat" ),
        "singular_name" => __( "Beta Nomenclature item", "mooveat" ),
    );

    $args = array(
        "label" => __( "Beta Nomenclature items", "mooveat" ),
        "labels" => $labels,
        "public" => true,
        "hierarchical" => true,
        "label" => "Beta Nomenclature items",
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => array( 'slug' => 'nomenclature_beta', 'with_front' => true,  'hierarchical' => true, ),
        "show_admin_column" => true,
        "show_in_rest" => true,
        "rest_base" => "nomenclature_beta",
        "show_in_quick_edit" => false,
    );
    register_taxonomy( "nomenclature_beta", array( "cpt_mooveat_client","mve_produit_alim" ), $args );
}

$url = site_url();

// Register taxonomy only in localhost
if ($url == 'http://mooveat.div') {
    add_action( 'init', 'cptui_register_my_taxes_nomenclature_beta' );
}



/**
 * Hide empty option from filter menu
 *
 * @param array     $args
 * @param AC_Column $column
 *
 * @return array
 */
function my_ac_filter_menu_remove_empty_options( $args, $column ) {

    $column_type = 'column-meta';

    if ( $column_type === $column->get_type() ) {

        if ( isset( $args['empty_option'] ) ) {
            unset( $args['empty_option'] );
        }
    }

    return $args;
}

add_filter( 'acp/filtering/dropdown_args', 'my_ac_filter_menu_remove_empty_options', 10, 2 );

function register_follow_up_table_page() {
    add_submenu_page(
        'edit.php?post_type=mv_address_book',
        __( 'Tableau de suivi', 'follow-up-table' ),
        __( 'Tableau de suivi', 'follow-up-table' ),
        'manage_options',
        'mv_address_book_org_follow_up_table',
        'follow_up_table'
    );

    function follow_up_table() {
        ob_start();
        include_once plugin_dir_path( __FILE__  ) . 'admin/follow_up_table_page.php';

        $template = ob_get_contents();
        ob_clean();
        echo $template;
    }
}
add_action( 'admin_menu', 'register_follow_up_table_page' );


