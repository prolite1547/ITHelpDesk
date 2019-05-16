@extends('layouts.dashboardLayout')
@section('title','DEV Projects')
@section('submenu')@endsection
@section('content')

<main>
   
      <div class="row">
          

          <div class="col-2-of-3">
                        <div class="window" style="width:100%;margin-bottom:10px;">
                                        <div class="window__title-box">
                                            <h3 class="heading-tertiary">Presentation</h3>
                                        </div>
                                        <div class="window__content" style="display:block;text-align:center ;background:#2d3436;color:white;">
                                                <div class="row" style="margin-bottom:5px;margin-left:10px;">
                                                       <div class="col-1-of-4">
                                                                <span style="font-size:12px;">ON-GOING</span>
                                                       </div>
                                                       <div class="col-1-of-5">
                                                                <span style="font-size:12px;">TESTING</span>
                                                       </div>
                                                       <div class="col-1-of-5">
                                                                <span style="font-size:12px;">DONE</span>
                                                       </div>
                                                       <div class="col-1-of-5">
                                                                <span style="font-size:12px;">NO. OF REPORTS</span>
                                                       </div>
                                                </div>
                                                <div class="row" style="margin-left:10px;">
                                                        <div class="col-1-of-4">
                                                                <span style="font-size:16px;font-weight: bold;">{{ $onGoing }}</span>
                                                        </div>
                                                        <div class="col-1-of-5">
                                                                <span style="font-size:16px;font-weight: bold;">{{ $testing }}</span>
                                                        </div>
                                                        <div class="col-1-of-5">
                                                                <span style="font-size:16px;font-weight: bold;">{{ $done }}</span>
                                                        </div>     
                                                        <div class="col-1-of-5">
                                                                <span style="font-size:16px;font-weight: bold;">{{ $allProj }}</span>
                                                        </div>  
                                                </div>
                                                {{-- {{  $onGoing }} | {{ $testing }} | {{ $done }} | {{ $allProj }} --}}
                                        </div>
                                </div>
                <table class="table" id="devprojs-table" >
                        <thead class="table__thead">
                        <th class="table__th">Project Name</th>
                        <th class="table__th">Assigned To</th>
                        <th class="table__th">Status</th>
                        <th class="table__th">Date Start</th>
                        <th class="table__th">Date End</th>
                        <th class="table__th">MD50 Status</th>
                        <th class="table__th">Actions</th>
                        </thead>
                        <tbody class="table__tbody">
                           
                        </tbody>
                       
                    </table>
          </div>
          <div class="col-1-of-3">
                       
        
                        <div class="window" style="width:100%">
                                <div class="window__title-box">
                                    <h3 class="heading-tertiary">Adding of Project</h3>
                                </div>
                                <div class="window__content" style="display:block;">
                                       <div class="window__form">
                                           
                                                <div class="fieldset__contact-inputs" >
                                                    {!! Form::open(['method' => 'POST','route' => 'add.devprojs','id' => 'form-addProject']) !!}
                                                    <div class="form__group">
                                                            {!! Form::label('projName','Project Name :',['class' => 'form__label'])!!}
                                                            {!! Form::text('projName',null,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                                                    </div>
                                                    <div class="form__group">
                                                            {!! Form::label('assigned','Assigned To :',['class' => 'form__label'])!!}
                                                            {!! Form::text('assigned',null,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                                                    </div>
                                                    <div class="form__group">
                                                            {!! Form::label('status','Status :',['class' => 'form__label'])!!}
                                                            {!! Form::select('status', ['On-Going' => 'On-Going', 'Testing' => 'Testing','Done' => 'Done'], null, ['placeholder' => '(select status)','class' => 'form__input   ','required', 'style'=>'width:300px;']) !!}
                                                    </div>
                                                    <div class="form__group">
                                                            {!! Form::label('dateStart','Date Start :',['class' => 'form__label'])!!}
                                                            {!! Form::date('dateStart',null,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                                                    </div>
                                                    <div class="form__group">
                                                            {!! Form::label('dateEnd','Date End :',['class' => 'form__label'])!!}
                                                            {!! Form::date('dateEnd',null,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                                                    </div>
                                                    <div class="form__group">
                                                            {!! Form::label('statusmd','MD50 Status :',['class' => 'form__label'])!!}
                                                            {!! Form::text('statusmd',null,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                                                    </div>
                                                    
                                                    {!! Form::button('Add Project',['type' => 'submit','class'=>'btn','data-action' => 'addProject']) !!}
                                                    {!! Form::close() !!}
                                                 
                                            </div>
                                            
                                         
                                                  
                                           
                                       </div>
                                </div>
                        </div>               
                  </div>
          
      </div>
</main>
 
@endsection
