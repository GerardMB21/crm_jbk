<template>

    <div class="modal fade" id="userGroupModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="row g-3" @submit.prevent="formController(url, $event)">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ text }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row">
                        <input v-model="model.id" type="hidden" name="id" id="id">
                        <div class="col-md-6">
                            <label for="company_id" class="form-label">Compañía:</label>
                            <select class="form-select" v-model="model.company_id" name="company_id" id="company_id"
                                @focus="$parent.clearErrorMsg($event)">
                                <option value="" selected disabled>Seleccionar</option>
                                <option v-for="company in companies" :value="company.id" :key="company.id">{{
                                    company.name }}</option>
                            </select>
                            <div id="company_id-error" class="error invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre:</label>
                            <input v-model="model.name" type="text" class="form-control" id="name" name="name"
                                @focus="$parent.clearErrorMsg($event)">
                            <div id="name-error" class="error invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="ip" class="form-label">IP:</label>
                            <input v-model="model.ip" type="text" class="form-control" id="ip" name="ip"
                                @focus="$parent.clearErrorMsg($event)">
                            <div id="ip-error" class="error invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white border-dark" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" :class="'btn btn-' + color">{{ text }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { valHooks } from 'jquery';
import { values } from 'lodash';


export default {
    props: {
        url: {
            type: String,
            default: ''
        },
        companies: {
            type: Array,
            default: ''
        }
    },
    data() {
        return {
            model: {
                id: '',
                company_id: '',
                name: '',
                ip: ''
            },
            text: '',
            color: ''
        }
    },
    created() {

    },
    mounted() {
        EventBus.$on('create_modal', function () {

            this.model.id = '';
            this.model.company_id = '';
            this.model.name = '';
            this.model.ip = '';

            this.text = "Crear"
            this.color = "success";

            $('#userGroupModal').modal('show');
        }.bind(this));

        EventBus.$on('edit_modal', function (group) {

            this.model.id = group.id;
            this.model.company_id = group.company_id;
            this.model.name = group.name;
            this.model.ip = group.ip;

            this.text = "Actualizar"
            this.color = "primary";

            $('#userGroupModal').modal('show');
        }.bind(this));
    },
    methods: {
        formController: function (url, event) {
            var vm = this;

            var target = $(event.target);
            var url = url;
            var fd = new FormData(event.target);

            EventBus.$emit('loading', true);

            // EventBus.$emit('loading', true);
            axios.post(url, fd, {
                headers: {
                    'Content-type': 'application/x-www-form-urlencoded',
                }
            }).then(response => {
                EventBus.$emit('loading', false);
                this.$parent.alertMsg(response.data);

            }).catch(error => {
                EventBus.$emit('loading', false);
                console.log(error.response);
                var obj = error.response.data.errors;
                $('.modal').animate({
                    scrollTop: 0
                }, 500, 'swing');
                $.each(obj, function (i, item) {
                    let c_target = target.find("#" + i + "-error");
                    if (!c_target.attr('data-required')) {
                        let p = c_target.prev();
                        p.addClass('is-invalid');
                    } else {
                        c_target.css('display', 'block');
                    }
                    c_target.html(item);
                });
            });
        },

    }
}
</script>
