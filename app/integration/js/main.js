import $ from 'jquery';
import 'bootstrap'
import 'datatables.net';
import './location';
import './cardDisplay';
import './checkout'
import './packageCourse';
import './addUnityCourse';
import './admin';

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

  var t =$('#example').DataTable({
    "order": [[ 1, 'asc' ]],
    "pageLength": 50,
    responsive: true,
    "columnDefs": [
      { "width": "3%", "targets": 0 }
    ],
    language: {
      processing:     "Traitement en cours...",
      search:         "Rechercher :",
      lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
      info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
      infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
      infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
      infoPostFix:    "",
      loadingRecords: "Chargement en cours...",
      zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
      emptyTable:     "Aucune donnée disponible dans le tableau",
      paginate: {
        first:      "Premier",
        previous:   "Pr&eacute;c&eacute;dent",
        next:       "Suivant",
        last:       "Dernier"
      },
      aria: {
        sortAscending:  ": activer pour trier la colonne par ordre croissant",
        sortDescending: ": activer pour trier la colonne par ordre décroissant"
      }
    }
  });
  t.on( 'order.dt search.dt', function () {
    t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
      cell.innerHTML = i+1;
    } );
  } ).draw();
 $("a[href*='#driving-cards']").click((e)=>{
    $('html, body').animate({scrollTop: $('#driving-cards').offset().top}, 800);
  });
  $("a[href*='#best-offers']").click((e)=>{
    $('html, body').animate({scrollTop: $('#best-offers').offset().top}, 800);
  });


  $('.js-datepicker').datepicker({
    format: 'yyyy-mm-dd',
    weekStart: 1,
    daysOfWeekHighlighted: "6,0",
    autoclose: true,
    todayHighlight: true,
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

