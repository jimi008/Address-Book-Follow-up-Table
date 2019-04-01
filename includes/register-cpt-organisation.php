<?php
/**
 * Created by PhpStorm.
 * User: laurentnicolas
 * Date: 13/08/2018
 * Time: 23:43
 */

function cptui_register_my_cpts_organisation() {

    /**
     * Post Type: Organisations.
     */

    $labels = array(
        "name" => __( "Organisations", "mooveat" ),
        "singular_name" => __( "Organisation", "mooveat" ),
        "add_new_item" => "Ajouter une nouvelle organisation",
        "edit_item" => '',
        "add_new" => 'Ajouter une organisation'
    );

    $args = array(
        "label" => __( "Organisations", "mooveat" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "has_archive" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => true,
        "rewrite" => array( "slug" => "organisation", "with_front" => true ),
        "query_var" => true,
        "supports" => array("thumbnail"),
        "taxonomies" => array( "nomenclature_beta" ),
    );

    register_post_type( "organisation", $args );
}

add_action( 'init', 'cptui_register_my_cpts_organisation' );

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5b71fb5de8ad2',
        'title' => 'Informations organisation',
        'fields' => array(
            /*array(
                'key' => 'field_5b71fc4527833',
                'label' => 'Groupe',
                'name' => 'groupe_entite',
                'type' => 'relationship',
                'instructions' => 'Organisation de rattachement',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'organisation',
                ),
                'taxonomy' => array(
                ),
                'filters' => array(
                    0 => 'search',
                ),
                'elements' => '',
                'min' => '',
                'max' => '',
                'return_format' => 'object',
            ),*/
            array(
                'key' => 'field_5b71fnomlink',
                'label' => "Nom de l'organisation",
                'name' => 'nomenclature_link',
                'type' => 'taxonomy',
                'instructions' => "Sélectionner l'organisation correspondante de la nomenclature",
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'taxonomy' => 'nomenclature_beta',
                'field_type' => 'select',
                'allow_null' => 0,
                'add_term' => 0,
                'save_terms' => 1,
                'load_terms' => 1,
                'return_format' => 'object',
                'multiple' => 0,
            ),
            array(
                'key' => 'field_5b71f_orga_name',
                'label' => "Nom de l'Organisation (si introuvable dans la liste précédente)",
                'name' => 'mv_orga_organisation_name_to_be_added',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5b71fcd927834',
                'label' => 'Page facebook',
                'name' => 'page_facebook',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5b71fcf527835',
                'label' => 'Compte Twitter',
                'name' => 'compte_twitter',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5b71fd0227836',
                'label' => 'Compte Instagram',
                'name' => 'compte_instagram',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5b71fd1027837',
                'label' => 'Page linkedin',
                'name' => 'page_linkedin',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'organisation',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            0 => 'discussion',
            1 => 'comments',
            2 => 'categories',
            3 => 'tags',
            4 => 'send-trackbacks',
            5 => 'the_content',
            6 => 'excerpt'
        ),
        'active' => 1,
        'description' => '',
    ));

endif;

function ac_custom_column_settings_527868b0()
{
    if (function_exists('ac_register_columns')):
        ac_register_columns('organisation', array(
            array(
                'columns' => array(
                    'title' => array(
                        'type' => 'title',
                        'label' => 'Titre',
                        'width' => '',
                        'width_unit' => '%',
                        'edit' => 'off',
                        'sort' => 'on',
                        'name' => 'title'
                    ),
                    'taxonomy-nomenclature_beta' => array(
                        'type' => 'taxonomy-nomenclature_beta',
                        'label' => 'Organisation de rattachement',
                        'width' => '',
                        'width_unit' => '%',
                        'edit' => 'off',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => 'taxonomy-nomenclature_beta',
                        'enable_term_creation' => 'off'
                    ),
                    'mv_orga_orga_to_be_added_col' => array(
                        'type' => 'column-acf_field',
                        'label' => 'Organisation (si aucun rattachement)',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_orga_organisation_name_to_be_added',
                        'character_limit' => '50',
                        'edit' => 'off',
                        'sort' => 'off',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => 'mv_orga_orga_to_be_added_col'
                    ),
                    'date' => array(
                        'type' => 'date',
                        'label' => 'Date',
                        'width' => '',
                        'width_unit' => '%',
                        'edit' => 'off',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'filter_format' => '',
                        'name' => 'date'
                    ),
                    'ridwpaid' => array(
                        'type' => 'ridwpaid',
                        'label' => '<abbr style="cursor:help;" title="Enrichi par l’extension Reveal IDs">ID</abbr>',
                        'width' => '',
                        'width_unit' => '%',
                        'sort' => 'on',
                        'name' => 'ridwpaid'
                    ),
                    '5bae1ca3dd4c4' => array(
                        'type' => 'column-acf_field',
                        'label' => 'Instagram',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'field_5b71fd0227836',
                        'character_limit' => '50',
                        'edit' => 'off',
                        'sort' => 'off',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5bae1ca3dd4c4'
                    ),
                    '5bae1ca3ddf23' => array(
                        'type' => 'column-acf_field',
                        'label' => 'Compte Twitter',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'field_5b71fcf527835',
                        'character_limit' => '50',
                        'edit' => 'off',
                        'sort' => 'off',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5bae1ca3ddf23'
                    ),
                    '5bae1ca3de102' => array(
                        'type' => 'column-acf_field',
                        'label' => 'Facebook',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'field_5b71fcd927834',
                        'character_limit' => '50',
                        'edit' => 'off',
                        'sort' => 'off',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5bae1ca3de102'
                    ),
                    '5bae1ca3de2c9' => array(
                        'type' => 'column-acf_field',
                        'label' => 'LinkedIn',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'field_5b71fd1027837',
                        'character_limit' => '50',
                        'edit' => 'off',
                        'sort' => 'off',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5bae1ca3de2c9'
                    )
                ),

            )
        ));
    endif;
}

add_action('ac/ready', 'ac_custom_column_settings_527868b0');

function set_tax_organisation_field($args, $field){
    $personnes_organisations_id = 8502;
    $selected_terms_ids = array();

    if($field['_name']=='nomenclature_link' || $field['_name']=='mv_address_book_orga_name_nomenclature'):
        //error_log('nomenclature_link field');
        $terms = get_terms(array(
            'taxonomy' => 'nomenclature_beta',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'child_of' => $personnes_organisations_id,
            'fields' => 'ids'
        ));

    foreach ($terms as $term_id){
        $depth = count(get_ancestors( $term_id, 'nomenclature_beta' ));
        if($depth == 6){
            $selected_terms_ids[] = $term_id;
        }
    }

    endif;

    $args['include'] = $selected_terms_ids;

    return $args;
}

add_filter('acf/fields/taxonomy/query','set_tax_organisation_field', 10, 2);


function mv_add_organisation_metaboxes() {
    add_meta_box(
        'mv-related-contacts',
        'Contacts associés',
        'mv_organisation_content',
        'organisation',
        'normal',
        'default'
    );
}

function mv_organisation_content(){
    global $post;
    $post_id = $post->ID;
    $args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'ids');
    $acf_orga_field_val = '';

    $organisation_terms = wp_get_post_terms( $post_id, 'nomenclature_beta', $args );

    if(empty($organisation_terms)){
        echo '<p>Merci de remplir le champ "Lien nomenclature" et d\'enregistrer cette organisation pour afficher les contacts associés.</p>';
    }
    else{
        $contacts_loop = new WP_Query( array(
                'post_type' => 'mv_address_book',
                'posts_per_page' => -1
            )
        );

        $firstname_array = $lastname_array = $direction_array = $fonction_array
        = $tel_array = $mob_array = $email_array = $website_array
        = $adresse_array = $city_array = $cp_array = $country_array
        = array();

        while ( $contacts_loop->have_posts() ) : $contacts_loop->the_post();
            $acf_grp = get_field('mv_orga_name_grp');
            if(!empty($acf_grp) && !empty($acf_grp['cat_pro_level_6']['value'])){
                $acf_orga_field_val = $acf_grp['cat_pro_level_6']['value'];
            }

            if(in_array($acf_orga_field_val,$organisation_terms)){
                $acf_grp_contact = get_field('contact');
                if(!empty($acf_grp_contact)){
                    $firstname_array[] = $acf_grp_contact['first_name'];
                    $lastname_array[] = $acf_grp_contact['nom'];
                    $fonction_array[] = $acf_grp_contact['fonction'];

                    $acf_grp_social = get_field('social');
                    $email_array[] = $acf_grp_social['email'];
                    $website_array[] = $acf_grp_social['site_web'];

                    $acf_grp_tel = get_field('telephone-grp');
                    $tel_array[] = $acf_grp_tel['telephone_fixe'];
                    $mob_array[] = $acf_grp_tel['telephone_mobile'];

                    $acf_grp_address = get_field('adresse-grp');
                    $adresse_array[] = $acf_grp_address['numero_&_rue'];
                    $city_array[] = $acf_grp_address['city'];
                    $cp_array[] = $acf_grp_address['code_postal'];
                    $country_array[] = $acf_grp_address['country'];

                }
            }
        endwhile;
        wp_reset_query();

        if(count($firstname_array)>0):
            echo '<table id="organisation-linked-contacts-table">'
                .'<tr><th>Prénom</th><td><strong>'. implode('</strong></td><td><strong>',$firstname_array) . '</strong></td></tr>'
                .'<tr><th>Nom</th><td><strong>'. implode('</strong></td><td><strong>',$lastname_array) . '</strong></td></tr>'
                .'<tr><th>Fonction</th><td>'. implode('</td><td>',$fonction_array) . '</td></tr>'
                .'<tr><th>Email</th><td>'. implode('</td><td>',$email_array) . '</td></tr>'
                .'<tr><th>Site web</th><td>'. implode('</td><td>',$website_array) . '</td></tr>'
                .'<tr><th>Tél fixe</th><td>'. implode('</td><td>',$tel_array) . '</td></tr>'
                .'<tr><th>Mobile</th><td>'. implode('</td><td>',$mob_array) . '</td></tr>'
                .'<tr><th>Adresse</th><td>'. implode('</td><td>',$adresse_array) . '</td></tr>'
                .'<tr><th>Ville</th><td>'. implode('</td><td>',$city_array) . '</td></tr>'
                .'<tr><th>Code Postal</th><td>'. implode('</td><td>',$cp_array) . '</td></tr>'
                .'<tr><th>Pays</th><td>'. implode('</td><td>',$country_array) . '</td></tr>'
                .'</table>';
        endif;

        $add_contact_link = '/wp-admin/post-new.php?post_type=mv_address_book&mv_orga=' . get_field('nomenclature_link',$post_id)->term_id;

        echo '<a class="add-new-contact button" target="_blank" href="'. $add_contact_link .'">+ Ajouter un contact à cette organisation</a>';

    }
}

add_action( 'add_meta_boxes', 'mv_add_organisation_metaboxes' );
