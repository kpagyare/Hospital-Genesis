@extends('layouts.app')
@section('title','Appointment Calendar')
@section('page_title','Appointment Calendar')
@section('breadcrumb','Home / Appointments / Calendar')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="page-header">
    <h4 class="page-title">Appointment Calendar</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;font-size:13px;">List View</a>
        <a href="{{ route('appointments.create') }}" class="btn-hms-primary"><i class="bi bi-plus-circle me-1"></i>Book Appointment</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        headerToolbar: { left:'prev,next today', center:'title', right:'dayGridMonth,timeGridWeek,listWeek' },
        events: {!! json_encode($events) !!},
        eventClick: function(info) {
            window.location.href = info.event.url;
            info.jsEvent.preventDefault();
        },
        height: 'auto',
    });
    calendar.render();
});
</script>
@endpush
