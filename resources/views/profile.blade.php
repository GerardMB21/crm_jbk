@extends('layouts.master')
@section('title')
@lang('translation.Profile')
@endsection

@section('content')
@component('common-components.breadcrumb')
@slot('pagetitle') Perfil @endslot
@slot('title') Perfil @endslot
@endcomponent

<div class="row mb-4">
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-center">
                    <!-- <div class="dropdown float-end">
                        <a class="text-body dropdown-toggle font-size-18" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                            <i class="uil uil-ellipsis-v"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Edit</a>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Remove</a>
                        </div>
                    </div> -->
                    <div class="clearfix"></div>
                    <div>
                        <img src="{{ URL::asset('/assets/images/users/avatar-4.jpg') }}" alt="" class="avatar-lg rounded-circle img-thumbnail">
                    </div>
                    <h5 class="mt-3 mb-1">{{ $user->name }}</h5>
                    <!-- <p class="text-muted">UI/UX Designer</p> -->

                    <!-- <div class="mt-4">
                        <button type="button" class="btn btn-light btn-sm"><i class="uil uil-envelope-alt me-2"></i>
                            Message</button>
                    </div> -->
                </div>

                <hr class="my-4">

                <div class="text-muted">
                    <div class="table-responsive mt-4">
                        <div>
                            <p class="mb-1">Nombre :</p>
                            <h5 class="font-size-16">{{ $user->name }}</h5>
                        </div>
                        <div class="mt-4">
                            <p class="mb-1">Correo :</p>
                            <h5 class="font-size-16">{{ $user->email }}</h5>
                        </div>
                        <div class="mt-4">
                            <p class="mb-1">Grupo :</p>
                            <h5 class="font-size-16">{{ $group->name }}</h5>
                        </div>
                        <div class="mt-4">
                            <p class="mb-1">Mobil :</p>
                            <h5 class="font-size-16">{{ $user->telefono }}</h5>
                        </div>
                        <div class="mt-4">
                            <p class="mb-1">Genero :</p>
                            <h5 class="font-size-16">{{ $user->genero }}</h5>
                        </div>
                        <div class="mt-4">
                            <p class="mb-1">Fecha de Nacimiento :</p>
                            <h5 class="font-size-16">{{ $user->fecha_naci }}</h5>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card mb-0">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#attendance" role="tab">
                        <i class="uil uil-calender font-size-20"></i>
                        <span class="d-none d-sm-block">Asistencia</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#advertisement" role="tab">
                        <i class="uil uil-bell font-size-20"></i>
                        <span class="d-none d-sm-block">Anuncios</span>
                    </a>
                </li>
            </ul>
            <!-- Tab content -->
            <div class="tab-content p-4">
                <div class="tab-pane active" id="attendance" role="tabpanel">
                    <div>
                        <div>
                            <h5 class="font-size-16 mb-4">Asistencia</h5>

                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="advertisement" role="tabpanel">
                    <div>
                        <div>
                            <h5 class="font-size-16 mb-4">Anuncios</h5>

                            <div>
                                <ul class="verti-timeline list-unstyled">
                                    @foreach($advertisements as $advertisement)
                                        <li class="event-list">
                                            <div class="event-date text-primar">{{ $advertisement->created_at->format('d M') }}</div>
                                            <h5>{{ $advertisement->title }}</h5>
                                            <p class="text-muted">{!! $advertisement->text !!}</p>
                                            @if ($advertisement->file_name)
                                                <div>
                                                    <a href="/storage/uploads/{{ $advertisement->file_name }}" target="_blank" class="btn btn-success waves-effect waves-light mb-3">Descargar Adjunto</a>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
@endsection
@section('script')
    <!-- fullcalendar -->
    <script src="{{ URL::asset('/assets/libs/fullcalendar/fullcalendar.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($events),
                eventContent: function(arg) {
                    const event = arg.event.extendedProps;

                    let iconHtml = '';

                    if (event.icon) {
                        iconHtml = `<i class="uil ${event.icon} font-size-15"></i>`;
                    };

                    let customContent = document.createElement('div');

                    customContent.style.backgroundColor = event.color;
                    customContent.className = "text-start";
                    customContent.innerHTML = iconHtml + `<span>${arg.event.title}</span>`;

                    return { domNodes: [customContent] };
                }
            });
            calendar.render();
        });
    </script>
@endsection
