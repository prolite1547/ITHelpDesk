@extends('layouts.ticketLayout')
@section('title','Tickets')

@section('table')
    <table class="table" id="tickets-table">
        <thead class="table__thead">
        <tr>
        <th class="table__th">Subject</th>
        <th class="table__th">Category</th>
        <th class="table__th">Priority</th>
        <th class="table__th">Status</th>
        <th class="table__th">Branch</th>
        <th class="table__th">Created At</th>
        <th class="table__th">Expiration</th>
        <th class="table__th">Closed Date</th>
        <th class="table__th">Fixed By</th>
        <th class="table__th">Resolved By</th>
        <th class="table__th"><input type="checkbox"></th>
        </thead>
        </tr>
        <tbody class="table__tbody">

        </tbody>
    </table>
@endsection



