import $ from 'jquery';
import {commonModal} from "../component/modal";

const {getRoute, httpRequest, trans} = require('./common');
const $mettingPointField = $("#metting_point_address");
$(document).ready(function (event) {
    console.log($mettingPointField)
    $mettingPointField.change((e)=>{
        const $targetId = $(e.target).val();
        let route = getRoute('search_instructor', {'id' : $targetId});
$.get(route, (data)=>{
})
    });
});