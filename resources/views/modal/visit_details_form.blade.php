<div class="modal__content">
    <div class="model__header">
        <h3 class="modal__header">Adding of Store Visit Details</h3>
    </div>
    @isset($detail)
        {{Form::model($detail,['route' => ['updateDetail',$detail->id],'class' => 'form','id' => 'editStoreVisitDetail'])}}
    @endisset
    @empty($detail)
        {{Form::open(['class' => 'form','id' => 'addStoreVisitDetail'])}}
    @endempty
    <div class="form__group">
        {{Form::label('store','Store')}}
        {{Form::select('store_id',[],null,['placeholder' => 'Choose store','class' => 'branchSelect2','required'])}}
    </div>
    <div class="form__group">
        {{Form::label('it_personnel','IT Personnel')}}
        {{Form::select('it_personnel',[],null,['placeholder' => 'Choose personnel','class' => 'techUsersSelect2','required'])}}
    </div>
    <div class="form__group">
        {{Form::label('status_id','Status')}}
        {{Form::select('status_id',$store_visit_status_arr,2,['placeholder' => 'Choose status','required'])}}
    </div>
    <div class="form__group">
        {{Form::label('start_date','Date Start',['required'])}}
        {{Form::date('start_date')}}
        {{Form::label('end_date','Date End',['required'])}}
        {{Form::date('end_date')}}
    </div>
    <div class="form__group">
        {!! Form::button('Cancel',['type' => 'button','class' => 'btn btn--red','data-action' => 'cancel']) !!}
        {!! Form::button('Save',['type' => 'submit','class' => 'btn btn--blue','data-action' => 'fix']) !!}
    </div>
    {{Form::close()}}
</div>
