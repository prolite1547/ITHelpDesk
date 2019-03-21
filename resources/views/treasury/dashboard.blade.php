@extends('layouts.master')
@section('title','Treasury')
@section('inside_container')
    @include('includes.treasuryHeader')
    @yield('content')

    <table class="table table--pos" id="tickets-table">
        <thead class="table__thead">
        <th class="table__th">Subject</th>
        <th class="table__th">Category</th>
        <th class="table__th">Priority</th>
        <th class="table__th">Status</th>
        <th class="table__th">Branch</th>
        <th class="table__th">Created At</th>
        <th class="table__th">Expiration Date</th>
        <th class="table__th">Assignee</th>
        <th class="table__th"><input type="checkbox"></th>
        </thead>
        <tbody class="table__tbody">

        </tbody><tbody class="table__tbody">

        </tbody>
    </table>

@endsection
