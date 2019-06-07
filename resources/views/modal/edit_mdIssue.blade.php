<main>
        <div class="window" style="width:100%">
                <div class="window__title-box">
                    <h3 class="heading-tertiary">Edit Issue Details</h3>
                </div>
                <div class="window__content" style="display:block;">
                       <div class="window__form">
                           
                                <div class="fieldset__contact-inputs" >
                                    {!! Form::open(['method' => 'POST','route' => 'edit.mdis','id' => 'form-AddMdIssue']) !!}
                                    <div class="form__group">
                               
                                                {!! Form::text('issueID', $issue->id, ['class' => 'form__input','placeholder' => ' ','required','readonly','minlength' => 2, 'style'=>'width:300px;display:none;']) !!}
                                     </div>
                                    <div class="form__group">
                                            {!! Form::label('issueName','Name of Issue :',['class' => 'form__label'])!!}
                                            {!! Form::text('issueName',$issue->issue_name,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                                    </div>
                                   
                                    <div class="form__group">
                                            {!! Form::label('status','Status :',['class' => 'form__label'])!!}
                                            {!! Form::select('status', ['On-Going' => 'On-Going','Done' => 'Done'], $issue->status, ['placeholder' => '(select status)','class' => 'form__input   ','required', 'style'=>'width:300px;']) !!}
                                    </div>
                                    <div class="form__group">
                                            {!! Form::label('dateStart','Date Start :',['class' => 'form__label'])!!}
                                            {!! Form::date('dateStart',$issue->start_date,['class' => 'form__input','placeholder' => ' ','required','minlength' => 2, 'style'=>'width:300px;']) !!}
                                    </div>
                                    <div class="form__group">
                                            {!! Form::label('dateEnd','Date End :',['class' => 'form__label'])!!}
                                            {!! Form::date('dateEnd',$issue->end_date,['class' => 'form__input','placeholder' => ' ','minlength' => 2, 'style'=>'width:300px;']) !!}
                                    </div>
                                    
                                    {!! Form::button('Update Issue',['type' => 'submit','class'=>'btn','data-action' => 'addProject']) !!}
                                    {!! Form::close() !!}
                                 
                            </div>
                            
                         
                                  
                           
                       </div>
                </div>
        </div>     
</main>