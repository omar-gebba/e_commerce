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
    // show delete for child-categories
    $('.cats').hover(function() {
        $(this).find('.child-delete').fadeIn(700)
    }, function() {
        $('.child-delete').fadeOut()
    });
});