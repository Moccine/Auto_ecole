import $ from 'jquery';
import {commonModal} from "../component/modal";
const {getRoute, httpRequest, trans} = require('./common');
$(document).ready(function () {
$('button.activate-user').click((event)=>{
    const target = $(event.target);
 const userId = target.data("user");
    const route = getRoute('admin_activate_user', {'id': userId});
    const routeForm = getRoute('admin_activate_form_user', {'id': userId});
    commonModal.showSpinner();
    $.get(routeForm, (data)=>{
        commonModal
            .setTitle('Modifier ')
            .showMessage(data.template)
            .initButtons()
            .addCancelButton(trans('app.action.close'))
            .addConfirmButton(trans('app.action.save'), ()=>{
                //commonModal.showSpinner();
                event.preventDefault();
                var form = document.getElementById('activate-form-user');
                $.ajax({
                    method: 'POST',
                    url: route,
                    data: new FormData($(form)[0]),
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                }).done((data) => {
                    if (data.success === true) {
                        commonModal.hideModal();
                    }
                });
                commonModal.hideModal();

            })
            .showModal();
    });



})

});