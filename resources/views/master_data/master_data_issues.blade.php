@extends('layouts.dashboardLayout')
@section('title','Master Data Issues')
@section('submenu')@endsection
@section('content')

<main>
   
      <div class="row">

            <div class="col-2-of-3">
                     
            <div style="margin-bottom:10px;">
                    <span>Search : </span><input  type="text" name="search" id="mdssearch">
            </div>
            <table class="table" id="mds-table" >
                    <thead class="table__thead">
                    <th class="table__th">Name of Issue</th>
                    <th class="table__th">Status</th>
                    <th class="table__th">Date Start</th>
                    <th class="table__th">Date End</th>
                    <th class="table__th">Actions</th>
                    </thead>
                    <tbody class="table__tbody">
                       
                    </tbody>
                   
                </table>
            </div>
            <div class="col-1-of-3">
                       
        
                        <div class="window" style="width:100%">
                                <div class="window__title-box">
                                    <h3 class="heading-tertiary">Adding of Issues</h3>
                                </div>
                                <div class="window__content" style="display:block;">
                                       <div class="window__form">
                                           
                                                <div class="fieldset__contact-inputs" >
                                                    {!! Form::open(['method' => 'POST','route' => 'add.mdissue','id' => 'form-AddMdIssue']) !!}
                                                    <div class="form__group">
                                                            {!! Form::label('issueName','Name of Issue :',['class' => 'form__label'])!!}
                                                            {!! Form::text('issueName',null,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                                                    </div>
                                                   
                                                    <div class="form__group">
                                                            {!! Form::label('status','Status :',['class' => 'form__label'])!!}
                                                            {!! Form::select('status', ['On-Going' => 'On-Going','Done' => 'Done'], null, ['placeholder' => '(select status)','class' => 'form__input   ','required', 'style'=>'width:300px;']) !!}
                                                    </div>
                                                    <div class="form__group">
                                                            {!! Form::label('dateStart','Date Start :',['class' => 'form__label'])!!}
                                                            {!! Form::date('dateStart',null,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                                                    </div>
                                                    <div class="form__group">
                                                            {!! Form::label('dateEnd','Date End :',['class' => 'form__label'])!!}
                                                            {!! Form::date('dateEnd',null,['class' => 'form__input','placeholder' => ' ','minlength' => 2, 'style'=>'width:300px;']) !!}
                                                    </div>
                                                    
                                                    {!! Form::button('Add Issue',['type' => 'submit','class'=>'btn','data-action' => 'addProject']) !!}
                                                    {!! Form::close() !!}
                                                 
                                            </div>
                                            
                                         
                                                  
                                           
                                       </div>
                                </div>
                        </div>     
                        
                        
                  </div>
      </div>
</main>
     
@endsection