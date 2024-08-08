<template>
    <div class="modal fade" id="modalGroup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Lista de Grupos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th class="col-6 text-center">GRUPO</th>
                                <th class="col-3 text-center">OPCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="group in groups" :key="group.id">
                                <td>{{ group.group_name }}</td>
                                <td>
                                    <button class="btn btn-danger" @click.prevent="deleteGroupUser(group)"
                                        title="Eliminar">
                                        <i class="fa-solid fa-trash-can fa-sm" style="color: #ffffff;"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        url_delete_group: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            groups: [],
            user: ''
        }
    },
    mounted() {
        EventBus.$on('group_modal', function (data, user) {
            this.groups = [];
            this.groups = data;
            this.user = user;
            $('#modalGroup').modal('show');
        }.bind(this));
    },
    methods: {
        deleteGroupUser(group) {
            Swal.fire({
                title: '¡Cuidado!',
                text: '¿Seguro que desea quitarlo del grupo ' + group.group_name + '?',
                icon: "warning",
                heightAuto: false,
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then(result => {
                EventBus.$emit('loading', true);

                if (result.value) {
                    axios.post(this.url_delete_group, {
                        id: group.id,
                        user_id: this.user.id
                    }).then(response => {
                        EventBus.$emit('loading', false);
                        this.groups=[];
                        this.groups=response.data;
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
