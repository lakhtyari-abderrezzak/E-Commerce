$(function () {
  "use strict";

  $("select").selectBoxIt({
    autoWidth: false,
  });
  $('.login-page h1 span').click(function() {
    $(this).addClass('selected').siblings().removeClass('selected'); 
    $('.login-page form').hide();
    $('.' + $(this).data("class")).fadeIn();
  })
  $("[placeholder]")
    .focus(function () {
      $(this).attr("data-text", $(this).attr("placeholder"));
      $(this).attr("placeholder", "");
    })
    .blur(function () {
      $(this).attr("placeholder", $(this).attr("data-text"));
    });

  // Create Asterisk for All Required Inputs

  $("input").each(function () {
    if ($(this).attr("required") === "required") {
      $(this).after('<span class="asterisk">*</span>');
    }
  });

  // On Hover Change Password Type In The Iput to Text Type
  var password = $(".pwd");
  $(".show-pass").mouseup(function () {
    password.attr("type", "password");
  });
  $(".show-pass").mousedown(function () {
    password.attr("type", "text");
  });

  // Prevent Delet Button From Excuting Straight Away

  $(".confirm").click(function () {
    return confirm("Are You Sure You Want To Continue With This Action");
  });

  $(".live").keyup(function (){
    $('.' + $(this).data("class")).text($(this).val());
  });
});
