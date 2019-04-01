jQuery( document ).ready(function( $ ) {

    // $('.mv-contacts, .mv-social').wrapAll('<div class="col col-1"></div>');
    // $('.mv-logo, .mv-cpo-grp, .mv-cso-grp, .mv-typologie ').wrapAll('<div class="col col-2"></div>');
    // $('.mv-fonction, .mv-cp-grp, .mv-suivi_actions ').wrapAll('<div class="col col-3"></div>');

    var $followUpWrapper = $( ".mv-followup-wrap" );
    $followUpWrapper.dialog({
        autoOpen: false,
        draggable: false,
        modal: true,
        width: '85%',
        appendTo: '#acf-group_5ad78f288b311 .inside.acf-fields'
    });


    $( ".dialog-opener" ).click(function() {
        $followUpWrapper.dialog( "open" );

        var $nom = $('.mv-nom input').val();
        var $firstName = $('.mv-firstname input').val();
        if (!$nom == "" && !$firstName == "") {
            $('.mv-followup-wrap > .acf-label label').text('Contact: ' + $firstName + ' ' + $nom);
        }

    });

// disable dragging and collapse
    $('.postbox .hndle').unbind('click.postboxes');
    $('.postbox .handlediv').remove();
    $('.postbox').removeClass('closed');


    $('body').append('' +
        '<div class="post-loader">' +
        '<svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ripple"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><g> <animate attributeName="opacity" dur="2s" repeatCount="indefinite" begin="0s" keyTimes="0;0.33;1" values="1;1;0"></animate><circle cx="50" cy="50" r="40" stroke="#8ECCC0" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="r" dur="2s" repeatCount="indefinite" begin="0s" keyTimes="0;0.33;1" values="0;22;44"></animate></circle></g><g><animate attributeName="opacity" dur="2s" repeatCount="indefinite" begin="1s" keyTimes="0;0.33;1" values="1;1;0"></animate><circle cx="50" cy="50" r="40" stroke="#FBBA30" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="r" dur="2s" repeatCount="indefinite" begin="1s" keyTimes="0;0.33;1" values="0;22;44"></animate></circle></g></svg>' +
        '</div>');
    $(".post-loader").css("display", "none");

    // Add default 'Select one'
    if(!$( '.mv-cpo select option:selected' ).length){
        $( '.mv-cpo select option[value=""]' ).attr({ selected: 'selected', disabled: 'disabled'});
        $(".post-loader").css("display", "none");
    }

    var catLevelClasses = ['.mv-cpo','.mv-cso','.mv-cto','.mv-cl4','.mv-cl5'];

    // add contact to organisation button
    var orga_query_string = "mv_orga=";
    if(window.location.href.indexOf(orga_query_string)!=-1){
        $.each(catLevelClasses,function(index,val){
            $(val+' select')
                .prop({
                    disabled:true
                })
                .find('option')
                .attr({
                    selected: 'selected'
                })
        });
    }

    // nomenclature level fields in contact edit page
    $.each(catLevelClasses,function(index,val){
        $(val+' select').change(function(){
            if(catLevelClasses[catLevelClasses.length-1]!=val){
                var selected_val = '',
                    selected_cat_title = '';

                $(val+' select option:selected').each(function(){
                    selected_val = $(this).val();
                    selected_cat_title = $(this).text();
                })

                for(var i = index+1;i<catLevelClasses.length;i++){
                    reset_fields([catLevelClasses[i]]);
                }

                $('.mv-ab-orga-name-nomenclature select').html($('<option></option>').val('').html('Choisir').prop({
                    'selected': false
                }));

                if(selected_cat_title != '- Choisir -' && selected_cat_title != ''){
                    $(".post-loader").css("display", "block");
                    // Send AJAX request
                    var data = {
                        action: 'catpro_category',
                        catpro_nonce: catpro_vars.catpro_nonce,
                        selected_catpro: selected_val,
                        cat_level: index+1
                    };

                    // Get response and populate select field
                    $.ajax({
                        url: ajaxurl,
                        data: data,
                        type: 'POST',
                        success: function (data) {
                            set_field_options(data,catLevelClasses[index+1]);
                            $(".post-loader").css("display", "block");
                        },
                        complete: function (xhr) {
                            var data = $.parseJSON(xhr.responseText);
                            if(data != null){
                                var firstDataVal = '';
                                $.each(data[0],function(key,val){
                                    firstDataVal = val;
                                    return false;
                                });
                                //console.log(data.length + '; ' + firstDataVal);
                                //console.log(data[0]);
                                if(data.length!==1 || firstDataVal!=='-'){
                                    //console.log('hide loader');
                                    $(".post-loader").css("display", "none");
                                }
                                else{
                                    //console.log('show loader');
                                    $(".post-loader").css("display", "block");
                                }
                            }
                            else{
                                $(".post-loader").css("display", "none");
                            }
                        }
                    });
                }
            }
        });
    });


    function set_field_options(data,fieldClass){
        //console.log(data);
        if (typeof data !== "undefined") {

            if(data ==  null || data.length == 0) {
                $(fieldClass + ' select').prop({disabled:true}).html($('<option></option>').val('').html(''));
            }
            else{
                $(fieldClass + ' select').html($('<option></option>').val('').html('- Choisir -').attr({
                    selected: 'selected'
                }));
            }

            if(data != null){
                if(data.length>1){
                    // Add pro categories to select field options
                    $.each(data, function (val, text) {
                        $.each(this, function (v, t) {
                            /// do stuff
                            $(fieldClass + ' select').append($('<option></option>').val(v).html(t));
                        });
                    });
                }
                else if(data.length == 1){
                    $.each(data, function (val, text) {
                        $.each(this, function (v, t) {
                            /// do stuff
                            $(fieldClass + ' select').html($('<option></option>').val(v).html(t).attr({
                                selected: 'selected'
                            }));
                            $(".post-loader").css("display", "block");
                            $(fieldClass + ' select').change();
                        });
                    });
                }
            }

            // Enable 'Select Area' field
            if($(fieldClass + ' select').find('option').length>1){
                $(fieldClass + ' select').prop({disabled:false});
            }
        } else {
            $(fieldClass + ' select').prop({disabled:true}).html($('<option></option>').val('').html(''));
        }
    }

    function reset_fields(classArray){
        $.each(classArray,function(index,val){
            $(val+' select').find('option')
                .remove()
                .end()
                .append('<option value=""></option>')
                .prop({disabled:true})
                .val('');
        });
    }

    // organisation name autocomplete
    $('.mv-ab-orga-name-nomenclature select').change(function(){
        var selected_val = $(this).val();
        $('.organisation-name-to-be-added input').val('');

        var data = {
            action: 'mv_ab_prev_cats',
            catpro_nonce: catpro_vars.catpro_nonce,
            selected_catpro: selected_val,
        };

        // Get response and populate select field
        $.ajax({
            url: ajaxurl,
            data: data,
            type: 'POST',
            success: function (data) {
                console.log(data);
                if(data!=null){
                    $.each(catLevelClasses,function(index,val){

                        $(val + ' select').prop({disabled:true});

                        if(val != ".mv-cpo"){
                            $(val + ' select').html($('<option></option>').val(data[index]['id']).html(data[index]['name']).attr({
                                selected: 'selected'
                            }));
                        }
                        else if(val == '.mv-cpo'){
                            $(val + ' select').val(data[index]['id']);
                        }
                    });
                }
            },
        });
    });

    // not found orga name text input
    $('.organisation-name-to-be-added input')
        .on('keypress', function(event) {
            $('.mv-ab-orga-name-nomenclature select').html($('<option></option>').val('').html('Choisir').prop({
                'selected': false
            }));
            if($(this).val() == ''){
                $(catLevelClasses[0] + ' select').prop({disabled:false}).val('');
                for(var i = 1;i<catLevelClasses.length;i++){
                    reset_fields([catLevelClasses[i]]);
                }
            }
            //$('.mv-ab-orga-name-nomenclature select').change();
            $(catLevelClasses[0] + ' select').prop({disabled:false});
        })



    // add reset button to contacts list table filters
    if($('.post-type-mv_address_book').length & $('.wp-list-table').length){
        $('#posts-filter .tablenav.top .actions').last().append('<a class="ab-reset-button button">Reset</a>');

        $('body').on("click",".ab-reset-button",function(){
            window.location = window.location.protocol + "//" + window.location.hostname + '/wp-admin/edit.php?post_type=' + 'mv_address_book';
        });
    }

    // init select 2
    if( $( '.multi-select' ).length > 0 ) {

        $( '.multi-select' ).select2({
            placeholder: 'Select'
        });

        // $( document.body ).on( "click", function() {
        //     $( '.multi-select' ).select2({
        //         placeholder: 'Select'
        //     });
        // });

    }

    if (typeof acf !== "undefined") {
        // get localized data
        var postID = acf.get('post_id');
        acf.add_filter('select2_ajax_results', function( json, params, instance ){

            if (json['results']) {
                $.map( json['results'], function( obj ) {
                    if (obj['text']) {
                        obj['text'] = obj['text'].replace(/(- )*/g, "");
                    }
                    return obj;
                });
            }

            // return
            return json;
        });
    }


});




// jQuery(document).ready(function($) {
//
//     // disable CSO field if no options
//     // $(".mv-cso select").each(function () {
//     //     this.disabled = $('option', this).length < 2;
//     // });
//
//     // disable CTO field if no options
//     // $(".mv-cto select").each(function () {
//     //     this.disabled = $('option', this).length < 2;
//     //
//     // });
//
// });





