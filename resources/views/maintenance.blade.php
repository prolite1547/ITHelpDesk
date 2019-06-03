@extends('layouts.dashboardLayout')
@section('title','Maintenance')
@section('submenu')@endsection

@section('content')
    <main>
        <div class="plusToggleContainer">
            <div class="form-contactAdd">
                <button class="form-contactAdd__button u-margin-l " type="button"><i
                        class="fas fa-plus"></i> Add Contact Number
                </button>

                <div class="form-contactAdd__content-box u-display-n">
                    {!! Form::open(['method' => 'POST','class' => 'form','id' => 'addContact']) !!}
                    <div class="form__group">
                        {!! Form::label('store_id','Branch:',['class' => 'form__label']) !!}
                        {!! Form::select('store_id',[] ,null, ['placeholder' => '(Choose branch..)','class' => 'form__input form__input--select2','data-select' => 'contact','id' => 'contactBranchSelect','required']) !!}
                    </div>
                    <div class="form__group u-display-n" id="contactFormGroup">
                        {!! Form::label('number','#',['class' => 'form__label']) !!}
                        {!! Form::text('number',null, ['placeholder' => 'Enter number...','class' => 'form__input','required']) !!}
                        {!! Form::button('Add',['type' => 'submit','class'=>'btn','data-action' => 'addContact']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>


            <div class="form-sdcHieratchyAdd">
                <button class="form-branchAdd__button u-margin-l " type="button"><i class="fas fa-plus"></i>
                    Add SDC Hierarchy
                </button>

                <div class="form-branchAdd__content-box u-display-n">
                    {!! Form::open(['method' => 'POST','class' => 'form','id' => 'addHierarchy', 'action' => 'SDCController@addHierarchy']) !!}
                    <div class="form__group">
                        {!! Form::label('group_name','Group Name :',['class' => 'form__label'])!!}
                        {!! Form::text('group_name',null,['class' => 'form__input','placeholder' => 'Group name','required']) !!}
                        
                    </div>
                    <div class="form__group">
                        {!! Form::label('hierarchy','Hierarchy :',['class' => 'form__label'])!!}
                        {!! Form::select('app1', ['1' => 'Treasury 1','2'=>'Treasury 2','3'=>'Gov. Compliance','4'=>'Final Approver'], null, ['id' => 'approver1' ,'class' => 'form-control','required']) !!} >>
                        {!! Form::select('app2', ['2'=>'Treasury 2','3'=>'Gov. Compliance','4'=>'Final Approver'], null, ['id' => 'approver2' ,'class' => 'form-control']) !!} >>
                        {!! Form::select('app3', ['3'=>'Gov. Compliance','4'=>'Final Approver'], null, ['id' => 'approver3' ,'class' => 'form-control']) !!} >> 
                        {!! Form::select('app4', ['4'=>'Final Approver'], null, ['id' => 'approver4' ,'class' => 'form-control']) !!}
                    </div>
                    {!! Form::button('Add',['type' => 'submit','class'=>'btn','data-action' => 'addHierarchy']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="form-categoriesAdd">
                <button class="form-contactAdd__button u-margin-l" type="button"><i
                        class="fas fa-plus"></i> Add Categories
                </button>
                <div class="form-categoriesAdd__content-box u-display-n">
                    {!! Form::open(['method' => 'POST','class' => 'form','id' => 'addCategoryA']) !!}
                    <div class="form__group">
                        <label for="category_a" class="form__label form__label--block"><input type="radio" name="category" value="a" id="category_a" checked> Category A</label>
                        <label for="category_b" class="form__label form__label--block"><input type="radio" name="category" value="b" id="category_b"> Category B</label>
                        <label for="category_c" class="form__label form__label--block"><input type="radio" name="category" value="c" id="category_c"> Category C</label>
                    </div>
                    <div class="form__group form-categoriesAdd__parent">
                        {!! Form::label('category','Category A:',['class' => 'form__label']) !!}
                        {!! Form::select('category',[] ,null, ['placeholder' => '(Choose parent category..)','class' => 'form__input form__input--select2','data-select' => 'categories','id' => 'categoriesSelect','required']) !!}
                    </div>
                    <div class="form__group">
                        {!! Form::text('new_category',null,['class' => 'form__input','placeholder' => 'Enter New Category','required']) !!}
                    </div>
                    {!! Form::button('Add',['type' => 'submit','class'=>'btn','data-action' => 'addCategory']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </main>
@endsection
