$(function () {


    'use strict';




    // $('#particle-id').particleground();



    $('[placeholder').focus(function(){
            
        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');

    }).blur(function (){

        $(this).attr('placeholder',$(this).attr('data-text'));
 
    });


    //switch between Signin & SignUp

    $('#sign-up').click(function () {

        $('#login').hide();
        $('#signup').fadeIn(600);

    });

    $('#log-in').click(function () {

        $('#signup').hide();
        $('#login').fadeIn(600);

    });

    // close X to hidden alert 

    $('i#close').click(function(){
         
        $('.custom-error').fadeOut(100);
        $('.navbar-right .alert-approve').fadeOut(100);

    });


    // eye icon to show password in form 


    $(' #showpass ').mouseenter(function(){
        $('#secret').attr('type','text');
    });

    $(' #showpass ').mouseleave(function(){
        $('#secret').attr('type','password');
    });


    $('#showpass2 ').mouseenter(function(){
        $('#secret2').attr('type','text');
    });

    $(' #showpass2 ').mouseleave(function(){
        $('#secret2').attr('type','password');
    });

    $(' #showpass3 ').mouseenter(function(){
        $('#secret3').attr('type','text');
    });

    $(' #showpass3 ').mouseleave(function(){
        $('#secret3').attr('type','password');
    });



  



    


    //show confirm message on delete button

    $('.confirm').click(function(){
        
        return confirm('Are You Sure?');

        });




});