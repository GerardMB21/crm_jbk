<template>
    <div class="container mt-2 p-5 bg-white border rounded">
        <form class="row g-3" @submit.prevent="formController(url, $event)">
            <input v-model="model.id" type="hidden" name="id" id="id">
            <div class="col-md-4">
                <label for="name" class="form-label">Empresa:</label>
                <input v-model="model.name" type="text" class="form-control" id="name" name="name"
                    @focus="$parent.clearErrorMsg($event)">
                <div id="name-error" class="error invalid-feedback"></div>
            </div>
            <div class="col-md-4">
                <label for="contact" class="form-label">Contacto:</label>
                <input v-model="model.contact" type="text" class="form-control" id="contact" name="contact"
                    @focus="$parent.clearErrorMsg($event)">
                <div id="contact-error" class="error invalid-feedback"></div>
            </div>
            <div class="col-md-4">
                <label for="pais" class="form-label">País:</label>
                <select class="form-select" v-model="model.pais" name="pais" id="pais"
                    @focus="$parent.clearErrorMsg($event)">
                    <option value="" selected disabled>Seleccionar</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Chile">Chile</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="España">España</option>
                    <option value="Global">Global</option>
                    <option value="México">México</option>
                    <option value="Perú">Perú</option>
                    <option value="Venezuela">Venezuela</option>
                </select>
                <div id="pais-error" class="error invalid-feedback"></div>
            </div>
            <div class="col-md-4">
                <label for="asist_type" class="form-label">Tipo de asistencia:</label>
                <select class="form-select" v-model="model.asist_type" name="asist_type" id="asist_type"
                    @focus="$parent.clearErrorMsg($event)">
                    <option value="" selected disabled>Seleccionar</option>
                    <option value="Login/Logout al Sistema">Login/Logout al Sistema</option>
                    <option value="Huella dactilar">Huella dactilar</option>
                </select>
                <div id="asist_type-error" class="error invalid-feedback"></div>
            </div>
            <div class="col-md-4">
                <label for="sufijo" class="form-label">Sufijo de usuario:</label>
                <input v-model="model.sufijo" type="text" class="form-control" id="sufijo" name="sufijo"
                    placeholder="@example.com" @focus="$parent.clearErrorMsg($event)">
                <div id="sufijo-error" class="error invalid-feedback"></div>
            </div>

            <div class="col-md-12">
                <label for="sufijo" class="form-label"></label>
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </form>

    </div>
</template>

<script>

export default {
    props: {
        url: {
            type: String,
            default: ''
        },
        company: {
            type: Object,
            default: ''
        }
    },
    data() {
        return {
            model: {
                id: '',
                name: '',
                contact: '',
                pais: '',
                asist_type: '',
                sufijo: ''
            },
        }
    },
    created() {
        this.model.id = this.company.id;
        this.model.name = this.company.name;
        this.model.contact = this.company.contact;
        this.model.pais = this.company.pais;
        this.model.asist_type = this.company.asist_type;
        this.model.sufijo = this.company.sufijo;
    },
    mounted() {
    },
    methods: {



        formController: function (url, event) {

            var vm = this;

            var target = $(event.target);
            var url = url;
            var fd = new FormData(event.target);


            Swal.fire({
                title: 'Advertencia!',
                text: '¿Seguro que desea guardar?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar!',
                heightAuto: false
            }).then((result) => {
                if (result.value) {

                    EventBus.$emit('loading', true);
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
                }
            });



        },

    }
}
</script>
