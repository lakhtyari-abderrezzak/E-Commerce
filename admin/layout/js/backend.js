$(function(){
    'use strict';

    $("select").selectBoxIt({
        autoWidth:false

    });


    $('[placeholder]').focus(function(){
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){
        $(this).attr('placeholder', $(this).attr('data-text'));
    })



// Create Asterisk for All Required Inputs 

$('input').each(function(){

    if($(this).attr('required') === 'required'){
        $(this).after('<span class="asterisk">*</span>');
    }
})

// On Hover Change Password Type In The Iput to Text Type 
var password = $('.pwd');
$(".show-pass").mouseup( function () {
        password.attr('type' , 'password');
});
$(".show-pass").mousedown(function () {
    password.attr('type' , 'text');
});

// Prevent Delet Button From Excuting Straight Away 

$(".confirm").click(function() {
    return confirm("Are You Sure You Want To Continue With This Action");
});

$(".catego h3").click(function (){
    $(this).next('.full-view').fadeToggle(300);
})
$(".option span").click(function(){
    $(this).addClass('active').siblings('span').removeClass('active');
    if($(this).data('view') === 'full'){
        $(".full-view").fadeIn(200);
        $(".option .eye").removeClass('fa-eye-slash').addClass("fa-eye");
    }else{
        $(".full-view").fadeOut(200);
        $(".option .eye").removeClass('fa-eye').addClass("fa-eye-slash");

    }
})
// Panel Toggle Fade 
$('.panel-info').click(function(){
    $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle();

    if($(this).hasClass('selected')){
        $(this).html('<i class="fa-solid fa-sort-down fa-lg"></i>');
    }else{
        $(this).html('<i class="fa-solid fa-sort-up fa-lg"></i>');
    }
})
})