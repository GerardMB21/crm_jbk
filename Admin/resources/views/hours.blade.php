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
        @slot('pagetitle') Configuración de Asistencia @endslot
        @slot('title') Horarios @endslot
    @endcomponent

    <script>
        let days;
        let groupedDays;
        const dayAbbreviations = {
            'Lunes': 'Lun',
            'Martes': 'Mar',
            'Miércoles': 'Mié',
            'Jueves': 'Jue',
            'Viernes': 'Vie',
            'Sábado': 'Sáb',
            'Domingo': 'Dom'
        };
    </script>

    <x-table :idCreateButton="'newEditHour'" :idModal="'editHour'" :textButton="'Crear Horario'" :headers="['NOMBRE','HORARIO','TOLERANCIA','ESTADO','OPCIONES']">
        @foreach ($horarios as $horario)
            <tr data-id="{{ $horario->id }}">
                <td data-field="name">
                    <div data-hour-id="{{ $horario->id }}">
                        {{ $horario->name }}
                    </div>
                </td>
                <td data-field="hour">
                    <div data-hour-id="{{ $horario->id }}">
                        <ul id="horarios_{{ $horario->id }}" style="list-style-type: none; padding-left: 0;">
                            @if (!empty($horario->days))
                                <script>
                                    days = @json($horario->days);

                                    function groupDaysBySchedule(days) {
                                        let groupedDays = [];
                                        let currentGroup = { start: days[0].day, end: days[0].day, inicio: days[0].inicio, final: days[0].final };

                                        for (let i = 1; i < days.length; i++) {
                                            let currentDay = days[i];
                                            let previousDay = days[i - 1];

                                            if (currentDay.inicio === previousDay.inicio && currentDay.final === previousDay.final) {
                                                currentGroup.end = currentDay.day;
                                            } else {
                                                groupedDays.push(currentGroup);
                                                currentGroup = { start: currentDay.day, end: currentDay.day, inicio: currentDay.inicio, final: currentDay.final };
                                            }
                                        }
                                        groupedDays.push(currentGroup);

                                        return groupedDays;
                                    }
                                    function renderGroupedDays(groupedDays) {
                                        let html = '';
                                        groupedDays.forEach(group => {
                                            let startDay = dayAbbreviations[group.start] || group.start;
                                            let endDay = dayAbbreviations[group.end] || group.end;

                                            if (group.start === group.end) {
                                                html += `<li>${startDay}: ${group.inicio} - ${group.final}</li>`;
                                            } else {
                                                html += `<li>${startDay} - ${endDay}: ${group.inicio} - ${group.final}</li>`;
                                            }
                                        });
                                        return html;
                                    }

                                    groupedDays = groupDaysBySchedule(days);

                                    document.getElementById('horarios_{{ $horario->id }}').innerHTML = renderGroupedDays(groupedDays);
                                </script>
                            @else
                                Sin horario
                            @endif
                        </ul>
                    </div>
                </td>
                <td data-field="tolerancia">
                    <div data-hour-id="{{ $horario->id }}">
                        {{ $horario->tolerancia_min }}
                    </div>
                </td>
                <td data-field="state">
                    <div data-hour-id="{{ $horario->id }}">
                        {{ $horario->state }}
                    </div>
                </td>
                <td style="width: 100px">
                    <button type="button" class="btn btn-outline-info btn-sm edit" id="editEditHour" title="Edit" data-hour-id="{{ $horario->id }}" data-bs-toggle="modal" data-bs-target="#editHour">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm delete" title="Delete" id="deleteHour">
                        <i class="fas uil-trash-alt"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </x-table>

    <x-modal :idModal="'editHour'" :ariaLabelledby="'editHour'" :routeAction="route('SaveHour')" :idTitle="'editHourTitle'" :textTitle="'Crear Horario'">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="name">Nombre:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="valid-feedback">Valido!</div>
                    <div class="invalid-feedback">El nombre es requerido.</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="tolerancia_min">Tolerancia (mins):</label>
                    <input type="number" class="form-control" id="tolerancia_min" name="tolerancia_min" required>
                    <div class="valid-feedback">Valido!</div>
                    <div class="invalid-feedback">La tolerancia es requerido.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="form-check form-switch form-switch-md">
                        <input type="checkbox" class="form-check-input" id="motivo_tardanza" name="motivo_tardanza">
                        <label class="form-check-label" for="motivo_tardanza">Motivo de tardanza</label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="form-check form-switch form-switch-md">
                        <input type="checkbox" class="form-check-input" id="motivo_temprano" name="motivo_temprano">
                        <label class="form-check-label" for="motivo_temprano">Motivo salir antes</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="form-check form-switch form-switch-md">
                        <input type="checkbox" class="form-check-input" id="restringir_last" name="restringir_last">
                        <label class="form-check-label" for="restringir_last">Sin acceso fuera de horario</label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="form-check form-switch form-switch-md">
                        <input type="checkbox" class="form-check-input" id="restringir_gest" name="restringir_gest">
                        <label class="form-check-label" for="restringir_gest">No gestion fuera de horario</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <span class="badge bg-info">Día</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <span class="badge bg-info">Entrada</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <span class="badge bg-info">Salida</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <input type="text" class="form-control" id="monday" name="monday" value="Lunes" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="in-time-monday" name="in-time-monday">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="out-time-monday" name="out-time-monday">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <input type="text" class="form-control" id="tuesday" name="tuesday" value="Martes" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="in-time-tuesday" name="in-time-tuesday">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="out-time-tuesday" name="out-time-tuesday">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <input type="text" class="form-control" id="wednesday" name="wednesday" value="Miercoles" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="in-time-wednesday" name="in-time-wednesday">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="out-time-wednesday" name="out-time-wednesday">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <input type="text" class="form-control" id="thursday" name="thursday" value="Jueves" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="in-time-thursday" name="in-time-thursday">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="out-time-thursday" name="out-time-thursday">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <input type="text" class="form-control" id="friday" name="friday" value="Viernes" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="in-time-friday" name="in-time-friday">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="out-time-friday" name="out-time-friday">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <input type="text" class="form-control" id="saturday" name="saturday" value="Sabado" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="in-time-saturday" name="in-time-saturday">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="out-time-saturday" name="out-time-saturday">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <input type="text" class="form-control" id="sunday" name="sunday" value="Domingo" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="in-time-sunday" name="in-time-sunday">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <input class="form-control" type="time" id="out-time-sunday" name="out-time-sunday">
                </div>
            </div>
        </div>
    </x-modal>

@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script>
        const horarios = @json($horarios);
        const titleEditHour = $('#editHourTitle')[0];

        function reinitData() {
            $('#id')[0].value = "";
            $('#name')[0].value = "";
            $('#tolerancia_min')[0].value = "";
            $('#motivo_tardanza')[0].checked = 0;
            $('#motivo_temprano')[0].checked = 0;
            $('#restringir_last')[0].checked = 0;
            $('#restringir_gest')[0].checked = 0;
            $('#in-time-monday')[0].value = "";
            $('#out-time-monday')[0].value = "";
            $('#in-time-tuesday')[0].value = "";
            $('#out-time-tuesday')[0].value = "";
            $('#in-time-wednesday')[0].value = "";
            $('#out-time-wednesday')[0].value = "";
            $('#in-time-thursday')[0].value = "";
            $('#out-time-thursday')[0].value = "";
            $('#in-time-friday')[0].value = "";
            $('#out-time-friday')[0].value = "";
            $('#in-time-saturday')[0].value = "";
            $('#out-time-saturday')[0].value = "";
            $('#in-time-sunday')[0].value = "";
            $('#out-time-sunday')[0].value = "";
        };
        function loadData(hourId) {
            let hourData;

            for (let i = 0; i < horarios.length; i++) {
                const horario = horarios[i];

                if (horario.id == +hourId) hourData = horario;
            };

            if (hourData) {
                const {
                    days,
                    id,
                    name,
                    sede_id,
                    state,
                    tolerancia_min,
                    motivo_tardanza,
                    motivo_temprano,
                    restringir_last,
                    restringir_gest
                } = hourData;

                $('#id')[0].value = id;
                $('#name')[0].value = name;
                $('#tolerancia_min')[0].value = tolerancia_min;
                $('#motivo_tardanza')[0].checked = motivo_tardanza;
                $('#motivo_temprano')[0].checked = motivo_temprano;
                $('#restringir_last')[0].checked = restringir_last;
                $('#restringir_gest')[0].checked = restringir_gest;

                for (let i = 0; i < days.length; i++) {
                    const d = days[i];
                    const { day, inicio, final } = d;

                    switch (day) {
                        case "Lunes":
                            $('#in-time-monday')[0].value = inicio;
                            $('#out-time-monday')[0].value = final;
                            break;

                        case "Martes":
                            $('#in-time-tuesday')[0].value = inicio;
                            $('#out-time-tuesday')[0].value = final;
                            break;

                        case "Miércoles":
                            $('#in-time-wednesday')[0].value = inicio;
                            $('#out-time-wednesday')[0].value = final;
                            break;

                        case "Jueves":
                            $('#in-time-thursday')[0].value = inicio;
                            $('#out-time-thursday')[0].value = final;
                            break;

                        case "Viernes":
                            $('#in-time-friday')[0].value = inicio;
                            $('#out-time-friday')[0].value = final;
                            break;

                        case "Sábado":
                            $('#in-time-saturday')[0].value = inicio;
                            $('#out-time-saturday')[0].value = final;
                            break;

                        case "Domingo":
                            $('#in-time-sunday')[0].value = inicio;
                            $('#out-time-sunday')[0].value = final;
                            break;

                        default:
                            break;
                    };
                };
            };
        };

        $(document).ready(function() {
            const table = $('#datatable').DataTable();

            $('#newEditHour').on('click', '', function () {
                reinitData();
                titleEditHour.innerText = "Nuevo Horario";
            });

            $('#editHour').on('click', 'btn-close', function () {
                reinitData();
            });

            $('#datatable tbody').on('click', '.btn.edit', function () {
                titleEditHour.innerText = "Editar Horario";

                reinitData();

                const hourId = this.dataset.hourId;

                loadData(hourId);

                $('#editHour').modal('show');
            });

            $('#datatable tbody').on('dblclick', 'tr td div', function () {
                reinitData();

                const hourId = this.dataset.hourId;

                loadData(hourId);

                $('#editHour').modal('show');
            });

            $('#deleteHour').click(function () {
                Swal.fire({
                    title: '¿Eliminar?',
                    text: "Estas seguro de eliminar este Horario",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-danger ms-2 mt-2',
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        Swal.fire({
                            title: 'Eliminado!',
                            text: 'Horario eliminado.',
                            icon: 'success',
                            confirmButtonColor: "#34c38f"
                        });
                    };
                });
            });

            $('#in-time-monday').on('change', function (e) {
                const valMonday = e.target.value;

                $('#in-time-tuesday')[0].value = valMonday;
                $('#in-time-wednesday')[0].value = valMonday;
                $('#in-time-thursday')[0].value = valMonday;
                $('#in-time-friday')[0].value = valMonday;
            });

            $('#out-time-monday').on('change', function (e) {
                const valMonday = e.target.value;

                $('#out-time-tuesday')[0].value = valMonday;
                $('#out-time-wednesday')[0].value = valMonday;
                $('#out-time-thursday')[0].value = valMonday;
                $('#out-time-friday')[0].value = valMonday;
            });
        });
    </script>
@endsection