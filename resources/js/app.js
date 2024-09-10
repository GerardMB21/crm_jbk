/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Swal from 'sweetalert2';
import EventBus from './event-bus';

window.Swal = Swal;
window.EventBus = EventBus;
import $ from 'jquery';

// Asegurarse de que jQuery esté disponible globalmente
window.$ = window.jQuery = $;


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    methods: {
        clearErrorMsg: function (event) {
            var elem = $(event.target);
            if (elem.hasClass('is-invalid')) {
                elem.removeClass('is-invalid');
                elem.next().html('');
            } else if (elem.parent().hasClass('is-invalid')) {
                elem.parent().removeClass('is-invalid');
                elem.parent().next().html('');
            } else {
                let id = elem.attr('id');
                let error_elem = elem.parents('.form-group').find('#' + id + '-error');
                if (error_elem.is(':visible')) {
                    error_elem.html('');
                }
            }
        },
        alertMsg: function (data) {
            if (data.type == 0) {
                return;
            } else if (data.type == 1) {
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: "success",
                    // timer: 2000,
                    heightAuto: false,
                })
            } else if (data.type == 2) {
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: "warning",
                    heightAuto: false,
                })
            } else if (data.type == 3) {
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: "success",
                    timer: 2000,
                    heightAuto: false
                }).then((confirmed) => {
                    window.location = data.url;
                })
            } else if (data.type == 4) {
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar!',
                    heightAuto: false
                }).then(function (result) {
                    if (result.value) {
                        axios.post(data.url, { id: data.id }).then(response => {
                            Swal.fire({
                                title: response.data.title,
                                text: response.data.msg,
                                icon: "success",
                                confirmButtonText: "OK",
                                heightAuto: false,
                            });
                            EventBus.$emit('refresh_table');
                        }).catch(error => {
                            console.log(error.response.data);
                        });
                    }
                });
            } else if (data.type == 5) {
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: "error",
                    heightAuto: false,
                })
            }
        },
    }
});