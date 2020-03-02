import $ from 'jquery';
import {commonModal} from "../component/modal";

const {getRoute, httpRequest, trans} = require('./common');

$(document).ready(function () {
    $(document).find('input.course-hour').change((e) => {
        var ajaxData = $(e.target).data();
        if ($(e.target).data('value') === 0) {
            $(e.target).data('value', 1);
       addCourse(ajaxData);
        } else {
            $(e.target).data('value', 0);
            removeCourse(ajaxData)
        }

    });
});

const addCourse = (ajaxData) => {
    const route = getRoute('add_course', {'instructor_id': ajaxData.instructor});
    $.ajax({
        url: route,
        data: ajaxData,
    }).done((data) => {
        console.log(data);
    })
};


const removeCourse = (ajaxData) => {
    const route = getRoute('remove_course', {'instructor_id': ajaxData.instructor});
    $.ajax({
        url: route,
        data: ajaxData,
    }).done((data) => {
        console.log(data);
    })
};