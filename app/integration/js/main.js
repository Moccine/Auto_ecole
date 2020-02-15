import $ from 'jquery';
import 'bootstrap'
import 'datatables.net';
import './location';
import './cardDisplay';
import './checkout'
import './packageCourse'

const {getRoute, httpRequest, trans} = require('./common');
const $mettingPoint = $(".instructor");
var price = 0;
const $form =   $("form#instructor-hours");

$(document).ready(function () {
  $form.find('input').change((e) => {
    var $target = $(e.target);
    const $package = $form.data('package');
    if (parseInt($.trim($package)) !== 1) {
      if ($target.is(':checked')) {
        var $instructor_id = $target.data('instructor');
        var route = getRoute('add_course', {'instructor_id': $instructor_id});
        $.get(route,
          $.extend( $form.data(), $target.data()),
          function (data) {
          $(".price").html(data.price.toFixed(2) + '€');
        });
      }

      if (!$target.is(':checked')) {
        var $instructor_id = $target.data('instructor');
        var route = getRoute('remove_course', {'instructor_id': $instructor_id});
        $.get(route,
          $.extend( $form.data(), $target.data()),
          function (data) {
          $(".price").html(data.price.toFixed(2) + '€');
        });
      }
    }
  });

  $('#example').DataTable({
    "pageLength": 50,
    responsive: true
  });
 $("a[href*='#driving-cards']").click((e)=>{
    $('html, body').animate({scrollTop: $('#driving-cards').offset().top}, 800);
  });
  $("a[href*='#best-offers']").click((e)=>{
    $('html, body').animate({scrollTop: $('#best-offers').offset().top}, 800);
  });



});
/*
import 'bootstrap'

import '../theme/theme-js/vendor/jquery-1.12.0.min';
import '../theme/theme-js/vendor/modernizr-2.8.3.min.js';
import '../theme/theme-js/imageLoaded';

import '../theme/theme-js/bootstrap.bundle.min';
import '../theme/theme-js/plugins';
import '../theme/theme-js/wow.min';
import '../theme/theme-js/ajax-mail';
import '../theme/theme-js/main';
*/

