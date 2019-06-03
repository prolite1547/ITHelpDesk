@extends('layouts.dashboardLayout')
@section('title','Store Visit')
@section('submenu')@endsection

@section('content')
    <div class="storeVisit">
        <div class="row-flex row-flex--jc-sa">
            <div class="storeVisit__target">
                <button class="btn btn--blue u-margin-b-xs" data-action="addTarget">Add Target</button>
                <table class="table responsive" id="storeTarget">
                <thead class="table__thead">
                    <tr>
                        <th class="table__th">Month</th>
                        <th class="table__th">Year</th>
                        <th class="table__th"># of Stores</th>
                        <th class="table__th">Created At</th>
                        <th class="table__th">Action</th>
                    </tr>
                </thead>
                    <tbody class="table__tbody">

                    </tbody>
                </table>
            </div>
            <div class="storeVisit__details">
                <div class="storeVisit__details">
                    <button class="btn btn--blue u-margin-b-xs" data-action="addDetails">Add Details</button>
                    <table class="table responsive u-margin-top-xsmall" id="storeDetails">
                        <thead class="table__thead">
                        <tr>
                            <th class="table__th">Store</th>
                            <th class="table__th">IT Personnel</th>
                            <th class="table__th">Status</th>
                            <th class="table__th">Date Start</th>
                            <th class="table__th">Date Done</th>
                            <th class="table__th">Created At</th>
                            <th class="table__th">Action</th>
                        </tr>
                        </thead>
                        <tbody class="table__tbody">
                        <tr>
                            <td>Matina</td>
                            <td>John Edward R. Labor</td>
                            <td>Done</td>
                            <td>Apr 01, 2019</td>
                            <td>Apr 10, 2019</td>
                            <td>
                                <svg class="sprite sprite--blue"><use xlink:href="{{asset('svg/sprite2.svg#icon-edit')}}"></use></svg>
                                <svg class="sprite sprite--red"><use xlink:href="{{asset('svg/sprite2.svg#icon-trash')}}"></use></svg>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

