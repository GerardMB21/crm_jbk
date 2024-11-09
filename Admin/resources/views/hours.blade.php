@extends('layouts.master')
@section('title')
    @lang('translation.Editable_Table')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Tables @endslot
        @slot('title') Editable Table @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Table Edits</h4>
                    <p class="card-title-desc">Table Edits is a lightweight jQuery plugin for making table rows editable.
                    </p>

                    <div class="table-responsive">
                        <table class="table table-editable table-nowrap align-middle table-edits">
                            <thead>
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>HORARIO</th>
                                    <th>TOLERANCIA</th>
                                    <th>ESTADO</th>
                                    <th>OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($horarios as $horario)
                                    <tr data-id="{{ $horario->id }}">
                                        <td data-field="name">{{ $horario->name }}</td>
                                        <td data-field="hour">
                                            <ul id="horarios_{{ $horario->id }}" style="list-style-type: none; padding-left: 0;">
                                                @if (!empty($horario->days))
                                                    <script>
                                                        let days = @json($horario->days);

                                                        const dayAbbreviations = {
                                                            'Lunes': 'Lun',
                                                            'Martes': 'Mar',
                                                            'Miércoles': 'Mié',
                                                            'Jueves': 'Jue',
                                                            'Viernes': 'Vie',
                                                            'Sábado': 'Sáb',
                                                            'Domingo': 'Dom'
                                                        };

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

                                                        let groupedDays = groupDaysBySchedule(days);
                                                        document.getElementById('horarios_{{ $horario->id }}').innerHTML = renderGroupedDays(groupedDays);
                                                    </script>
                                                @else
                                                    Sin horario
                                                @endif
                                            </ul>
                                        </td>
                                        <td data-field="tolerancia">{{ $horario->tolerancia_min }}</td>
                                        <td data-field="state">{{ $horario->state }}</td>
                                        <td style="width: 100px">
                                            <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- <tr data-id="1">
                                    <td data-field="id" style="width: 80px">1</td>
                                    <td data-field="name">David McHenry</td>
                                    <td data-field="age">24</td>
                                    <td data-field="gender">Male</td>
                                    <td style="width: 100px">
                                        <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr data-id="2">
                                    <td data-field="id">2</td>
                                    <td data-field="name">Frank Kirk</td>
                                    <td data-field="age">22</td>
                                    <td data-field="gender">Male</td>
                                    <td>
                                        <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr data-id="3">
                                    <td data-field="id">3</td>
                                    <td data-field="name">Rafael Morales</td>
                                    <td data-field="age">26</td>
                                    <td data-field="gender">Male</td>
                                    <td>
                                        <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr data-id="4">
                                    <td data-field="id">4</td>
                                    <td data-field="name">Mark Ellison</td>
                                    <td data-field="age">32</td>
                                    <td data-field="gender">Male</td>
                                    <td>
                                        <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr data-id="5">
                                    <td data-field="id">5</td>
                                    <td data-field="name">Minnie Walter</td>
                                    <td data-field="age">27</td>
                                    <td data-field="gender">Female</td>
                                    <td>
                                        <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/table-edits/table-edits.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var pickers = {};
            $('.table-edits tr').editable({
                // dropdowns: {
                //   gender: ['Male', 'Female']
                // },
                edit: function edit(values) {
                $(".edit i", this).removeClass('fa-pencil-alt').addClass('fa-save').attr('title', 'Save');
                },
                save: function save(values) {
                $(".edit i", this).removeClass('fa-save').addClass('fa-pencil-alt').attr('title', 'Edit');
                if (this in pickers) {
                    pickers[this].destroy();
                    delete pickers[this];
                }
                },
                cancel: function cancel(values) {
                $(".edit i", this).removeClass('fa-save').addClass('fa-pencil-alt').attr('title', 'Edit');
                if (this in pickers) {
                    pickers[this].destroy();
                    delete pickers[this];
                }
                }
            });
        });
    </script>
@endsection