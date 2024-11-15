@extends('layouts.master')
@section('title')
    @lang('translation.Editable_Table')
@endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Administración de Usuarios @endslot
        @slot('title') Grupos de Usuarios @endslot
    @endcomponent

    <x-table :idCreateButton="'newEditGroup'" :idModal="'editGroup'" :textButton="'Crear Horario'" :headers="['GRUPO','IP','HORARIO','ESTADO','OPCIONES']">
        @foreach ($groups as $group)
            <tr data-id="{{ $group->id }}">
                <td data-field="name">
                    <div data-group-id="{{ $group->id }}">
                        {{ $group->name }}
                    </div>
                </td>
                <td data-field="ip">
                    <div data-group-id="{{ $group->id }}">
                        {{ $group->ip }}
                    </div>
                </td>
                <td data-field="hour">
                    <div data-group-id="{{ $group->id }}">
                        {{ $group->horario_name }}
                    </div>
                </td>
                <td data-field="state">
                    <div data-group-id="{{ $group->id }}">
                        {{ $group->state }}
                    </div>
                </td>
                <td style="width: 100px">
                    <button type="button" class="btn btn-outline-info btn-sm edit" title="Edit" data-group-id="{{ $group->id }}" data-bs-toggle="modal" data-bs-target="#editGroup">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm delete" title="Delete" data-group-id="{{ $group->id }}">
                        <i class="fas uil-trash-alt"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </x-table>

    <x-modal :idModal="'editGroup'" :ariaLabelledby="'editGroup'" :routeAction="route('SaveGroup')" :idTitle="'editGroupTitle'" :textTitle="'Crear Grupo'">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="company_id">Compañía:</label>
                    <select class="form-select" id="company_id" name="company_id" value="0" required>
                        <option value="0" selected>Seleccionar</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                    <div class="valid-feedback">Valido!</div>
                    <div class="invalid-feedback">La compañia es requerida.</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="name">Nombre:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="valid-feedback">Valido!</div>
                    <div class="invalid-feedback">El nombre es requerido.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="ip">IP:</label>
                    <input type="text" class="form-control" id="ip" name="ip" required>
                    <div class="valid-feedback">Valido!</div>
                    <div class="invalid-feedback">La IP es requerida.</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="horario_id">Horario:</label>
                    <select class="form-select" id="horario_id" name="horario_id" value="0" required>
                        <option value="0" selected>Seleccionar</option>
                        @foreach ($hours as $hour)
                            <option value="{{ $hour->id }}">{{ $hour->name }}</option>
                        @endforeach
                    </select>
                    <div class="valid-feedback">Valido!</div>
                    <div class="invalid-feedback">El horario es requerido.</div>
                </div>
            </div>
        </div>
    </x-modal>

@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script>
        const companies = @json($companies);
        const groups = @json($groups);
        const hours = @json($hours);
        const titleEditGroup = $('#editGroupTitle')[0];

        function reinitData() {
            $('#id')[0].value = "";
            $('#name')[0].value = "";
            $('#ip')[0].value = "";
            $('#company_id')[0].value = "0";
            $('#horario_id')[0].value = "0";
        };
        function loadData(groupId) {
            let groupData;

            for (let i = 0; i < groups.length; i++) {
                const group = groups[i];

                if (group.id == +groupId) groupData = group;
            };

            if (groupData) {
                const {
                    campana_id,
                    company_id,
                    created_at,
                    created_at_user,
                    deleted_at,
                    horario_id,
                    horario_name,
                    id,
                    ip,
                    name,
                    perfil_id,
                    permissions,
                    state,
                    updated_at,
                    updated_at_user
                } = groupData;

                $('#id')[0].value = id;
                $('#name')[0].value = name;
                $('#ip')[0].value = ip;
                $('#company_id')[0].value = company_id;
                $('#horario_id')[0].value = horario_id;
            };
        };

        $(document).ready(function() {
            const table = $('#datatable').DataTable();

            $('#newEditGroup').on('click', '', function () {
                reinitData();
                titleEditGroup.innerText = "Nuevo Grupo";
            });

            $('#editGroup').on('click', 'btn-close', function () {
                reinitData();
            });

            $('#datatable tbody').on('click', '.btn.edit', function () {
                titleEditGroup.innerText = "Editar Grupo";

                reinitData();

                const groupId = this.dataset.groupId;

                loadData(groupId);

                $('#editGroup').modal('show');
            });

            $('#datatable tbody').on('dblclick', 'tr td div', function () {
                reinitData();

                const hourId = this.dataset.hourId;

                loadData(hourId);

                $('#editGroup').modal('show');
            });

            $('#datatable tbody').on('click', '.btn.delete', function () {
                const groupId = this.dataset.groupId;

                Swal.fire({
                    title: '¿Eliminar?',
                    text: "Estas seguro de eliminar este Grupo",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-danger ms-2 mt-2',
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        fetch(`{{ route('DeleteGroup', '') }}/${groupId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: 'Eliminado!',
                                text: 'Grupo eliminado.',
                                icon: 'success',
                                confirmButtonColor: "#34c38f"
                            }).then(function () {
                                window.location.reload();
                            })
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Ocurrio un error al intentar eliminar el grupo.',
                                icon: 'error',
                                confirmButtonColor: "#34c38f"
                            });
                        });
                    };
                });
            });
        });
    </script>
@endsection