$(function() {
    'use strict';

    $('[placeholder]').focus(function() {

        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', ' ');

    }).blur(function() {

        $(this).attr('placeholder', $(this).attr('data-text'));

    });

   // add asterisk for inputs which required
    $('input').each(function () {
        if ($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }
    }); 
    $('.fa-eye').hover(function () {
        $('.password').attr('type', 'text');
    } , function () {
        $('.password').attr('type', 'password');
    });

// conformation message when delete members

    $('.confirm').click(function () {
        return confirm('Are You Sure you want to delete');
    });
    $('div.front-login h1 span').click(function () {
        $(this).addClass("active").siblings().removeClass('active');
        $('div.front-login form').hide();
        $('.' + $(this).data('class')).fadeIn(600);
    });
    $('.name-review').keyup(function() {
        $('.live-review h3').text($(this).val());
    });
    // $('.desc-review').keyup(function() {
    //     $('.live-review p').text($(this).val());
    // });
    // $('.price-review').keyup(function() {
    //     $('.live-review span').text('$' + $(this).val());
    // });
    $('.live').keyup(function () {
       $($(this).data('class')).text($(this).val());
    });
    $('.hide-msg').click(function() {
        $(this).parent().hide();
    })
    // for hide and show custom dropdown of child categories
    $('.show-menue').hover(function () {
        $($(this).data('class')).slideDown(500)},
        function () {
            $($(this).data('class')).hide();
     });
});