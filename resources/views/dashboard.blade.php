@if(Auth::user()->role_id == 5)
    <script>window.location = "/datacorrections/ty/sdc/pending";</script>
@elseif(Auth::user()->role_id == 6)
    <script>window.location = "/datacorrections/ty2/sdc/pending";</script>
@elseif(Auth::user()->role_id == 7)
    <script>window.location = "/datacorrections/gc/sdc/pending";</script>
@elseif(Auth::user()->role_id == 8)
    <script>window.location = "/datacorrections/app/sdc/pending";</script>
@endif

@extends('layouts.dashboardLayout')
@section('title','Dashboard')
@section('submenu')@endsection

@section('content')
    <div class="iframe-container" style="width: 100%;height: 100vh;">
        <iframe src="{{ route('reports.charts') }}" frameborder="0" frameborder="0" width="100%" height="100%" scrolling="yes"></iframe>
    </div>
@endsection
