@extends('datacorrections.index')
@section('title', 'System Data Correction')
@section('content')

<div class="form-group" style="padding:30px;">
<form class="needs-validation" action="{{ route('sdc.store') }}" method="post" enctype="multipart/form-data" novalidate>
   
{{ csrf_field() }}

    <div class="sdc-header" >
            <div  class="py-3 text-center mb-4">
                    <h2>SYSTEM DATA CORRECTION FORM</h2>
            </div>
            <hr>
            <div  class="row text-center">
                <div class="col-md-6">
                    <span><strong>TICKET NO : </strong></span>
                    <span id="ticketno">{{ "TID".$ticket->id }}</span>
                <input type="hidden" name="ticketno" value="{{$ticket->id}}">
                </div>
                <div class="col-md-6">
                        <span><strong>SDC NO :</strong> </span>
                        <span id="sdcno">
                                @if ($sdc != null)
                                SDC{{ $sdc->id+=1 }}
                                @else
                                SDC1
                                @endif
                        </span>
                      <input type="hidden" name="sdcno" value="<?php
                                if($sdc != null){
                                        echo "SDC".$sdc->id;
                                }else{
                                        echo "SDC1";
                                }
                      ?>">
                      <input type="hidden" name="sdc_id" value="
                      <?php 
                        if(isset($sdc->id)){
                                echo $sdc->id;
                        }else{
                                echo 1;
                        }
                      ?>
                      ">
                      <input type="hidden" name="ticket_created" value ="<?php 
                                 $newDate = date("m/d/Y", strtotime($ticket->created_at));   
                                echo  $newDate;
                      ?>">
                </div> 
            </div>
        
    </div>
    <hr>

{{-- SUPPORT FILL IN --}}
        {{-- @if(Auth::user()->role_id == 1 OR Auth::user()->role_id == 2 OR Auth::user()->role_id == 3 OR Auth::user()->role_id == 4 ) --}}
                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label for="datesubmitted">Date Submitted : </label>
                            {{-- <div class="input-group date" data-provide="datepicker"> --}}
                                <input type="text" id="datesubmitted" name="datesubmitted" class="form-control"  value="<?php 
                                          date_default_timezone_set("Asia/Manila");
                                                        $currentDate =  date('m/d/Y');
                                                        $newDate = date("m/d/Y", strtotime($currentDate));    

                                                        echo $newDate;        
                                ?>" readonly>
                                {{-- <div class="input-group-addon invalid-tooltip ">
                                        Valid date is required.
                                </div>              --}}
                            {{-- </div> --}}
                        </div>

                      <div class="col-md-8  mb-3">
                            <label for="requestor">Name of Requestor : </label>
                            <div class="input-group">
                            <input type="text" class="form-control" name="requestor" id="requestor"   value="{{ strtoupper(Auth::user()->full_name) }}" readonly>
                                
                            </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-5 mb-3">
                         <div class="input-group">
                            <label for="supervisor">Dept. Supervisor : </label>
                            <select class="custom-select d-block w-100" name="supervisor" id="supervisor" required>
                                <option data-id="0"   value="">Choose...</option>
                                 @foreach ($users  as $user)
                                        <option data-id="{{ $user->id }}" value="{{ $user->full_name }}">{{ $user->full_name }}</option>
                                 @endforeach
                            </select>
                            
                                {{-- <input type="text" class="form-control" name="supervisor"  id="supervisor" style="text-transform:uppercase;" required> --}}
                                <div class="invalid-tooltip " style="width: 100%;">
                                    Valid dept. supervisor is required
                                </div>
                            </div>   
                        </div>

                        <div class="col-md-4 mb-3">
                                <label for="department">Department : </label>
                                <input type="text" class="form-control" name="department" id="department"   readonly>
                           
                                    {{-- <select class="custom-select d-block w-100" name="department" id="department" required>
                                        <option data-id="0" value="">Choose...</option>
                                         @foreach ($departments  as $department)
                                                <option data-id="{{ $department->id }}" value="{{ $department->department }}">{{ $department->department }}</option>
                                         @endforeach
                                    </select>
                                    <div class="invalid-tooltip ">
                                        Please select a valid department.
                                    </div> --}}
                        </div>

                        <div class="col-md-3 mb-3">
                                <label for="position">Position : </label>
                                <input type="text" class="form-control" name="position" id="position"   readonly>
                                {{-- <select class="custom-select d-block w-100" name="position" id="position"  required>
                                        <option value="">Choose...</option>  --}}
                                        {{-- @foreach ($positions  as $position)
                                                <option value="{{ $position->position }}">{{ $position->position }}</option>
                                        @endforeach --}}
                                </select>
                                {{-- <div class="invalid-tooltip">
                                         Valid position is required
                                </div> --}}
                        </div>
                    </div>
{{-- @endif --}}

                    <hr>
                    <div style="background-color:#2c3e50;color:white;" class="py-3 text-center mb-3">
                            <h4>DETAILS</h4>
                    </div>

                    
{{-- VISIBLE TO TREASURY USERS AND SUPPORTS --}}
{{-- @if (Auth::user()->role_id == 1 OR Auth::user()->role_id == 2 OR Auth::user()->role_id == 3 OR Auth::user()->role_id == 4 OR Auth::user()->role_id == 5 ) --}}

                    <div class="row mb-3">
                            <div class="col-md-6 mb-3 ">
                                <label for="affected">Affected Store/Server : </label>
                                   
                                <select class="custom-select d-block w-100" name="affected" id="affected">
                                        <option data-id="0" value="">Choose...</option>
                                                @foreach ($stores  as $store)
                                                        <option data-id="{{ $store->id }}" value="{{ $store->store_name }}">{{ $store->store_name }}</option>
                                                @endforeach
                                        </select>
                                <div class="invalid-tooltip">
                                        Valid affected store/server is required
                                </div>
                                    
                            </div>

                            <div class="col-md-6">
                                    <label for="terminalname">Terminal Name (For POS) : </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="terminalname" id="terminalname" >
                                        {{-- <div class="invalid-feedback" style="width: 100%;">
                                            Valid affected store/server required
                                        </div> --}}
                                    </div>   
                            </div>
                    </div>
{{-- @endif --}}
{{-- END --}}

{{-- ATTACHMENTS HERE (DOWNLOADABLE BY ALL USERS) --}}

<div class="row mb-3">
                <div class="col-md-12">
                        <label for="upfile">Upload Attachment(s) :</label>
                        <div class="input-group">
                                <input id="upfile" name="upfile[]" type="file" multiple hidden>
                                <label class="btn btn-danger btn-sm" onclick="document.getElementById('upfile').click()">Choose File(s)</label>
                                
                        </div>
                     
                        <ol class="mt-2" id="selectedFiles">

                        </ol>
                </div>
</div>

{{-- END --}}

{{-- VISIBLE TO ALL USERS --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                              <label for="dfindings">Findings and Recommendations :</label>
                          <div class="input-group">
                              <textarea type="text" class="form-control" name="dfindings" cols="5" rows="5" id="dfindings"  ></textarea>
                              <div class="invalid-tooltip " style="width: 100%;">
                                  Findings and recommendations is required
                              </div>
                          </div>   
                        </div>
                   </div>
{{-- END --}}

{{-- @if (Auth::user()->role_id == 1 OR Auth::user()->role_id == 2 OR Auth::user()->role_id == 3 OR Auth::user()->role_id == 4) --}}
<div class="row">
        <div class="col-md-3 mb-2">
                <label for="app_group">Approver Group : </label>
        </div>
        <div class="col-md-9 mb-2">
                <label for="hierarchy" id = "hlabel">Hierarchy : </label>
        </div>
</div>
<div class="row mb-5">
        <div class="col-md-3 mb-2">
                 
                    <select class="custom-select d-block w-100" name="app_group" id="app_group" required>
                        <option value="">--- Choose ---</option>
                        @foreach ($appgroup as $group)
                                <option value="{{ $group->group }}">{{ $group->group }} </option>
                        @endforeach
                        <option value="CUS">CUSTOM</option>
                       
                    </select>
                    <div class="invalid-tooltip">
                           Please choose an approver group
                    </div>
        </div>
        <div class="col-md-9 mb-2">
                
             <div class="selected-group">
                     
             </div>
    </div>
</div>
    
{{-- @endif --}}
{{-- END OF 1ST PART SUPPORT FORM --}}
 
                <hr class="mb-4">
 
                  <div class="row">
                       
                        <div class="col-md-12">
                                <input class="btn btn-primary btn-md btn-block" type="submit" name="action" value="SAVE"> 
                        </div>
                          
                  </div>
                  <hr>
                     <div class="row  mb-5">
                            <div class="col-md-12">
                                <input class="btn btn-success btn-md btn-block confirmation2" type="submit" name="action" value="POST DATA CORRECTION" > 
                                <input class="btn btn-success btn-md btn-block" type="submit" style="display:none;" id="confirmPost" name="action" value="POST DATA CORRECTION" > 
                         
                            </div>
                           

                            
                            {{-- <div class="col-md-6">
                                <select class="custom-select d-block w-100" name="forwardto" id="forwardto">
                                        <option value="1">Treasury 1</option> 
                                        <option value="2">Treasury 2</option> 
                                        <option value="3">Gov. Compliance</option>
                                        <option value="4">Final Approver</option> 
                                </select>
                                <div class="input-group-addon invalid-tooltip">
                                        This field is required
                                </div>    
                            </div> --}}
                    </div>
                   
            </form>
            
    </div>
    


@endsection