import $ from 'jquery';
import {commonModal} from "../component/modal";
const {getRoute, httpRequest, trans} = require('./common');
const $form =   $("form#instructor-hours");


$(document).ready(function (event) {
  const $packageId = $('.course-number');

  $form.find('input').change((e) => {
    var $target = $(e.target);
    const $package = $form.data('package');
    if (parseInt($.trim($package)) === 1) {
      if ($target.is(':checked')) {
        const $cardId = $packageId.data('card-id');
        const $creditId = $packageId.data('credit-id');
        const $route = getRoute('add_package_course', {'card_id': $cardId, 'credit_id': $creditId});
       $.ajax({
         url: $route,
         data: $.extend( $form.data(), $target.data()),
         success: (data)=>{
           console.log(data.message);
           if(data.message ==='success'){
             $(document).find('.add-course-error').html();
             $(".h1.course-number").html(data.restCourseNumber +  'h / (' + data.courseNumber +' h )');
           }else {
             $(document).find('.add-course-error').html(trans('package.add.error')).css('color', 'red');
             commonModal
               .setTitle()
               .showMessage('vous avez plus de credit')
               .initButtons()
               .addCancelButton(trans('app.action.close'))
               .showModal();
         }
         }
       });
      }

      if (!$target.is(':checked')) {
        const $cardId = $packageId.data('card-id');
        const $creditId = $packageId.data('credit-id')
        var route = getRoute('remove_package_course', {'card_id': $cardId, 'credit_id': $creditId});
        $.get(route,
          $.extend( $form.data(), $target.data()),
          function (data) {
            $(".h1.course-number").html(data.restCourseNumber +  'h / (' + data.courseNumber +' h )');
          });
      }

    }
  });
});
