<main>
    <div class="window" style="width:100%">
        <div class="window__title-box">
        <h3 class="heading-tertiary">Edit Project  </h3>
        </div>
        <div class="window__content" style="display:block;width:100%;">
               <div class="window__form">
                   
                        <div class="fieldset__contact-inputs" >
                            {!! Form::open(['method' => 'POST','route' => 'edit.devprojs','id' => 'form-addProject']) !!}
                            <div class="form__group">
                               
                                {!! Form::text('projID', $project->id, ['class' => 'form__input','placeholder' => ' ','required','readonly','minlength' => 2, 'style'=>'width:300px;display:none;']) !!}
                             </div>
                            <div class="form__group">
                                    {!! Form::label('projName','Project Name :',['class' => 'form__label'])!!}
                                    {!! Form::text('projName', $project->project_name ,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                            </div>
                            <div class="form__group">
                                    {!! Form::label('assigned','Assigned To :',['class' => 'form__label'])!!}
                                    {!! Form::text('assigned',$project->assigned_to,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                            </div>
                            <div class="form__group">
                                    {!! Form::label('status','Status :',['class' => 'form__label'])!!}
                                    {!! Form::select('status', ['On-Going' => 'On-Going', 'Testing' => 'Testing','Done' => 'Done'], $project->status, ['placeholder' => '(select status)','class' => 'form__input   ','required', 'style'=>'width:300px;']) !!}
                            </div>
                            <div class="form__group">
                                    {!! Form::label('dateStart','Date Start :',['class' => 'form__label'])!!}
                                    {!! Form::date('dateStart', $project->date_start ,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                            </div>
                            <div class="form__group">
                                    {!! Form::label('dateEnd','Date End :',['class' => 'form__label'])!!}
                                    {!! Form::date('dateEnd',$project->date_end,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                            </div>
                            <div class="form__group">
                                    {!! Form::label('statusmd','MD50 Status :',['class' => 'form__label'])!!}
                                    {!! Form::text('statusmd',$project->md50_status,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                            </div>
                            
                            {!! Form::button('Save Changes',['type' => 'submit','class'=>'btn btn--blue','data-action' => 'saveChanges']) !!}
                            {!! Form::close() !!}
                            
                    </div>
                    
                 
                          
                   
               </div>
        </div>
    </div> 
</main>