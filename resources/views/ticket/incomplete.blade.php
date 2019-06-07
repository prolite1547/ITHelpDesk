@extends('layouts.dashboardLayout')
@section('title','Incomplete Ticket')
@section('submenu')@endsection

@section('content')
    <main>
        <div class="incomplete">
            <h1 class="incomplete__header-primary">Incomplete Ticket Data</h1>
                <div class="incomplete__flex">
                    <div class="incomplete__left">
                        {!! Form::model($ticket->issue,['method'=>'PATCH','route' => ['addTicketDetails'],'class'=>'form-addTicketDetails','enctype'=>'multipart/form-data']) !!}
                        {{Form::hidden('_method','PATCH')}}
                        <label class="form-addTicketDetails__ticket">Ticket #: <input name="ticket_id" type="text" value="{{$ticket->id}}" readonly class="form-addTicketDetails__ticket-value"></label>
                        <div class="form__group">
                            {!! Form::label('subject','Subject :',['class' => 'form__label form__label--block'])!!}
                            {!! Form::text('subject',null,['class' => 'form__input form__input--100w','placeholder' => 'Subject','required','minlength' => '10']) !!}
                        </div>
                        <div class="form__group">
                            {!! Form::textarea('details',null,['rows' => '5','class' => 'form__input u-width-full','placeholder' => 'details...','required','minlength' => '10']) !!}
                        </div>
                        <div class="form__group">

                            {{ Form::file('attachments[]', array('multiple'))  }}
                        </div>

                        <div class="form__group">
                            {!! Form::text('type',1,['class' => 'form__input','hidden','required']) !!}
                            {!! Form::label('priority','Priority:',['class' => 'form__label'])!!}
                            {!! Form::select('priority', $prioSelect, $ticket->priority, ['placeholder' => '(select priority)','class' => 'form__input','required']) !!}
                            {!! Form::select('group', $groupSelect, null, ['placeholder' => '(select group)','class' => 'form__input','required']) !!}
                        </div>
                        <div class="form__group form-addTicketDetails__category-inputs">
                            {!! Form::select('category', $typeSelect, null, ['placeholder' => '(select category)','class' => 'form__input','required']) !!}
                            {!! Form::select('catA', $categoryASelect, null, ['placeholder' => '(select sub-A)','class' => 'form__input categoryASelect form__input--select2','required']) !!}
                            {!! Form::select('catB',[], null, ['placeholder' => '(select sub-B)','class' => 'form__input categoryBSelect form__input--select2','required']) !!}
                            {!! Form::select('catC', $CategoryCSelect, null, ['placeholder' => '(select sub-C)','class' => 'form__input categoryCSelect form__input--select2']) !!}
                        </div>
                        <div class="form__group">
                            {!! Form::label('assignee','Assign to user:',['class' => 'form__label'])!!}
                            {!! Form::select('assignee', $selfOption + $assigneeSelect, null, ['placeholder' => '(assign to)','class' => 'form__input form__input--select2','required','id' => 'assigneeSelect']) !!}
                        </div>
                        {!! Form::button('Bind Details',['class' => 'btn btn--blue','type' => 'submit','id'=>'ticketAdd']) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="incomplete__right">
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
    </main>
@endsection



