<template>

    <div class="modal fade" id="horarioModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                            <label for="name" class="form-label">Nombre:</label>
                            <input v-model="model.name" type="text" class="form-control" id="name" name="name"
                                @focus="$parent.clearErrorMsg($event)">
                            <div id="name-error" class="error invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="tolerancia" class="form-label">Tolerancia (mins):</label>
                            <div class="input-group">
                                <input v-model="model.tolerancia" type="text" class="form-control" id="tolerancia"
                                name="tolerancia" @focus="$parent.clearErrorMsg($event)">
                                <div id="tolerancia-error" class="error invalid-feedback"></div>
                            </div>
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

export default {
    props: {
        url: {
            type: String,
            default: ''
        }

    },
    data() {
        return {
            model: {
                id: '',
                name: '',
                tolerancia: ''

            },
            text: '',
            color: ''
        }
    },
    created() {

    },
    mounted() {
        EventBus.$on('create_modal', function (data) {

            this.model.id = '';
            this.model.name = '';
            this.model.tolerancia = '';

            this.text = "Crear"
            this.color = "success";

            $('#horarioModal').modal('show');
        }.bind(this));
        EventBus.$on('edit_modal', function (horario) {

            this.model.id = horario.id;
            this.model.name = horario.name;
            this.model.tolerancia = '';

            this.text = "Actualizar"
            this.color = "primary";

            $('#horarioModal').modal('show');
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
