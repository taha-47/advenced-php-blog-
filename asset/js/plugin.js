/*
===========================

===========================
*/
/*global $, console,alert */

$(function () {

  'use strict';

  /* --Breaking news-- */
  function breakingNews() {

    if ($(".main-breaking").length) {
      var n = 635;
      var lschild = $(".Breaking-scroll li:last-child").position();
      setInterval(function () {

        $(".Breaking-scroll").css({
          'left': n + 'px'
        });
        n += -1;
        if (n < -lschild.left - 350) {
          n = 635;
        }
      }, 25);
    }
  }
  breakingNews();

});