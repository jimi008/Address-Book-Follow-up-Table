<?php
function ac_custom_column_settings_e2e1dfda() {

    if ( function_exists( 'ac_register_columns' ) ) {

            ac_register_columns( 'mv_address_book', array(
                array(
                    'columns' => array(
                        'title' => array(
                            'type' => 'title',
                            'label' => 'Prénom, Nom Contact',
                            'width' => '',
                            'width_unit' => '%',
                            'edit' => 'off',
                            'sort' => 'on',
                            'name' => 'title'
                        ),
                        /*'5acf62d6ac60b' => array(
                            'type' => 'column-meta',
                            'label' => 'Intitulé organisation',
                            'width' => '',
                            'width_unit' => '%',
                            'field' => 'contact_intitule_organisation',
                            'field_type' => '',
                            'before' => '',
                            'after' => '',
                            'sort' => 'on',
                            'filter' => 'off',
                            'filter_label' => '',
                            'name' => '5acf62d6ac60b'
                        ),*/
                        '5acf62d6b35c0' => array(
                            'type' => 'column-meta',
                            'label' => 'Catégorie principale organisation',
                            'width' => '',
                            'width_unit' => '%',
                            'field' => 'mv_cat_orga_grp_categorie_principale_organisation',
                            'field_type' => '',
                            'before' => '',
                            'after' => '',
                            'sort' => 'on',
                            'filter' => 'off',
                            'filter_label' => '',
                            'name' => '5acf62d6b35c0'
                        ),
                        '5acf63255edf0' => array(
                            'type' => 'column-meta',
                            'label' => 'Catégorie secondaire organisation',
                            'width' => '',
                            'width_unit' => '%',
                            'field' => 'mv_cat_orga_grp_categorie_secondaire_organisation',
                            'field_type' => '',
                            'before' => '',
                            'after' => '',
                            'sort' => 'on',
                            'filter' => 'off',
                            'filter_label' => 'Any CSO',
                            'name' => '5acf63255edf0'
                        ),
                        '5aeb713d9428e' => array(
                            'type' => 'column-meta',
                            'label' => 'Catégorie tertiaire organisation',
                            'width' => '',
                            'width_unit' => '%',
                            'field' => 'mv_cat_orga_grp_categorie_tertiaire_organisation',
                            'field_type' => '',
                            'before' => '',
                            'after' => '',
                            'sort' => 'on',
                            'filter' => 'off',
                            'filter_label' => 'Any CSO',
                            'name' => '5aeb713d9428e'
                        ),
                        '5acf6761304fa' => array(
                            'type' => 'column-meta',
                            'label' => 'Catégorie(s) produit(s)',
                            'width' => '',
                            'width_unit' => '%',
                            'field' => 'mv_ab_tags_grp_categories_produits',
                            'field_type' => 'array',
                            'before' => '',
                            'after' => '',
                            'sort' => 'on',
                            'name' => '5acf6761304fa'
                        ),
                        '5acf725676980' => array(
                            'type' => 'column-meta',
                            'label' => 'Label',
                            'width' => '',
                            'width_unit' => '%',
                            'field' => 'mv_ab_tags_grp_label_taxonomy',
                            'field_type' => 'array',
                            'before' => '',
                            'after' => '',
                            'sort' => 'on',
                            'name' => '5acf725676980'
                        ),
                        '5acf7256780b2' => array(
                            'type' => 'column-meta',
                            'label' => 'Email',
                            'width' => '',
                            'width_unit' => '%',
                            'field' => 'social_email',
                            'field_type' => '',
                            'before' => '',
                            'after' => '',
                            'sort' => 'on',
                            'filter' => 'off',
                            'filter_label' => '',
                            'name' => '5acf7256780b2'
                        ),
                        '5acf72567960b' => array(
                            'type' => 'column-meta',
                            'label' => 'Site internet',
                            'width' => '',
                            'width_unit' => '%',
                            'field' => 'social_site_web',
                            'field_type' => 'link',
                            'link_label' => '',
                            'before' => '',
                            'after' => '',
                            'sort' => 'on',
                            'filter' => 'off',
                            'filter_label' => 'Site internet',
                            'name' => '5acf72567960b'
                        ),
                        '5acf72567a949' => array(
                            'type' => 'column-meta',
                            'label' => 'À contacter',
                            'width' => '7',
                            'width_unit' => '%',
                            'field' => 'suivi_actions_to_be_contacted',
                            'field_type' => '',
                            'before' => '',
                            'after' => '',
                            'sort' => 'on',
                            'filter' => 'on',
                            'filter_label' => 'À contacter',
                            'name' => '5acf72567a949'
                        )
                    ),

                )
            ) );
    }
}
add_action( 'ac/ready', 'ac_custom_column_settings_e2e1dfda' );


function ac_custom_column_settings_ed081c20() {
    if ( function_exists( 'ac_register_columns' ) ) {
        ac_register_columns( 'mv_address_book', array(
            array(
                'columns' => array(
                    'title' => array(
                        'type' => 'title',
                        'label' => 'Prénom, Nom Contact',
                        'width' => '',
                        'width_unit' => '%',
                        'sort' => 'on',
                        'name' => 'title'
                    ),
                    /*'5acf62d6ac60b' => array(
                        'type' => 'column-meta',
                        'label' => 'Intitulé organisation',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'contact_intitule_organisation',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5acf62d6ac60b'
                    ),*/
                    '5b6d8ca99a802' => array(
                        'type' => 'column-meta',
                        'label' => 'Fonction',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'contact_fonction',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5b6d8ca99a802'
                    ),
                    '5acf62d6b35c0' => array(
                        'type' => 'column-meta',
                        'label' => 'Catégorie principale organisation',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_cat_orga_grp_categorie_principale_organisation',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5acf62d6b35c0'
                    ),
                    '5acf63255edf0' => array(
                        'type' => 'column-meta',
                        'label' => 'Catégorie secondaire organisation',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_cat_orga_grp_categorie_secondaire_organisation',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => 'Any CSO',
                        'name' => '5acf63255edf0'
                    ),
                    '5aeb713d9428e' => array(
                        'type' => 'column-meta',
                        'label' => 'Catégorie tertiaire organisation',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_cat_orga_grp_categorie_tertiaire_organisation',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => 'Any CSO',
                        'name' => '5aeb713d9428e'
                    ),
                    '5aeb713d9catprol4' => array(
                        'type' => 'column-meta',
                        'label' => 'Catégorie niveau 4',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_cat_orga_grp_cat_pro_level_4',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => 'Any CSO',
                        'name' => '5aeb713d9catprol4'
                    ),
                    '5aeb713d9catprol5' => array(
                        'type' => 'column-meta',
                        'label' => 'Catégorie niveau 5',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_cat_orga_grp_cat_pro_level_5',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => 'Any CSO',
                        'name' => '5aeb713d9catprol5'
                    ),
                    '5aeb713d9catprol6' => array(
                        'type' => 'column-meta',
                        'label' => 'Organisation',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_orga_name_grp_cat_pro_level_6',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => 'Any CSO',
                        'name' => '5aeb713d9catprol6'
                    ),
                    'orga_to_be_added_col' => array(
                        'type' => 'column-meta',
                        'label' => 'Organisation (si aucun rattachement)',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_orga_name_grp_organisation_name_to_be_added',
                        'character_limit' => '50',
                        'edit' => 'off',
                        'sort' => 'off',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => 'orga_to_be_added_col'
                    ),
                    '5acf6761304fa' => array(
                        'type' => 'column-meta',
                        'label' => 'Catégorie(s) produit(s)',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_ab_tags_grp_categories_produits',
                        'field_type' => 'array',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'name' => '5acf6761304fa'
                    ),
                    '5b6d8ca99b1a5' => array(
                        'type' => 'column-meta',
                        'label' => 'Profil utilisateur',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'type_profil_grp_type_profil_personne_organisation',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'name' => '5b6d8ca99b1a5'
                    ),
                    '5b6d8ca99d1c4' => array(
                        'type' => 'column-meta',
                        'label' => "Stade d'abonnement",
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'stade_abo_grp_stade_abo',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'name' => '5b6d8ca99d1c4'
                    ),
                    '5acf72567a949' => array(
                        'type' => 'column-meta',
                        'label' => 'À contacter',
                        'width' => '7',
                        'width_unit' => '%',
                        'field' => 'suivi_actions_to_be_contacted',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'on',
                        'filter_label' => 'À contacter',
                        'name' => '5acf72567a949'
                    ),
                    '5acf725676980' => array(
                        'type' => 'column-meta',
                        'label' => 'Label',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_ab_tags_grp_label_taxonomy',
                        'field_type' => 'array',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'name' => '5acf725676980'
                    ),
                    '5b6d8ca99b5f9' => array(
                        'type' => 'column-meta',
                        'label' => 'Fonction dans la filière',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_ab_tags_grp_fonction_dans_la_filiere',
                        'field_type' => 'array',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'name' => '5b6d8ca99b5f9'
                    ),
                    '5b6d8ca99b778' => array(
                        'type' => 'column-meta',
                        'label' => 'Échelle administrative',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_ab_tags_grp_echelle_administration',
                        'field_type' => 'array',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'name' => '5b6d8ca99b778'
                    ),
                    '5b6d8ca99b917' => array(
                        'type' => 'column-meta',
                        'label' => 'Restauration collective (délégation)',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_ab_tags_grp_type_restauration_collective',
                        'field_type' => 'array',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'name' => '5b6d8ca99b917'
                    ),
                    '5b6d8ca99ba92' => array(
                        'type' => 'column-meta',
                        'label' => 'Restauration collective (type de cuisine)',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'mv_ab_tags_grp_type_cuisine',
                        'field_type' => 'array',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'name' => '5b6d8ca99ba92'
                    ),
                    '5acf7256780b2' => array(
                        'type' => 'column-meta',
                        'label' => 'Email',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'social_email',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5acf7256780b2'
                    ),
                    '5acf72567960b' => array(
                        'type' => 'column-meta',
                        'label' => 'Site internet',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'social_site_web',
                        'field_type' => 'link',
                        'link_label' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => 'Site internet',
                        'name' => '5acf72567960b'
                    ),
                    '5b6d8ca99c25d' => array(
                        'type' => 'column-meta',
                        'label' => 'Adresse',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'adresse-grp_numero_&_rue',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => 'Toutes les villes',
                        'name' => '5b6d8ca99c25d'
                    ),
                    '5b6d8ca99c421' => array(
                        'type' => 'column-meta',
                        'label' => 'Ville',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'adresse-grp_city',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'on',
                        'filter_label' => 'Toutes les villes',
                        'name' => '5b6d8ca99c421'
                    ),
                    '5b6d8ca99c5e9' => array(
                        'type' => 'column-meta',
                        'label' => 'Code Postal',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'adresse-grp_code_postal',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'on',
                        'filter_label' => 'Tous les codes postaux',
                        'name' => '5b6d8ca99c5e9'
                    ),
                    '5b6d8ca99c797' => array(
                        'type' => 'column-meta',
                        'label' => 'Pays',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'adresse-grp_country',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'on',
                        'filter_label' => 'Tous les pays',
                        'name' => '5b6d8ca99c797'
                    ),
                    '5b6d8ca99c95b' => array(
                        'type' => 'column-meta',
                        'label' => 'Facebook',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'social_facebook',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5b6d8ca99c95b'
                    ),
                    '5b6d8ca99cb0e' => array(
                        'type' => 'column-meta',
                        'label' => 'Twitter',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'social_twitter',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5b6d8ca99cb0e'
                    ),
                    '5b6d8ca99ccc4' => array(
                        'type' => 'column-meta',
                        'label' => 'Tél fixe',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'telephone-grp_telephone_fixe',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5b6d8ca99ccc4'
                    ),
                    '5b6d8ca99ce69' => array(
                        'type' => 'column-meta',
                        'label' => 'Tél mobile',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'telephone-grp_telephone_mobile',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5b6d8ca99ce69'
                    ),
                    '5b6d8ca99d015' => array(
                        'type' => 'column-meta',
                        'label' => 'Fax',
                        'width' => '',
                        'width_unit' => '%',
                        'field' => 'telephone-grp_fax',
                        'field_type' => '',
                        'before' => '',
                        'after' => '',
                        'sort' => 'on',
                        'filter' => 'off',
                        'filter_label' => '',
                        'name' => '5b6d8ca99d015'
                    )
                ),
                'layout' => array(
                    'id' => '5b6d80d4b4d70',
                    'name' => 'All fields',
                    'roles' => array( 'administrator',
                        'editor',
                        'author' ),
                    'users' => false,
                    'read_only' => false
                )

            )
        ) );
    }
}
add_action( 'ac/ready', 'ac_custom_column_settings_ed081c20' );

