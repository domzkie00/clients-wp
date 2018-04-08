jQuery(function($){

    var searchRequest;
    $('.member-email-input').autoComplete({
        minChars: 2,
        source: function(email, suggest){
            try { 
                searchRequest.abort();
            } catch(e){}
                searchRequest = $.post(cwpscript.ajaxurl, { search: email, action: 'get_clients_wp_users' }, function(res) {
                    suggest(res.data);
                }
            );
        }
    });

    $('input[name=_is_new_member]').on('click', function(e){
        if ($(this).is(':checked')) {
            $('.is-new-member-field').show();
        } else {
            $('.is-new-member-field').hide();
        }
    });

    $('#cwp-add-shortcode').on('click', function(e) {
        e.preventDefault();
        $(this).prop('disabled', 'disabled');
        let cloned_data = $('#referer-input').clone();
        cloned_data.find('input[type=text]').val('');
        cloned_data.append('<td><a href="#" class="button button-secondary remove-map">remove</a><td>')
        $('#cwp-shortcode-table tbody').append(cloned_data);
        $(this).removeProp('disabled');
    });

    $('.remove-map').live('click', function(e){
        e.preventDefault();
        $(this).parent().parent().fadeOut('slow');

        let cur = this
        setTimeout(function(){
            $(cur).parent().parent().remove();
        }, 1000)
    });

    $('.cwp-label').live('keyup', function(e){
        let value = $(this).val();
        let final_value = value.replace(/\s+/g, '_').toLowerCase();
        $(this).parent().next().children('.cwp-shortcode').val('[cwp_' + final_value + ']');
    });

    $('select[name=_clients_page_shortcode]').change(function(e){
        $('.clients_dropdown-wrapper .spinner').show().css({'visibility':'visible'});
        $('select[name=_clients_page_client]').hide();
        $.post(
            cwpscript.ajaxurl,
            { 
            data: { 
              '_shortcode' : $(this).val(),
              '_client': (cwpscript.selected_client) ? cwpscript.selected_client : ''
            },
            action : 'get_clients_not_in_shortcode'
            }, 
            function( result, textStatus, xhr ) {
              let output = generate_clients_options(JSON.parse(result));
              $('select[name=_clients_page_client]').html(output);
              $('select[name=_clients_page_client]').show();
              $('.clients_dropdown-wrapper .spinner').hide().css({'visibility':'hidden'});
            }).fail(function(error) {
              $('select[name=_clients_page_client]').show();
              $('.clients_dropdown-wrapper .spinner').hide().css({'visibility':'hidden'});
          });
    });

    function generate_clients_options(posts) {
        let output = '';
        $.each(posts, function(key, post) {
            output += '<option value="' + post.ID + '">' + post.post_title + '</option> \n';
        })

        return output;
    }

    $(document).on('change', '#integration-select-type', function(){
        $('#integration-select-folder').fadeIn();
    });
});