<?php

class Mooveat_Address_Book_ACF
{
    private $version;

    protected $personnes_organisations_id;
    protected $produits_id;
    protected $produits_transformes_id;
    protected $produits_bruts_id;
    protected $signes_qualite_id;
    protected $acf_cat_orga_grp;
    protected $acf_cat_orga_fields;

    public function __construct($version)
    {

        $this->version = $version;

        /* main vars */
        $this->personnes_organisations_id = 8502;
        $this->produits_id = 6623;
        $this->produits_transformes_id = 6818;
        $this->produits_bruts_id = 6624;
        $this->signes_qualite_id = 5539;

        $this->acf_cat_orga_fields = array(
            'categorie_principale_organisation',
            'categorie_secondaire_organisation',
            'categorie_tertiaire_organisation',
            'cat_pro_level_4',
            'cat_pro_level_5',
            'cat_pro_level_6'
        );


        add_action('plugins_loaded', array( $this, 'acf_options_page') );

        add_filter( 'terms_clauses', array( $this, 'terms_clauses_multiple_parents'), 20, 3 );

        // Populate select field using filter
        add_filter('acf/load_field/name=categorie_principale_organisation', array( $this, 'acf_load_category') );
        // Populate select field using filter
        add_filter('acf/load_field/name=categorie_secondaire_organisation', array( $this, 'acf_load_category') );
        // Populate select field using filter
        add_filter('acf/load_field/name=categorie_tertiaire_organisation', array( $this, 'acf_load_category') );
        add_filter('acf/load_field/name=cat_pro_level_4', array( $this, 'acf_load_category') );
        add_filter('acf/load_field/name=cat_pro_level_5', array( $this, 'acf_load_category') );
        add_filter('acf/load_field/name=cat_pro_level_6', array( $this, 'acf_load_category') );

        // Populate select field using filter
        add_filter('acf/load_field/name=categories_produits', array( $this, 'acf_load_categories_produits') );

        // Populate select field u
        add_filter('acf/load_field/name=label', array( $this, 'acf_load_labels') );

        add_filter('ac/column/value', array( $this, 'ac_cpo_column_value'), 20, 3 );
        add_filter('ac/export/value', array( $this, 'ac_cpo_column_value_export'), 20, 3 );

//        add_action( 'save_post', array( $this, 'save_post' ) );

        add_action( 'admin_notices', array($this, 'admin_notice'), 20 );

//        add_action('init', array($this, 'admin_only') );

        add_action( 'delete_user', array($this, 'update_contact_from_WP_USER'),20, 1 );

    }



// Add ACF options page for import/export
    public function acf_options_page()
    {
        if( function_exists('acf_add_options_page') ) {

            acf_add_options_page(array(
                'page_title' 	=> 'Import / Export',
                'menu_title'	=> 'Import / Export',
                'menu_slug' 	=> 'import-export-addressbook',
                'capability'	=> 'edit_posts',
                'parent_slug'   => 'edit.php?post_type=mv_address_book',
                'redirect'		=> false
            ));

        }
    }


    // Parent can be array with multiple terms
    public function terms_clauses_multiple_parents( $pieces, $taxonomies, $args )
    {
        // Bail if we are not currently handling our specified taxonomy
        if (!in_array('nomenclature_beta', $taxonomies))
            return $pieces;

        // Check if our custom argument, 'wpse_parents' is set, if not, bail
        if (!isset ($args['wpse_parents'])
            || !is_array($args['wpse_parents'])
        )
            return $pieces;

        // If  'wpse_parents' is set, make sure that 'parent' and 'child_of' is not set
        if ($args['parent']
            || $args['child_of']
        )
            return $pieces;

        // Validate the array as an array of integers
        $parents = array_map('intval', $args['wpse_parents']);

        // Loop through $parents and set the WHERE clause accordingly
        $where = [];
        foreach ($parents as $parent) {
            // Make sure $parent is not 0, if so, skip and continue
            if (0 === $parent)
                continue;

            $where[] = " tt.parent = '$parent'";
        }

        if (!$where)
            return $pieces;

        $where_string = implode(' OR ', $where);
        $pieces['where'] .= " AND ( $where_string ) ";

        return $pieces;
    }


    public function my_post_title_updater($post_id)
    {

        if ((wp_is_post_revision($post_id) || wp_is_post_autosave($post_id))) {
            return;
        }

        if (get_post_type() == 'mv_address_book') {
            // Update the post into the database
            $my_contact = array(
                'ID' => $post_id,
                'post_status' => 'publish',
                'post_name' => $post_id,
                'post_type' => 'mv_address_book'
            );

            $contact_group = get_field('contact', $post_id);

            $firstName = $contact_group['first_name'];
            $nom = $contact_group['nom'];
            $fullName = $firstName . ' ' . $nom;

            $my_contact['post_title'] = $fullName;
            $my_contact['post_name'] = sanitize_title($fullName);
            wp_insert_post($my_contact);

        }
        else if (get_post_type() == 'organisation'){
            $orga_post = array(
                'ID' => $post_id,
                'post_status' => 'publish',
                'post_name' => $post_id,
                'post_type' => 'organisation'
            );

            $orga_name = get_field('nomenclature_link',$post_id)->name;

            if(empty($orga_name)){
                $orga_name = get_field('mv_orga_organisation_name_to_be_added',$post_id);
            }

            $orga_post['post_title'] = $orga_name;
            $orga_post['post_name'] = sanitize_title($orga_name);
            wp_insert_post($orga_post);

        }

    }

    public function save_post_query_var( ) {
        add_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99 );

    }

    public function add_notice_query_var( $location ) {
        remove_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99 );
        return add_query_arg( array( 'saved' => 'true' ), $location );
    }



    public function admin_notice() {

        global $pagenow;

        if ( $pagenow == 'user-edit.php' ) {

            $user_id = isset ($_GET['user_id']) ? $_GET['user_id'] : '';
            $is_linked_ID = get_user_meta($user_id, 'user_linked_to_contact', true);


            if ($is_linked_ID) {
                $contact_url = get_edit_post_link( $is_linked_ID );
                $contact_name = get_the_title( $is_linked_ID );


                if ( $contact_name ) {

                    $class = 'notice notice-info is-dismissible';

                    // Contact entry corresponding WP-USER notification
                    $message = sprintf(__("Cet utilisateur wordpress gère la fiche du contact ", 'mv') . '<a href="%1$s">%2$s</a>', $contact_url, $contact_name);

                    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message  );
                }

            }

        }


        if ( ! isset( $_GET['saved'] ) ) {
            return;
        }

        if ( $this->is_empty_email() == true && $this->checked_wpuser() == true ){

            $class = 'notice notice-error is-dismissible';
            $message = __( 'Please input email address to create WP-USER', 'mv' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        }


        if( $this->is_email_duplicate() == true  && $this->checked_wpuser() == true && $this->is_empty_email() == false ){

            $class = 'notice notice-error is-dismissible';
            $message = __( 'Duplicate email found', 'mv' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        }


    }

    public function is_empty_email() {
        $email = get_post_meta( get_the_ID(), 'social_email', true );

        if ( !($email) ) {
            // email is empty
            return true;
        } else {
            return false;
        }

    }

    public function checked_wpuser() {
        $wp_user_checked = get_post_meta( get_the_ID(), 'contact_wp_user', true );

        if( !$wp_user_checked ){
            //checkbox is not checked
            return false;
        } else {
            return true;
        }
    }

    public function is_email_duplicate() {
        $email_exist = get_post_meta( get_the_ID(), 'duplicate_email_exist', true );

        if ( ($email_exist == 1)  ){
            //duplicate email found
            return true;
        } else {
            return false;
        }
    }

    function create_wpuser_from_contact($post_id) {

        $email_key = 'duplicate_email_exist';
        $email = get_post_meta( get_the_ID(), 'social_email', true );

        delete_post_meta($post_id, $email_key);

        if (!$email) {
            return;
        }

        $contacts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => 'mv_address_book',
            'exclude'   => $post_id,
            'meta_key' => 'social_email',
            'meta_value' => $email,
        ));

        $contact_url = get_edit_post_link( $post_id );
        $contact_name = get_the_title($post_id);


        if( !empty($contacts) ) {
            // some contact found
            add_post_meta($post_id, $email_key, 1, false);

            // If this is a revision, don't send the email.
            if ( wp_is_post_revision( $post_id ) )
                return;

            $subject = 'A contact has duplicate email';

            $message = "L’email de cette personne est déjà présent dans la liste des utilisateurs wordpress\n\n";
            $message .= '<a href="'. $contact_url . '">' . $contact_name . '</a>';

            // Send email to admin.
            if ($this->is_email_duplicate() == true  && $this->checked_wpuser() == true && $this->is_empty_email() == false){

                $admin_email = get_option( 'admin_email' );
                wp_mail( $admin_email, $subject, $message );
            }



        } else {

            $existed_user_ID = email_exists($email);

            // bail early if  wp-user checkbox is not checked
            if ( $this->checked_wpuser() == false ) {
                // delete user meta about link with contact entry
                delete_user_meta( $existed_user_ID, 'user_linked_to_contact', $post_id);
                return;
            }

            // bail early if email is empty
            if ( $this->is_empty_email() == true ) {
                return;
            }

            // bail early if duplicate  email found in another contact entry
            if ( $this->is_email_duplicate() == true ){
                return;
            }


            $lastName = get_post_meta( get_the_ID(), 'contact_nom', true );
            $firstName = get_post_meta( get_the_ID(), 'contact_first_name', true );

            // check if existing user found and update its information
            if ( $existed_user_ID ) {
                $arg = array(
                    'ID' => $post_id,
                    'post_author' => $existed_user_ID,
                );
                //link contact with WP-USER
                wp_update_post($arg);

                // update WP-USER from contact data

                $userdata = array(
                    'ID'            => $existed_user_ID,
                    'display_name'  => $contact_name,
                    'first_name'    => $firstName,
                    'last_name'     => $lastName,
                    'nickname'      => $contact_name,
                    'user_pass'     => NULL  // When creating an user, `user_pass` is expected.
                );
                wp_update_user( $userdata );

                // add user meta about link with contact entry
                update_user_meta( $existed_user_ID, 'user_linked_to_contact', $post_id);

            } else {

                // If not existing user found then create a new user
                $userdata = array(
                    'user_login' => $email,
                    'user_email' => $email,
                    'display_name' => $contact_name,
                    'first_name'    => $firstName,
                    'last_name'     => $lastName,
                    'nickname'      => $contact_name,
                    'user_pass' => NULL  // When creating an user, `user_pass` is expected.
                );

                $new_user_id = wp_insert_user($userdata);

                // add user meta about link with contact entry
                add_user_meta( $new_user_id, 'user_linked_to_contact', $post_id);

                if ( $new_user_id ) {
                    $arg = array(
                        'ID' => $post_id,
                        'post_author' => $new_user_id,
                    );
                    wp_update_post($arg);
                }

            }
        }

    }


    public function update_contact_from_WP_USER($user_id)
    {

        $is_linked_ID = get_user_meta($user_id, 'user_linked_to_contact', true);

        // check if its corresponding user
        if ($is_linked_ID) {

            // Un-check WP-USER in corresponding contact entry
            update_post_meta($is_linked_ID, 'contact_wp_user', null);

            $arg = array(
                'ID' => $is_linked_ID,
                'post_author' => get_current_user_id(), // assign contact to current logged in user
            );
            //link contact with WP-USER
            wp_update_post($arg);

        }
    }

    public function acf_load_category($field)
    {
        $field['choices'] = array();
        $selected_field = array();
        $get_terms_param = array(
            'taxonomy' => 'nomenclature_beta',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false
        );

        // mv_orga is set only after clicking "add new contact to this organisation" link in Organisation CPT
        if(!isset($_GET['mv_orga']) || empty($_GET['mv_orga']) || !$_GET['mv_orga']>0){
            // Reset choices

            if($field['name']=='categorie_principale_organisation'){
                $selected_field = [$this->personnes_organisations_id];
                $get_terms_param['wpse_parents'] = $selected_field;
            }
            else{
                $previous_field = '';

                foreach ($this->acf_cat_orga_fields as $acf_field){
                    if($field['name']==$acf_field && $field['name']!='categorie_principale_organisation'){
                        //error_log(print_r(get_field('mv_cat_orga_grp_'.$previous_field),true));
                        if ( isset( get_field('mv_cat_orga_grp_'.$previous_field)['value'] ) ) {
                            $selected_field = get_field('mv_cat_orga_grp_'.$previous_field)['value'];
                            $get_terms_param['parent'] = $selected_field;
                        }
                    }
                    /*else if($field['name']==$acf_field && $field['name']== 'cat_pro_level_6'){
                        if ( isset( get_field('mv_orga_name_grp'.$previous_field)['value'] ) ) {
                            $selected_field = get_field('mv_orga_name_grp'.$previous_field)['value'];
                            $get_terms_param['parent'] = $selected_field;
                        }
                    }*/
                    $previous_field = $acf_field;
                }
            }

            if ( !empty( $selected_field ) ) {
                $terms = get_terms($get_terms_param);

                // Populate
                $field['choices'][''] = '- Choisir -';

                if (!empty($terms)) {
                    foreach ($terms as $term) {
                        $field['choices'][$term->term_id] = $term->name;
                    }
                }
            }
        }
        else{
            $ancestors = get_ancestors($_GET['mv_orga'],'nomenclature_beta');
            array_pop($ancestors);
            $ancestors_rev = array_reverse($ancestors);
            $ancestors_rev[] = $_GET['mv_orga'];
            //error_log(print_r($ancestors,true));
            $idx = 0;
            foreach ($this->acf_cat_orga_fields as $acf_field) {
                if($field['name']==$acf_field){
                    $get_terms_param['term_taxonomy_id'] = array($ancestors_rev[$idx]);
                    $terms = get_terms($get_terms_param);
                    $field['choices'][$terms[0]->term_id] = $terms[0]->name;
                }
                $idx++;
            }
        }

        // Return values
        return $field;

    }


    public function set_next_level_options(){
        // Verify nonce AJAX
        if (!isset($_POST['catpro_nonce']) || !wp_verify_nonce($_POST['catpro_nonce'], 'catpro_nonce'))
            die('Permission denied');

        // Get principal selected var
        $selected_catpro = $_POST['selected_catpro'];

        $terms = get_terms(array(
            'taxonomy' => 'nomenclature_beta',
            'orderby' => 'name',
            'order' => 'ASC',
            'parent' => $selected_catpro,
            'hide_empty' => false
        ));

        $found_catpro = array();
        foreach ($terms as $term) {
            $found_catpro[] = array(
                $term->term_id => $term->name
            );
        }

        // Returns direct child terms as array if there is selected category pro level n-1
        if (count($found_catpro) > 0) {
            return wp_send_json($found_catpro);

        } else {
            return wp_send_json(null);
        }

        die();
    }

    public function set_previous_levels(){
        // Verify nonce AJAX
        if (!isset($_POST['catpro_nonce']) || !wp_verify_nonce($_POST['catpro_nonce'], 'catpro_nonce'))
            die('Permission denied');

        // Get principal selected var
        $selected_catpro = $_POST['selected_catpro'];

        $ancestors = get_ancestors($selected_catpro,'nomenclature_beta','taxonomy');

        $ancestors_id_name = array();

        foreach ($ancestors as $ancestor) {
            $anc_term = get_term($ancestor,'nomenclature_beta');
            $ancestors_id_name[] = array(
                'id' => $anc_term->term_id,
                'name' => $anc_term->name
            );
        }

        // Returns direct child terms as array if there is selected category pro level n-1
        if (count($ancestors_id_name) > 0) {

            $ancestors_id_name = array_reverse($ancestors_id_name);
            array_shift($ancestors_id_name);

            return wp_send_json($ancestors_id_name);

        } else {
            return wp_send_json(null);
        }

        die();
    }


    public function acf_load_categories_produits($field)
    {

        // Reset choices
        $field['choices'] = array();

        /**
         * Get all direct child's of specific parent terms. Note we use 'wpse_parents' => id to only get terms for
         *
         * @see get_terms
         * @link http://codex.wordpress.org/Function_Reference/get_terms
         */

        $terms_1 = get_terms(array(
            'taxonomy' => 'nomenclature_beta',
            'orderby' => 'name',
            'order' => 'ASC',
            'parent' => $this->produits_bruts_id,
            'hide_empty' => false,
        ));

        //error_log(print_r($terms_1[0],true));

        foreach ($terms_1 as $term_1) {
            $term_1->name = $term_1->name . ' (produit brut)';
        }

        //error_log(print_r($terms_1[0],true));

        $terms_2 = get_terms(array(
            'taxonomy' => 'nomenclature_beta',
            'orderby' => 'name',
            'order' => 'ASC',
            'parent' => $this->produits_transformes_id,
            'hide_empty' => false,
        ));
        foreach ($terms_2 as $term_2) {
            $term_2->name = $term_2->name . ' (produit transformé)';
        }

        $terms = array_merge($terms_1,$terms_2);


        // Populate
//        $field['choices'][''] = 'Select Category';

        foreach ($terms as $term) {
            $field['choices'][$term->term_id] = $term->name;
        }

        // Return values
        return $field;

    }



    public function acf_load_labels($field) // Warning : function not used anymore -> labels loaded as taxonomy and filtered by "mooveat-produits-alimentaires" plugin
    {

        // Reset choices
        $field['choices'] = array();

        /**
         * Get all direct child's of specific parent terms. Note we use 'wpse_parents' => id to only get terms for
         *
         * @see get_terms
         * @link http://codex.wordpress.org/Function_Reference/get_terms
         */

        /*$terms = get_terms(array(
            'taxonomy' => 'nomenclature_beta',
            'orderby' => 'name',
            'order' => 'ASC',
            'parent' => '5539',
            'hide_empty' => false
        ));*/

        $slug_id_array = array(
            'personnes-et-organisations' => $this->personnes_organisations_id,
            'produits' => $this->produits_id,
        );

        $terms = get_terms(array(
            'taxonomy' => 'nomenclature_beta',
            'orderby' => 'name',
            'order' => 'ASC',
            'exclude_tree' => $slug_id_array,
            'hide_empty' => false
        ));


        // Populate
//        $field['choices'][''] = 'Select Label';

        foreach ($terms as $term) {
            $field['choices'][$term->term_id] = $term->name;
        }

        // Return values
        return $field;

    }

    private function ac_column_value_change($value,$id,$column,$is_export=false){
        if ( $column instanceof ACP_Column_CustomField || $column instanceof ACP\Column\CustomField ) {
            $meta_key = $column->get_meta_key(); // This gets the Custom Field key

            $before_value = $value;

            $this->ac_column_value_pro_cat('mv_cat_orga_grp_categorie_principale_organisation',$value,$before_value,$meta_key,$is_export);
            $this->ac_column_value_pro_cat('mv_cat_orga_grp_categorie_secondaire_organisation',$value,$before_value,$meta_key,$is_export);
            $this->ac_column_value_pro_cat('mv_cat_orga_grp_categorie_tertiaire_organisation',$value,$before_value,$meta_key,$is_export);
            $this->ac_column_value_pro_cat('mv_cat_orga_grp_cat_pro_level_4',$value,$before_value,$meta_key,$is_export);
            $this->ac_column_value_pro_cat('mv_cat_orga_grp_cat_pro_level_5',$value,$before_value,$meta_key,$is_export);
            $this->ac_column_value_pro_cat('mv_orga_name_grp_cat_pro_level_6',$value,$before_value,$meta_key,$is_export);

            if ( 'mv_ab_tags_grp_categories_produits' == $meta_key ) {

                if ($value != '&ndash;') {

                    $value_array = explode(", ", $value);
                    $value='';
                    foreach ($value_array as $value_item){
                        $selected_catprod = get_term( $value_item, 'nomenclature_beta' );
                        if(!is_null($selected_catprod) && !is_wp_error($selected_catprod)):
                            $id_info = $is_export ? '; ' : ' <span class="small-txt">(id = ' . $value_item . ')</span>;<br/>';
                            $value .= $selected_catprod->name . $id_info;
                        else:

                        endif;
                    }

                    if($value==''):
                        $value = '-';
                    endif;
                }
            }

            if ( 'mv_ab_tags_grp_label_taxonomy' == $meta_key ) {

                if ($value != '&ndash;') {

                    //error_log(print_r($value,true));
                    $value_array = explode(", ", $value);
                    $value='';
                    foreach ($value_array as $value_item){
                        $selected_label = get_term( $value_item, 'nomenclature_beta' );
                        if(!is_null($selected_label) && !is_wp_error($selected_label)):
                            $id_info = $is_export ? '; ' : ' <span class="small-txt">(id = ' . $value_item . ')</span>;<br/>';
                            $value .= $selected_label->name . $id_info;
                        else:

                        endif;
                    }

                    if($value==''):
                        $value = '-';
                    endif;
                }
            }

            /*if ( 'mv_ab_tags_grp_type_restauration_collective' == $meta_key ) {
                if ($value != '&ndash;') {
                    $value_array = explode(", ", $value);
                    $value='';
                    foreach ($value_array as $value_item){
                        //error_log(print_r($item_option_value,true));
                        $value .= get_field_object('mv_ab_tags_grp')['sub_fields'][0]['choices'][$value_item] . '; ';
                    }
                    if($value==''):
                        $value = '-';
                    endif;
                }
            }*/
            $this->change_valuelabel_field_value('mv_ab_tags_grp_type_restauration_collective','mv_ab_tags_grp',$value,$meta_key,$id,$is_export);
            $this->change_valuelabel_field_value('mv_ab_tags_grp_type_cuisine','mv_ab_tags_grp',$value,$meta_key,$id,$is_export);
            $this->change_valuelabel_field_value('mv_ab_tags_grp_echelle_administration','mv_ab_tags_grp',$value,$meta_key,$id,$is_export);

            $this->change_valuelabel_field_value('type_profil_grp_type_profil_personne_organisation','type_profil_grp',$value,$meta_key,$id,$is_export);
            $this->change_valuelabel_field_value('stade_abo_grp_stade_abo','stade_abo_grp',$value,$meta_key,$id,$is_export);
            $this->change_valuelabel_field_value('mv_ab_tags_grp_fonction_dans_la_filiere','fonction_dans_la_filiere',$value,$meta_key,$id,$is_export);


            //$value = $this->change_valuelabel_field_value('mv_ab_tags_grp_type_restauration_collective','mv_ab_tags_grp',$value,$meta_key);
        }

        return $value;
    }

    private function ac_column_value_pro_cat($ac_grp,&$value,$before_value,$meta_key,$is_export=false){
        if ( $ac_grp == $meta_key ) {
            if ($value != '&ndash;') {
                $selected_cat = get_term( $value, 'nomenclature_beta' );
                if(!is_null($selected_cat) && !is_wp_error($selected_cat)):
                    $id_info = $is_export ? '; ' : ' <span class="small-txt">(id = ' . $before_value . ')</span>;<br/>';
                    $value = $selected_cat->name . $id_info;
                else :
                    $value = '-';
                endif;
            }
        }
    }

    private function change_valuelabel_field_value($ac_field_name,$acf_field_group_name,&$value,$meta_key,$id,$is_export=false){
        if ( $ac_field_name == $meta_key ) {
            if ($value != '&ndash;' && !empty($value)) {
                $value_array = explode(", ", $value);
                $value='';

                $ac_field_name_val = get_field($ac_field_name);

                /*if($id==81876){
                    error_log('$value_array: ');
                    error_log(print_r($value_array,true));
                    error_log('$ac_field_name_val: ');
                    error_log(print_r($ac_field_name_val,true));
                }*/

                foreach ($value_array as $value_item){
                    $html_format = $is_export ? '; ' : '; <br/>';
                    if($is_export){
                        //error_log(print_r(get_field($acf_field_group_name,$id),true));
                    }
                    if(!empty($ac_field_name_val['value'])){
                        $value = $ac_field_name_val['label'];
                    }
                    else{
                        foreach ($ac_field_name_val as $val_lab_array){
                            if($val_lab_array['value'] == $value_item){
                                $value .= $val_lab_array['label'] . $html_format;
                            }
                        }
                    }
                    //error_log(print_r(get_field($ac_field_name),true));
                    //$value .= get_field_object($acf_field_group_name,$id)['sub_fields'][0]['choices'][$value_item] . $html_format;
                }

                if($value==''):
                    $value = '-';
                endif;
            }
        }
    }

    public function ac_cpo_column_value( $value, $id, $column ) {
        return $this->ac_column_value_change($value,$id,$column,false);
    }

    public function ac_cpo_column_value_export( $value, $column, $id ) {
        if ( ! $column instanceof ACP_Column_CustomField && ! $column instanceof ACP\Column\CustomField ){
            if($column->get_name() == 'title'){
                //error_log(print_r($value,true));
                $value = get_the_title($id);
            }
        }

        return $this->ac_column_value_change($value,$id,$column,true);
    }

}