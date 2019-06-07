@extends('layouts.dashboardLayout')
@section('submenu')@endsection
@section('title','Store Operations')
@section('content')
    <main class="storeOperations">
        <div class="storeOperations__content row-flex">
            <a class="icon-btn storeOperations__box" href="{{route('storeVisitIndex')}}">
                <div class="con-btn__icon-box">
                    <svg class="icon-btn__icon"><use xlink:href="{{asset('svg/sprite2.svg#icon-aircraft')}}"></use></svg>
                </div>
                <span class="icon-btn__label">Store Visit</span>
            </a>

        @if (Auth::user()->id != 24)
             <a class="icon-btn storeOperations__box" href="{{route('show.devprojs')}}">
                <div class="con-btn__icon-box">
                    <svg class="icon-btn__icon"><use xlink:href="{{asset('svg/sprite2.svg#icon-clipboard')}}"></use></svg>
                </div>
                <span class="icon-btn__label">Dev Projects</span>
            </a>
        @endif
           
            {{-- <a class="icon-btn storeOperations__box" href="{{route('show.mds')}}">
                    <div class="con-btn__icon-box">
                        <svg class="icon-btn__icon"><use xlink:href="{{asset('svg/sprite2.svg#icon-menu')}}"></use></svg>
                    </div>
                    <span class="icon-btn__label">Master Data Services</span>
                </a> --}}
        </div>

    </main>
@endsection
