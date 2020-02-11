// for $('#commonModal')
const $ = require('jquery');
const {trans} = require('../js/common');

const $modal = $('#commonModal');
const $title = $('#commonModalTitle');
const $error = $('#commonModalError');
const $message = $('#commonModalMessage');
const $spinner = $('#commonModalSpinner');
const $buttons = $('#commonModalButtons');
const $closer = $('#commonModalCloser');
/*
var datatable_jquery_script = document.createElement("script");
datatable_jquery_script.src = "vendor/datatables/jquery.dataTables.min.js";
document.body.appendChild(datatable_jquery_script);
setTimeout(function(){
  var datatable_bootstrap_script = document.createElement("script");
  datatable_bootstrap_script.src = "vendor/datatables/dataTables.bootstrap4.min.js";
  document.body.appendChild(datatable_bootstrap_script);
},100);
*/

const commonModal = {

  showModal(callback) {
    callback = callback || (() => {});

    $modal.modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    }, callback());
  },

  hideModal() {
    $modal.modal('hide');
  },

  toggleMessage() {
    $message.toggle();
    return this;
  },

  setTitle(title) {
    $title.html(title);
    return this;
  },

  setMessage(message) {
    $message.html(message);
    return this;
  },

  setError(error) {
    $error.html(error);
    return this;
  },

  showMessage(message, showCloser = true) {
    $error.hide();
    $spinner.hide();
    if (message) {
      this.setMessage(message);
    }
    if (showCloser) {
      $closer.show();
    }
    $message.show();
    return this;
  },

  showError(error) {
    $message.hide();
    $spinner.hide();
    if (error) {
      this.setError(error);
    }
    $closer.show();
    $error.show();
    return this;
  },

  showSpinner() {
    this
      .setTitle(trans('modal.common.title.in_progress'))
      .initButtons();
    $message.hide();
    $error.hide();
    $closer.hide();
    $spinner.show();
    return this;
  },

  initButtons() {
    $buttons.html('');
    return this;
  },

  addBuntton(label, callback = null, btnClass = 'btn-default') {
    if (callback) {
      $buttons.append(`<button type="button" class="btn ${btnClass}"><i class="zmdi zmdi-check"></i> ${label}</button>`);
      $buttons.find('button:last').click(callback);
    } else {
      $buttons.append(`<button type="button" class="btn ${btnClass}" data-dismiss="modal"><i class="zmdi zmdi-close"></i>${label}</button>`);
    }
    return this;
  },

  addCancelButton(label, callback = null) {
    return this.addBuntton(label, callback, 'btn-tertiary');
  },

  addConfirmButton(label, callback = null) {
    return this.addBuntton(label, callback, 'btn-primary');
  },

  getModalObject() {
    return $modal;
  },

  showErrorModal(error = trans('common.error.general'), title=trans('modal.common.title.error')) {
    this
      .setTitle(title)
      .showError(error)
      .initButtons()
      .addConfirmButton(trans('action.close'))
      .showModal();
  },

  showAjaxErrorModal(jqXHR) {
    let error = (jqXHR.responseJSON && jqXHR.responseJSON.message) ? jqXHR.responseJSON.message : jqXHR.responseText;
    this.showErrorModal(error);
  },

  showSuccessModal(success = trans('common.success.general'), title=trans('modal.common.title.success'), confirmButtonCallback = null) {
    this
      .setTitle(title)
      .showMessage(`<span class="alert alert-success "> ${success} </span>`)
      .initButtons()
      .addConfirmButton(trans('action.close'), confirmButtonCallback)
      .showModal();
  },

};

export {commonModal};
