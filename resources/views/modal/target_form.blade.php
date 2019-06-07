    <div class="modal__content">
        <div class="model__header">
            <h3 class="modal__header">Adding of Target # of Stores to Visit</h3>
        </div>
        @isset($target)
            {{Form::model($target,['route' => ['updateTarget',$target->id],'class' => 'form','id' => 'editStoreVisitTarget'])}}
        @endisset
        @empty($target)
            {{Form::open(['class' => 'form','id' => 'addStoreVisitTarget'])}}
        @endempty
        <div class="form__group">
            {{Form::label('month','Month')}}
            {{Form::selectMonth('month',null,['required'])}}
            {{Form::label('year','Year')}}
            {{Form::selectRange('year',  date("Y") - 1, date("Y") + 3,date("Y"),['required'])}}
        </div>
        <div class="form__group">
            {{Form::label('num_of_stores','No. of Stores')}}
            {{Form::text('num_of_stores',null,['required'])}}
        </div>
        <div class="form__group">
            {!! Form::button('Save',['type' => 'submit','class' => 'btn btn--blue','data-action' => 'fix']) !!}
        </div>
        {{Form::close()}}
    </div>




