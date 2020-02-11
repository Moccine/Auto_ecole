import $ from 'jquery';
import {commonModal} from "../component/modal";


const {getRoute, httpRequest, trans} = require('./common');

$(document).ready(function () {
  const $courseInfo = $('table').find('.course-information');
  $courseInfo.click((event)=>{
    var $target = $(event.target);
    const $courseInfoId =  $target.data('course-id');
   const $courseInfoRoute = getRoute('course_info', {course_id: $courseInfoId});
 $.ajax({
   url: $courseInfoRoute,
   method: 'GET'
 }).done((data)=>{
   console.log(data);
   commonModal
     .setTitle()
     .showMessage(data.template)
     .initButtons()
     .addCancelButton(trans('app.action.close'))
     .showModal();
 });

  });
});
