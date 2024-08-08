<template>
    <div class="container mt-2 p-5 border rounded bg-white">
        <div>
            <button class="btn btn-success" @click.prevent="create()" title="Crear">Crear Horario</button>
        </div>
        <hr>
        <table id="example" class="table table-striped text-center" style="width:100%">
            <thead>
                <tr>
                    <th class="col-3 text-center">NOMBRE</th>
                    <th class="col-2 text-center">HORARIO</th>
                    <th class="col-2 text-center">TOLERANCIA</th>
                    <th class="col-2 text-center">ESTADO</th>
                    <th class="col-3 text-center">OPCIONES</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="horario in horarios" :key="horario.id">
                    <td>{{ horario.name }}</td>
                    <td>{{ horario.sede_id }}</td>
                    <td class="text-center">{{ horario.tolerancia_min }}</td>
                    <td class="text-center">{{ horario.state }}</td>
                    <td>
                        <button class="btn btn-primary" @click.prevent="edit(horario)" title="Editar">
                            <i class="fa-solid fa-pen-to-square fa-sm" style="color: #ffffff;"></i>
                        </button>
                        <button class="btn btn-danger" @click.prevent="deleteHorario(horario)" title="Eliminar">
                            <i class="fa-solid fa-trash-can fa-sm" style="color: #ffffff;"></i>
                        </button>

                    </td>
                </tr>
            </tbody>

        </table>
    </div>
</template>

<script>

export default {
    props: {
        horarios: {
            type: Array,
            default: ''
        },
        url_delete: {
            type: String,
            default: '',
        }
    },
    mounted() {

    },
    methods: {
        create() {
            EventBus.$emit('create_modal');
        },
        edit(horario) {
            EventBus.$emit('edit_modal', horario);
        },
        deleteHorario(horario) {
            Swal.fire({
                title: '¡Cuidado!',
                text: '¿Seguro que desea eliminar el horario ' + horario.name + '?',
                icon: "warning",
                heightAuto: false,
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then(result => {
                EventBus.$emit('loading', true);

                if (result.value) {
                    axios.post(this.url_delete, {
                        id: horario.id,
                    }).then(response => {
                        EventBus.$emit('loading', false);
                        this.$parent.alertMsg(response.data);
                    }).catch(error => {
                        console.log(error);
                        console.log(error.response);
                    });
                } else if (result.dismiss == Swal.DismissReason.cancel) {
                    EventBus.$emit('loading', false);
                }
            });
        },

    }
}
</script>
