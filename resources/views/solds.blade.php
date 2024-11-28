@extends('layouts.master')
@section('title')
    @lang('translation.Editable_Table')
@endsection
@section('css')
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Ventas @endslot
        @slot('title') {{ $campaign->name }} @endslot
    @endcomponent

    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">

                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        @foreach ($tab_states as $tab_state)
                            <li class="nav-item">
                                <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $tab_state->id }}" role="tab" data-tab-id="{{ $tab_state->id }}">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">{{ $tab_state->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content p-3 text-muted">
                        @foreach ($forms as $form => $data)
                            <div class="tab-pane {{ $tab_state_id == $form ? 'active' : '' }}" id="{{ $form }}" role="tabpanel">
                                <button type="button" class="btn btn-success waves-effect waves-light mb-3 modal-sold" title="Edit" data-tab-id="{{ $form }}">Crear Venta</button>

                                <table class="datatable table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>FECHA DE CREACIÓN</th>
                                            <th>FECHA DE EDICIÓN</th>
                                            <th>AGENTE</th>
                                            <th>ESTADO</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data as $d)
                                            <tr data-id="{{ $d['id'] }}">
                                                <td data-form="id">
                                                    <div data-form-id="{{ $d['id'] }}">
                                                        {{ $d['id'] }}
                                                    </div>
                                                </td>
                                                <td data-form="fecha de creacion">
                                                    <div data-form-id="{{ $d['id'] }}">
                                                        {{ \Carbon\Carbon::parse($d['created_at'])->format('d/m/Y H:i') }}
                                                    </div>
                                                </td>
                                                <td data-form="fecha de edición">
                                                    <div data-form-id="{{ $d['id'] }}">
                                                        {{ \Carbon\Carbon::parse($d['updated_at'])->format('d/m/Y H:i') }}
                                                    </div>
                                                </td>
                                                <td data-form="agente">
                                                    <div data-form-id="{{ $d['id'] }}">
                                                        {{ $d['created_at_user'] }}
                                                    </div>
                                                </td>
                                                <td data-form="estado">
                                                    <div data-form-id="{{ $d['id'] }}">
                                                        {{ $d['state'] }}
                                                    </div>
                                                </td>
                                                <td style="width: 100px" data-form="opciones">
                                                    <button type="button" class="btn btn-outline-info btn-sm edit" title="Edit" data-form-id="{{ $d['id'] }}" data-tab-id="{{ $form }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm delete" title="Delete" data-form-id="{{ $d['id'] }}">
                                                        <i class="fas uil-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/ckeditor/ckeditor.min.js') }}"></script>
    <script>
        const id = @json($id);
        const campaigns = @json($campaigns);
        const tab_states = @json($tab_states);
        const fields = @json($fields);

        $(document).ready(function() {

            const filterFields = fields[tab_states[0].id];
            console.log(filterFields)

            $('.datatable').DataTable();

            $('a.nav-link').on('click', function () {
                const tabId = this.dataset.tabId;
                const filterFields = fields[tabId];

                console.log(filterFields)
            });

            $('.modal-sold').on('click', '', function () {
                const tabId = this.dataset.tabId;

                window.location.assign(`{{ url('sales/solds/${id}/${tabId}') }}`);
            });

            $('.datatable tbody').on('click', '.btn.edit', function () {
                const tabId = this.dataset.tabId;
                const formId = this.dataset.formId;

                window.location.assign(`{{ url('sales/solds/${id}/${tabId}/${formId}') }}`);
            });

            $('.datatable tbody').on('dblclick', 'tr td div', function () {
                const formId = this.dataset.formId;
                console.log(formId)
            });

            $('.datatable tbody').on('click', '.btn.delete', function () {
                const formId = this.dataset.formId;

                Swal.fire({
                    title: '¿Eliminar?',
                    text: "Estas seguro de eliminar esta Venta",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-danger ms-2 mt-2',
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        fetch(`{{ route('DeleteSold', '') }}/${formId}`, {
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
                                text: 'Venta eliminado.',
                                icon: 'success',
                                confirmButtonColor: "#34c38f"
                            }).then(function () {
                                window.location.reload();
                            })
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Ocurrio un error al intentar eliminar la Venta.',
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