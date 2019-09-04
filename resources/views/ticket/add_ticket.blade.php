@extends('layouts.dashboardLayout')
@section('title','Add Ticket')
@section('submenu')@endsection
@section('content')
    <main>
        <div class="window">
                <div class="window__title-box">
                    <h3 class="heading-tertiary">New Ticket</h3>
                </div>
                <div class="window__menu">
                    <label id="incidentForm" class="window__item">
                        Incident
                    </label>
                    <label id="PLDTForm" class="window__item">
                        Connection
                    </label>
                    {{--<li class="window__item window__item--active"><a href="#!" class="window__link">Incident</a></li>--}}
                    {{--<li class="window__item"><a href="#!" class="window__link">PLDT</a></li>--}}
                </div>
            <div class="window__content" id="incidentFormContainer">
                <div class="row-flex u-width-50">
                    <div class="window__form">
                        <div class="formColumn">
                            {!! Form::open(['method' => 'POST','route' => 'addTicket','id' => 'form-addTicket']) !!}
                            <div class="form__group">
                                {!! Form::select('store', [], null, ['placeholder' => '(reported by)','class' => 'form__input branchSelect','required','id' => 'ticketBranchSelect']) !!}
                            </div>
                            <div class="form__group">
                                <fieldset class="fieldset">
                                    <legend class="fieldset__legend">Caller Details</legend>
                                    <div class="row-flex row-flex--jc-sb">
                                        <div class="fieldset__contact">
                                            {!! Form::select('user', [], null, ['placeholder' => '(contact)','class' => 'form__input userSelect','required']) !!}
                                        </div>
                                        <div class="fieldset__contact-inputs u-display-n">
                                            <div class="form__group">
                                                {!! Form::label('fName','First Name :',['class' => 'form__label'])!!}
                                                {!! Form::text('fName',null,['class' => 'form__input','placeholder' => 'first','required','minlength' => 2]) !!}
                                            </div>
                                            <div class="form__group">
                                                {!! Form::label('mName','Middle Name :',['class' => 'form__label'])!!}
                                                {!! Form::text('mName',null,['class' => 'form__input','placeholder' => 'middle','required','minlength' => 2]) !!}
                                            </div>
                                            <div class="form__group">
                                                {!! Form::label('lName','Last Name :',['class' => 'form__label'])!!}
                                                {!! Form::text('lName',null,['class' => 'form__input','placeholder' => 'last','required','minlength' => 2]) !!}
                                            </div>
                                            <div class="form__group">
                                                {!! Form::select('position_id', [], null, ['placeholder' => '(position)','class' => 'form__input ','required','id' => 'ticketPositionSelect']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            {!! Form::button('Add Ticket',['class' => 'btn btn--blue','type' => 'submit','id'=>'ticketAdd']) !!}
                            {!! Form::close() !!}

                            {!! Form::open(['method'=>'PATCH','route' => 'addTicketDetails','class'=>'form-addTicketDetails u-display-n','enctype'=>'multipart/form-data']) !!}
                                {{Form::hidden('_method','PATCH')}}
                            <label class="form-addTicketDetails__ticket">Ticket #: <input name="ticket_id" type="text" value="1234" readonly class="form-addTicketDetails__ticket-value"></label>
                            <div class="form__group">
                                {!! Form::label('subject','Subject :',['class' => 'form__label form__label--block'])!!}
                                {!! Form::text('subject',old('subject'),['class' => 'form__input form__input--100w','placeholder' => 'Subject','required','minlength'=>'10']) !!}
                            </div>
                            <div class="form__group">
                                {!! Form::textarea('details',null,['rows' => '5','class' => 'form__input u-width-full','placeholder' => 'details...','required','minlength'=>'10']) !!}
                            </div>
                            <div class="form__group">
                                {{ Form::file('attachments[]', array('multiple'))  }}
                            </div>
                            <div class="form__group">
                                {!! Form::text('type',1,['class' => 'form__input','hidden','required']) !!}
                                {!! Form::label('priority','Priority:',['class' => 'form__label'])!!}
                                {!! Form::select('priority', $prioSelect, null, ['placeholder' => '(select priority)','class' => 'form__input','required']) !!}
                            </div>
                            <div class="form__group">
                                {!! Form::select('category', $typeSelect, null, ['placeholder' => '(select category)','class' => 'form__input','required']) !!}
                                {!! Form::select('catA', $categoryASelect, null, ['placeholder' => '(select sub-A)','class' => 'form__input categoryASelect form__input--select2','required']) !!}
                                {!! Form::select('catB',[], null, ['placeholder' => '(select sub-B)','class' => 'form__input categoryBSelect form__input--select2','required']) !!}
                                {!! Form::select('catC', $CategoryCSelect, null, ['placeholder' => '(select sub-C)','class' => 'form__input categoryCSelect form__input--select2']) !!}
                            </div>
                            {{--  <div class="form__group">
                                {!! Form::select('group', $groupSelect, null, ['placeholder' => '(select group)','class' => 'form__input','required']) !!}
                            </div>  --}}
                            <div class="form__group">
                                <input type="hidden" name="group" value = null id="group">
                            </div>

                            <div class="form__group">
                                {!! Form::label('assignee','Assign to user:',['class' => 'form__label'])!!}
                                {!! Form::select('assignee', $selfOption + $assigneeSelect, null, ['placeholder' => '(assign to)','class' => 'form__input form__input--select2 assigneeSelect ','required','id' => 'assigneeSelect']) !!}
                            </div>
                            {!! Form::button('Bind Details',['class' => 'btn btn--blue','type' => 'submit','id'=>'ticketAdd']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="window__maintenance plusToggleContainer">
                        @if(in_array(Auth::user()->role_id,[$user_roles['admin'],$user_roles['tower']]))
                            <div class="form-branchAdd">
                                <button class="form-branchAdd__button plusToggleContainer__button u-margin-l " type="button"><i class="fas fa-plus"></i>
                                    Add Branch
                                </button>

                                <div class="form-branchAdd__content-box u-display-n">
                                    {!! Form::open(['method' => 'POST','class' => 'form','id' => 'addBranch']) !!}
                                    <div class="form__group">
                                        {!! Form::label('store_name','Name :',['class' => 'form__label'])!!}
                                        {!! Form::text('store_name',null,['class' => 'form__input','placeholder' => 'branch name','required']) !!}
                                    </div>
                                    {!! Form::button('Add',['type' => 'submit','class'=>'btn','data-action' => 'addBranch']) !!}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <div class="form-departmentAdd">
                                <button class="form-departmentAdd__button u-margin-l plusToggleContainer__button" type="button"><i class="fas fa-plus"></i>
                                    Add Department
                                </button>

                                <div class="form-departmentAdd__content-box u-display-n">
                                    {!! Form::open(['method' => 'POST','class' => 'form','id' => 'addDepartment']) !!}
                                    <div class="form__group">
                                        {!! Form::label('department','Department:',['class' => 'form__label'])!!}
                                        {!! Form::text('department',null,['class' => 'form__input','placeholder' => 'department','required']) !!}
                                    </div>
                                    {!! Form::button('Add',['type' => 'submit','class'=>'btn','data-action' => 'addDepartment']) !!}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <div class="form-positionAdd">
                                <button class="form-positionAdd__button u-margin-l plusToggleContainer__button" type="button"><i class="fas fa-plus"></i>
                                    Add Position
                                </button>
                                <div class="form-positionAdd__content-box u-display-n">
                                    {!! Form::open(['method' => 'POST','class' => 'form','id' => 'addPosition']) !!}
                                    <div class="form__group">
                                        {!! Form::label('department_id','Department:',['class' => 'form__label']) !!}
                                        {!! Form::select('department_id',[] ,null, ['placeholder' => '(Choose department..)','class' => 'form__input form__input--select2','data-select' => 'position','id' => 'positionDepSelect','required']) !!}
                                    </div>
                                    <div class="form__group u-display-n" id="positionFormGroup">
                                        {!! Form::label('position','Position:',['class' => 'form__label']) !!}
                                        {!! Form::text('position',null, ['placeholder' => 'position','class' => 'form__input','required']) !!}
                                        {!! Form::button('Add',['type' => 'submit','class'=>'btn','data-action' => 'addPosition']) !!}
                                    </div>

                                    {!! Form::close() !!}
                                </div>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{--PLDT FORM--}}
            <div class="window__content" id="PLDTFormContainer">
                {{Form::open(array('class' => 'form-email','id' => 'addPLDTIssue','files' => true))}}
                    <div class="form__group-flex">
                        {{Form::label('emailTo','To:')}}
                        {{Form::select('to[]',$emailAndGroupSelect,null,array('class'=> 'form-email__input-text u-display-b u-width-full','required','multiple','id' => 'emailTo'))}}
                    </div>
                    <div class="form__group-flex">
                        {{Form::label('emailCc','Cc:')}}
                        {{Form::select('cc[]',$emailAndGroupSelect,null,array('class'=> 'form-email__input-text u-display-b u-width-full','multiple','id' => 'emailCc'))}}
                    </div>
                    {{Form::text('subject',null,array('placeholder' => 'Subject','class'=> 'form-email__input-text u-display-b u-width-full','required'))}}
                    {{Form::textarea('details',null,array('class' => 'form-email__input-textarea','placeholder' => 'Details','required'))}}
                    <div class="form__group">
                        {{Form::file('attachments[]',['multiple'])}}
                    </div>
                    <div class="form__group">
                        {{Form::select('branch',[],null,['placeholder' => '(choose branch...)','class' => 'form-email__input-select branchSelect','required'])}}
                        {{Form::select('concern',$categoryCConnectionGroupSelect,null,['placeholder' => '(choose concern...)','class' => 'form-email__input-select form-email__input-select--concern','required'])}}
                    </div>
                    <div class="form__group">
                        {{Form::select('tel',['083-554-7450' => '083-554-7450'],null,['placeholder' => '(choose telephone...)','class' => 'form-email__input-select form-email__input-select--tel u-display-n'])}}
                        {{Form::select('pid',['PID#578070' => 'PID#578070'],null,['placeholder' => '(choose pid...)','class' => 'form-email__input-select form-email__input-select--pid u-display-n'])}}
                    </div>
                    <div class="form__group">
                        {{Form::label('contact_person','Contact Person:','form-email__label')}}
                        {{Form::tel('contact_person',null,['placeholder' => 'contact person','class' => 'form-email__input-text','required'])}}
                        {{Form::label('contact_number','Contact #:','form-email__label')}}
                        {{Form::tel('contact_number',null,['placeholder' => 'number','class' => 'form-email__input-text','required'])}}
                    </div>
                    {{Form::button('Send',array('type' => 'submit','class' => 'btn btn--blue','data-action' => 'addPLDTIssue'))}}
                {{Form::close()}}
            </div>
        </div>
    </main>
@endsection
