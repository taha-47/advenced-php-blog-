/*
===========================
Admin Jquery 
===========================
*/
/*global $, console,alert */

$(function () {

  'use strict';

  var editor = new FroalaEditor('#editor');


  /*-------- Post page---------*/

  //Tags feild
  $(".tag-input").on("keyup", function (e) {

    var keyboardKey = e.keyCode || e.which;

    if (keyboardKey === 188) {
      var inputValue = $(this).val().slice(0, -1);
      $(".added-tags").append('<span class="tags">' + inputValue + '<i class="fa fa-times-circle" aria-hidden="true"></i></span>');
      $(this).val('');
    }
  });

  /* Remove the tag */
  $(document).on("click", ".tags i", function () {
    $(this).parent().fadeOut(500, function () {
      $(this).remove();
    });
  })

  //Set the value info the input
  $(document).on("click", ".add-post, .update-post", function () {
    var tags = $(".tags").map(function () {
      return $(this).text();
    }).get()
    var tags = tags.join(',');
    $('.tags-values').val(tags);
  })

  /*--------- Menu page ------*/
  $(".disabled *").attr("disabled", "disabled").off('click');//Disable all button when we don't have menu

  /* Select all checkbox functionnality */
  $(".select-all").click(function () {
    var n = $(".menu-select:checked").length;
    if (n > 0) {
      $(".menu-select").prop("checked", false);
    } else {
      $(".menu-select").prop("checked", true);
    }
  });

  $(".btn-add-menu").click(function () {
    var selectedMenuItem = new Array();
    var n = $(".menu-select:checked").length;
    if (n > 0) {
      $(".menu-select:checked").each(function () {
        selectedMenuItem.push($(this).val());
      });
      $.each(selectedMenuItem, function (i, v) {
        var newArr = v;
        var menuList = $(".menu-list");

        if (menuList.text() == "") {
          $('.menu-list').append('<div class="menu-item">' + newArr + '</div>');
        } else {
          if (v.includes(menuList.text())) {
            console.log(v + "exist");
          } else {
            $('.menu-list').append('<div class="menu-item">' + newArr + '<span class="close"><i class="fa fa-times-circle" aria-hidden="true"></i></span></div>');
          }
        }
      });

      $(".menu-select").prop("checked", false);
    }
  });

  /* Remove the item added to the menu */
  $(document).on("click", ".menu-item .close", function () {
    $(this).parent().fadeOut(500, function () {
      $(this).remove();
    });
  })

  /* Drag and drop menu item */

  $(".menu-list").sortable({
    containment: "parent",
    cursor: "move",
    opacity: .5,
  });


  /* Get value from divTag */
  function getValues() {
    $(document).on("click", ".btn-add-menu, .save-menu", function () {
      var menuItems = $(".menu-item").map(function () {
        return $(this).text();
      }).get()
      var multiMenus = menuItems.join(',');
      $('#menuContent').val(multiMenus);
    })
  }
  getValues();


});