<?php

class Mooveat_Address_Book_Admin
{

    private $version;
    protected $personnes_organisations_id;

    public function __construct($version)
    {
        $this->version = $version;
        $this->personnes_organisations_id = 8502;

        add_action('do_meta_boxes' , array( $this, 'mv_ab_remove_meta_boxes' ));
        add_filter('screen_layout_columns', array( $this, 'two_screen_layout_columns' ));
        add_filter('get_user_option_screen_layout_mv_address_book', array( $this, 'two_screen_layout_post' ));
        add_filter('post_row_actions', array( $this, 'mv_hide_quick_edit'), 10, 2);
        add_action('add_meta_boxes', array( $this, 'mv_ab_remove_meta_boxes'), 100);
        //add_action('add_meta_boxes', array( $this, 'mv_add_pro_treemap'), 100);
        add_action('admin_menu', array( $this, 'mv_address_book_replace_submit_meta_box' ));
        add_filter('post_updated_messages', array( $this,  'mv_address_book_cpt_messages' ));
        add_action('current_screen', array( $this, 'wpse151723_remove_yoast_seo_posts_filter'), 20 );
        add_filter( 'admin_body_class', array( $this,'acp_overflow_table_class'), 10 );
        add_action('acf/save_post', array($this,'ab_acf_save_post'), 16);
        add_filter('acf/validate_value/name=organisation_name_to_be_added', array( $this, 'org_acf_validate_value'), 10, 4);
        //add_action( 'current_screen', 'wpse151723_remove_yoast_seo_posts_filter', 20 );
        //add_filter('acf/load_value/name=organisation_name_to_be_added', 'org_acf_reset_value', 10, 3);

    }
    // Not possible by using some hook. it will always show horizontal scroll.
    function acp_overflow_table_class( $classes ) {
        $classes .= ' acp-overflow-table ';
        return $classes;
    }

    /**
     * Admin styles & Scripts
     *
     * @return void
     */
    public function admin_scripts($hook)
    {

        if ($hook  == 'edit.php' ) {
            wp_register_style('select2css', '//cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.css', false, '1.0', 'all');
            wp_register_script('select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.js', array('jquery'), '1.0', true);
            wp_enqueue_style('select2css');
            wp_enqueue_script('select2');
        }

        if ($hook != 'post-new.php' && $hook != 'edit.php' && $hook != 'post.php') {
            return;
        }


        $screen = get_current_screen();

        if ('mv_address_book' === $screen->post_type) {
            wp_enqueue_script('mvab-admin-script', plugin_dir_url( __FILE__ ) . "js/mvab-admin.js", array('jquery'), $this->version);
            wp_enqueue_style('mvab-admin-style', plugin_dir_url( __FILE__ ) . "css/mvab-admin.css", array(), $this->version);
            wp_dequeue_script('autosave');
            wp_deregister_script('postbox');

            //For calling ajax to populate categories select field
            wp_localize_script('mvab-admin-script', 'catpro_vars', array(
                'catpro_nonce' => wp_create_nonce('catpro_nonce'), // Create nonce which we later will use to verify AJAX request
            ));

        }

        if ('organisation' === $screen->post_type) {
            wp_enqueue_style('mvab-admin-style', plugin_dir_url( __FILE__ ) . "css/mvab-admin.css", array(), $this->version);
        }

    }


    //	remove following meta boxes from mv_address_book post_type
    public function mv_ab_remove_meta_boxes()
    {
        remove_meta_box('wpseo_meta', 'mv_address_book', 'normal');
        remove_meta_box('nomenclature_betadiv', 'mv_address_book', 'normal');
        remove_meta_box('nomenclature_betadiv', 'mv_address_book', 'side');
        remove_meta_box('nomenclature_betadiv', 'mv_address_book', 'advanced');
        remove_meta_box('slugdiv', 'organisation', 'normal');
    }

    public function two_screen_layout_columns($columns)
    {
        $columns['post'] = 1;
        return $columns;
    }

    public function two_screen_layout_post()
    {
        return 1;
    }


    /**
     * Sets the post title for address book post type
     */
    function mv_contact_name_as_post_title( $post_id )
    {

        if (!(wp_is_post_revision($post_id) || wp_is_post_autosave($post_id))) {
            return;
        }

        $contact_group = get_field('contact', $post_id);

        $firstName = $contact_group['first_name'];
        $nom = $contact_group['nom'];
        $fullName = $firstName . ' ' . $nom;
//    $slug = sanitize_title( $fullName );

        $content = array(
            'ID' => $post_id,
            'post_title' => $fullName,
            'post_name' => sanitize_title($fullName),
            'post_status' => 'publish'
        );

        remove_action('save_post_mv_address_book', array( $this, 'mv_contact_name_as_post_title') );
        wp_update_post($content);
        add_action('save_post_mv_address_book', array( $this, 'mv_contact_name_as_post_title') );
    }

    /**
     * Sets the post title for organisation post type
     */
    function set_organisation_post_title( $post_id )
    {

        if (!(wp_is_post_revision($post_id) || wp_is_post_autosave($post_id))) {
            return;
        }

        $orga_name = get_field('nomenclature_link',$post_id)->name;

        if(empty($orga_name)){
           $orga_name = get_field('mv_orga_organisation_name_to_be_added',$post_id);
        }


        $content = array(
            'ID' => $post_id,
            'post_title' => $orga_name,
            'post_name' => sanitize_title($orga_name),
            'post_status' => 'publish'
        );

        remove_action('save_post_organisation', array( $this, 'set_organisation_post_title') );
        wp_update_post($content);
        add_action('save_post_organisation', array( $this, 'set_organisation_post_title') );
    }



    /**
     * Hide quick edit
     * @internal  Used as a callback.
     * @see  https://developer.wordpress.org/reference/hooks/post_row_actions/
     */

    public function mv_hide_quick_edit($actions, $post)
    {

        if ('mv_address_book' === $post->post_type) {
            unset($actions['inline hide-if-no-js']);
        }

        return $actions;
    }


    /*public function mv_remove_wp_seo_meta_box()
    {
        remove_meta_box('wpseo_meta', 'mv_address_book', 'normal');
    }*/


    function wpse151723_remove_yoast_seo_posts_filter() {

        $screen = get_current_screen();

        global $wpseo_meta_columns;

        if ('mv_address_book' === $screen->post_type || 'organisation' === $screen->post_type) {

            if ($wpseo_meta_columns) {
                remove_action('restrict_manage_posts', array($wpseo_meta_columns, 'posts_filter_dropdown'));
                remove_action('restrict_manage_posts', array($wpseo_meta_columns, 'posts_filter_dropdown_readability'));

            }

            add_filter('months_dropdown_results', '__return_empty_array');
        }
    }

    public function my_manage_columns($columns)
    {
        unset($columns['wpseo-score']);
        unset($columns['wpseo-score-readability']);
        unset($columns['wpseo-title']);
        unset($columns['wpseo-metadesc']);
        unset($columns['wpseo-focuskw']);
        unset($columns['wpseo-links']);
        unset($columns['wpseo-linked']);
        return $columns;
    }

    public function remove_columns_init()
    {
        add_filter('manage_mv_address_book_posts_columns', array( $this, 'my_manage_columns'), 20, 2);

    }



    /**
     * Loop throught custom post types and
     * replace default submit box
     *
     * @since  1.0
     *
     */
    function mv_address_book_replace_submit_meta_box()
    {
        // create a multidimensional array that will hold
        // each custom post_type as a key, and custom
        // post_type name will be it's value.
        $items = array(
            'mv_address_book' => 'Contact'

        );

        // now loop through $items array and remove, then
        // add submit meta box for each post type, by using
        // values from array to complete this.
        foreach ($items as $item => $value) {
            remove_meta_box('submitdiv', $item, 'core'); // $item represents post_type
            add_meta_box('submitdiv', sprintf(__('Enregistrer/Mettre à jour le %s'), $value), array( $this, 'mv_address_book_submit_meta_box'), $item, 'side', 'low'); // $value will be the output title in the box
        }
    }


    /**
     * Custom edit of default wordpress publish box callback
     * loop through each custom post type and remove default
     * submit box, replacing it with custom one that has
     * only submit button with custom text on it (add/update)
     *
     * @global $action , $post
     * @see wordpress/includes/metaboxes.php
     * @since  1.0
     *
     */
    function mv_address_book_submit_meta_box()
    {
        global $action, $post;

        $post_type = $post->post_type; // get current post_type
        $post_type_object = get_post_type_object($post_type);
        $can_publish = current_user_can($post_type_object->cap->publish_posts);
        // again, use the same array. It is important
        // to put it in same order, so that it can
        // follow the right meta box
        $items = array(
            'mv_address_book' => 'Address Book'
        );
        // now create var $item that will take only right
        // post_type information for currently displayed
        // post_type. Because $post_type var will store
        // only current post_type, it will correspond to
        // the appropriate 'key' from the $items array.
        // This $item will hold only the string name of
        // the post_type which will be used further in context
        // on appropriate places.
        $item = $items[$post_type];

        echo '<div class="submitbox" id="submitpost">
            <div id="major-publishing-actions">';

                do_action('post_submitbox_start');

                echo '<div id="delete-action">';

                    if (current_user_can("delete_post", $post->ID)) {
                        if (!EMPTY_TRASH_DAYS)
                            $delete_text = __('Delete Permanently');
                        else
                            $delete_text = __('Move to Trash');

                        echo '<a class="submitdelete deletion" href="' . get_delete_post_link($post->ID) . '">'. $delete_text . '</a>';

                    } //if

                echo '</div><div id="publishing-action"><span class="spinner"></span>';

                    if ( !in_array($post->post_status, array('publish', 'future', 'private')) || 0 == $post->ID ) {

                        if ( $can_publish ) {

                            echo '<input name="original_publish" type="hidden" id="original_publish" value="Save Contact"/>';
                            submit_button(sprintf(__('Enregistrer le contact %'), $item), 'primary button-large', 'publish', false, array('accesskey' => 'p'));

                        }
                    } else {
                        echo '<input name="original_publish" type="hidden" id="original_publish"
                               value="Save Contact"/>
                        <input name="save" type="submit" class="button button-primary button-large" id="publish"
                               accesskey="p" value="Enregistrer le contact"/>';

                    } //if

                echo '</div><div class="clear"></div></div></div>';

    }


    /**
     * mv_address_book CPT updates messages.
     *
     * @param array $messages Existing post update messages.
     *
     * @return array Amended mv_address_book CPT notices
     */
    public function mv_address_book_cpt_messages($messages)
    {
        $post = get_post();
        $post_type = get_post_type($post);
        $post_type_object = get_post_type_object($post_type);

        $messages['mv_address_book'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => __('Contact updated.', 'textdomain'),
            2 => __('Custom field updated.', 'textdomain'),
            3 => __('Custom field deleted.', 'textdomain'),
            4 => __('Contact updated.', 'textdomain'),
            5 => isset($_GET['revision']) ? sprintf(__('Contact restored to revision from %s', 'textdomain'), wp_post_revision_title((int)$_GET['revision'], false)) : false,
            6 => __('Contact saved.', 'textdomain'),
            7 => __('Contact saved.', 'textdomain'),
            8 => __('Contact submitted.', 'textdomain'),
            9 => sprintf(
                __('Contact scheduled for: <strong>%1$s</strong>.', 'textdomain'),
                date_i18n(__('M j, Y @ G:i', 'textdomain'), strtotime($post->post_date))
            ),
            10 => __('Contact draft updated.', 'textdomain')
        );


        return $messages;
    }


    // "Personnes et Organisations" tree map metabox

    public function mv_add_pro_treemap() {
        add_meta_box(
            'mv-treemap-metabox',
            'Arborescence "Personnes et Organisations"',
            array($this,'mv_treemap_content'),
            'mv_address_book',
            'normal',
            'default'
        );
    }

    public function mv_treemap_content(){
        global $post;
        $post_id = $post->ID;
        $list_cat_param = array(
            'taxonomy' => 'nomenclature_beta',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'child_of' => $this->personnes_organisations_id,
            'hierarchical' => true,
            'show_count' => true
        );
        wp_list_categories($list_cat_param);
    }

    // on save post save the selected nomenclature ids as taxonomy terms
    public function ab_acf_save_post( $post_id ) {

        if(get_post_type($post_id)!='mv_address_book')
            return;

        $ac_field = array(
            'categorie_principale_organisation',
            'categorie_secondaire_organisation',
            'categorie_tertiaire_organisation',
            'cat_pro_level_4',
            'cat_pro_level_5',
        );

        $ac_field_catname = array(
            'cat_pro_level_6',
        );

        $procat_fields = array();

        $acf_cat_group = get_field('mv_cat_orga_grp');
        foreach ($ac_field as $acf_fd){

            $acf_field = $acf_cat_group[$acf_fd];
            //error_log('$acf_field_val: '. $acf_field['value']);
            $procat_fields[] = $acf_field['value'];
        }

        $acf_group = get_field('mv_orga_name_grp');
        foreach ($ac_field_catname as $acf_fd){

            $acf_field = $acf_group[$acf_fd];
            //error_log('$acf_field_val: '. $acf_field['value']);
            $procat_fields[] = $acf_field['value'];
        }

        $new_org = $acf_group["organisation_name_to_be_added"];
        if (!is_empty($new_org)) {
            $parent_term_id = 8502;
            foreach ($ac_field as $acf_fd){
                $acf_field = $acf_cat_group[$acf_fd];
                if (is_empty($acf_field)) {
                    $term = term_exists("-", 'nomenclature_beta', $parent_term_id);
                    if (null == $term) {
                        $term = wp_insert_term(
                        '-',
                        'nomenclature_beta',
                        array(
                            'parent' => $parent_term_id,
                            )
                        );
                    }
                    $parent_term_id = $term['term_id'];
                }
                else {
                    $parent_term_id = $acf_field['value'];
                }
            }
            wp_insert_term(
                $new_org,
                'nomenclature_beta',
                array(
                    'parent' => $parent_term_id,
                )
            );
        }

        foreach(get_field('mv_ab_tags_grp')['label_taxonomy'] as $term_object){
            $procat_fields[] = $term_object->term_id;
        };

        //error_log(print_r($procat_fields,true));

        wp_set_post_terms( $post_id, $procat_fields, 'nomenclature_beta');

    }

    function org_acf_validate_value( $valid, $value, $field, $input ){

        // bail early if value is already invalid
        if( !$valid ) {
            return $valid;
        }

        if (!is_empty($value)) {
            $term = term_exists($value, 'nomenclature_beta');
            if (0 !== $term && null !== $term) {
                $valid = "Déjà disponible dans la liste ci-dessus";
            }
        }

        // return
        return $valid;
    }

    public function org_acf_reset_value($value) {
        return "";
    }
}