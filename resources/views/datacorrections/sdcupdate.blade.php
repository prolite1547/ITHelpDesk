@extends('datacorrections.index')
@section('title','System Data Correction (SDC'.$sdc->id.')')
@section('content')

<?php 
        $role_id = Auth::user()->role_id;
        $status = $sdc->status;
        $forward_status = $sdc->forward_status;
 
        if((($role_id >=1 AND $role_id <=4) AND ($status!=0 AND $status!=3 AND $status!=2)) OR (($role_id == 5 AND $forward_status != 1) OR ($role_id == 5 AND $forward_status == 1 AND $status == 2)) OR (($role_id == 6 AND $forward_status != 2) OR ($role_id == 6 AND $forward_status == 2 AND $status == 2)) OR (($role_id == 7 AND $forward_status != 3) OR ($role_id == 7 AND $forward_status == 3 AND $status == 2)) OR (($role_id == 8 AND $forward_status != 4) OR ($role_id == 8 AND $forward_status == 4 AND $status == 2))){
                echo "<script>location.href=window.history.back()</script>";
        }
?>
<div class="form-group" style="padding:30px;">
<form id="updateForm" class="needs-validation"  action="{{ route('sdc.update', ['id'=>$sdc->id]) }}" enctype="multipart/form-data" method="post" novalidate>
   
{{ csrf_field() }}
{{ method_field('PUT') }}

<div class="sdc-header" >
            <div  class="py-3 text-center mb-4">
                    <h2>SYSTEM DATA CORRECTION FORM</h2>
            </div>
            <hr>
            <div  class="row text-center">
                <div class="col-md-6">
                    <span><strong>TICKET NO : </strong></span>
                    <span id="tickerno">TID{{ $sdc->ticket_no }}</span>
                <input type="hidden" name="ticketno" value="{{ $sdc->ticket_no }}">
                </div>
                <div class="col-md-6">
                        <span><strong>SDC NO :</strong> </span>
                        <span id="sdcno">SDC{{ $sdc->id }}</span>
                      
                </div> 

                <input type="hidden" name="sdc_id" value="<?php 
                        if(isset($sdc->id)){
                                echo $sdc->id;
                        }else{
                                echo 1;
                        }
                      ?>">
            </div>
        
    </div>
    <hr>
    @if (isset($sdc->rejection))
    <div class="row mb-3">
            <div class="col-md-12">
                    <?php 
                        $fstatus = $sdc->forward_status;
                        $from = "";

                        switch ($fstatus) {
                                case '1':
                                    $from = "TREASURY 1";
                                break;
                                case '2':
                                    $from = "TREASURY 2";
                                break;
                                case '3':
                                    $from = "GOV. COMPLIANCE";
                                break;
                                case '4':
                                    $from = "FINAL APPROVER";
                                break;
                                default:
                                      
                                break;
                        }
                        
                     ?>
                    <label for="rejection">Rejected from <?php echo $from; ?> <br> Reason(s) :</label>
                    <div class="input-group">
                            <textarea type="text" class="form-control text-area" name="rejection" cols="5" rows="5" id="rejection" readonly >{{ $sdc->rejection }}</textarea> 
                    </div>   
            </div>
    </div>
@endif
{{-- SUPPORT FILL IN --}}
@if(Auth::user()->role_id == 1 OR Auth::user()->role_id == 2 OR Auth::user()->role_id == 3 OR Auth::user()->role_id == 4 OR Auth::user()->role_id == 8  )
                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label for="datesubmitted">Date Submitted : </label>
                            
                                <input type="text"  id="datesubmitted" name="datesubmitted" value="<?php 
                                        if($sdc->status == 0){
                                              date_default_timezone_set("Asia/Manila");
                                              $currentDate =  date('m/d/Y');
                                              $newDate = date("m/d/Y", strtotime($currentDate));    

                                              echo $newDate;  
                                        }else{
                                              echo $sdc->date_submitted;
                                        }
                                ?>" class="form-control input-validate" readonly>
                                <div class="input-group-addon invalid-tooltip ">
                                        Valid date is required.
                                </div>             
                            
                        </div>

                      <div class="col-md-8  mb-3">
                            <label for="requestor">Name of Requestor : </label>
                            <div class="input-group">
                            <input type="text" class="form-control input-validate" name="requestor" value="{{ $sdc->requestor_name }}" id="requestor" readonly>
                                <div class="invalid-tooltip " style="width: 100%;">
                                    Valid name of requestor is required
                                </div>
                            </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-5 mb-3">
                                
                            <label for="supervisor">Dept. Supervisor : </label>
                            <div class="input-group">
                            <select class="custom-select d-block w-100" name="supervisor" id="supervisor" required @if (Auth::user()->role_id == 8 OR ($sdc->status != 2 AND $sdc->status != 0 ))
                                        disabled   
                                       @endif>
                                        <option data-id="0"   value="">Choose...</option>
                                         @foreach ($users  as $user)
                                                <option data-id="{{ $user->id }}" value="{{ $user->full_name }}" @if ($user->full_name == $sdc->dept_supervisor)
                                                    selected
                                                @endif>{{ $user->full_name }}</option>
                                         @endforeach
                                    </select>
                                  
                                                 
                                                <div class="invalid-tooltip " style="width: 100%;">
                                                    Valid dept. supervisor is required
                                                </div>
                        </div>        
                            {{-- <div class="input-group">
                                <input type="text" class="form-control input-validate" name="supervisor" style="text-transform:uppercase;"  value="{{ $sdc->dept_supervisor }}" id="supervisor" required @if (Auth::user()->role_id == 8 OR ($sdc->status != 2 AND $sdc->status != 0 ))
                                disabled   
                               @endif>
                                <div class="invalid-tooltip " style="width: 100%;">
                                    Valid dept. supervisor is required
                                </div>
                            </div>    --}}
                        </div>

                        <div class="col-md-4 mb-3">
                                <label for="department">Department : </label>
                                <input type="text" class="form-control" name="department" id="department"  value="<?php echo $sdc->department ?>"   @if (Auth::user()->role_id == 8 OR ($sdc->status != 2 AND $sdc->status != 0 ))
                                disabled   
                                @else 
                                readonly
                               @endif>
                                  {{-- <select class="custom-select d-block w-100 input-validate" name="department" id="department" required @if (Auth::user()->role_id == 8 OR ($sdc->status != 2 AND $sdc->status != 0 ))
                                                disabled   
                                               @endif>
                                        
                                        @foreach ($departments as $department)
                                               
                                                         <option data-id="{{ $department->id }}" value="{{$department->department}}"  @if ($sdc->department  == $department->department)selected @endif>{{ $department->department }}</option>
                                    
                                               
                                         @endforeach
                                    </select>
                                    <div class="invalid-tooltip ">
                                        Please select a valid department.
                                    </div> --}}
                            </div>

                        <div class="col-md-3 mb-3">
                                <label for="position">Position : </label>
                                <input type="text" class="form-control" name="position" id="position"  value="<?php echo $sdc->position ?>"   @if (Auth::user()->role_id == 8 OR ($sdc->status != 2 AND $sdc->status != 0 ))
                                disabled   
                                @else 
                                readonly
                               @endif>
                         {{-- <select class="custom-select d-block w-100 input-validate" data-position="{{ $sdc->position }}" name="position" id="position"   required @if (Auth::user()->role_id == 8 OR ($sdc->status != 2 AND $sdc->status != 0 ))disabled @endif>
                                
                                     
                                </select> --}}
                                <div class="invalid-tooltip">
                                          Valid position is required
                                </div>
                        </div>
                    </div>
                    <hr>
@endif
                    
                    <div style="background-color:#2c3e50;color:white;" class="py-3 text-center mb-3">
                            <h4>DETAILS</h4>
                    </div>

{{-- VISIBLE TO TREASURY USERS AND SUPPORTS --}}
@if (Auth::user()->role_id == 1 OR Auth::user()->role_id == 2 OR Auth::user()->role_id == 3 OR Auth::user()->role_id == 4 OR Auth::user()->role_id == 5 OR Auth::user()->role_id == 6 OR Auth::user()->role_id == 8)
                  
                    <div class="row mb-3">
                            <div class="col-md-6 mb-3 ">
                                        <label for="affected">Affected Store/Server : </label>
                                   
                                        <select class="custom-select d-block w-100" name="affected" id="affected"   @if ($sdc->status != 2 && $sdc->status != 0) disabled  @endif>
                                                <option data-id="0" value="" >Choose...</option>
                                                        @foreach ($stores  as $store)
                                                                <option data-id="{{ $store->id }}" value="{{ $store->store_name }}" @if ($store->store_name == $sdc->affected_ss)
                                                                    selected
                                                                @endif>{{ $store->store_name }}</option>
                                                        @endforeach
                                                </select>
                                        <div class="invalid-tooltip">
                                                Valid affected store/server is required
                                        </div>
                            </div>

                            <div class="col-md-6">
                                    <label for="terminalname">Terminal Name (For POS) : </label>
                                    <div class="input-group">
                                     <input type="text" class="form-control input-validate" name="terminalname" value="{{ $sdc->terminal_name }}" id="terminalname" 
                                     @if (Auth::user()->role_id  != 1 OR Auth::user()->role_id  != 2 OR Auth::user()->role_id  != 3 OR Auth::user()->role_id  != 4 )
                                     @if ($sdc->status != 2 && $sdc->status != 0)
                                         disabled
                                     @endif
                                     @endif >
                                        {{-- <div class="invalid-feedback" style="width: 100%;">
                                            Valid affected store/server required
                                        </div> --}}
                                    </div>   
                            </div>
                    </div>
@endif
{{-- END --}}

{{-- ATTACHMENTS HERE (DOWNLOADABLE BY ALL USERS) --}}
@if ($sdc->status == 0 || $sdc->status == 2)
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
@endif

<div class="row mb-3">
                <div class="col-md-12">
                        <label for="attachments">Attachment(s) :</label>
                        <ul id ="attachments" style="list-style: none;">
                                       
                                        <?php $count = 0;?>
                                @foreach ($sdc->attachments as $attachment)
                                        @if ($attachment->role_id != '5')
                                                <li  id="<?php echo $count;?>">
                                                
                                                                @if ($sdc->status == 0 Or $sdc->status == 2 )
                                                                <a data-att_id = "{{ $attachment->id }}" data-name = "{{ $attachment->original_name }}" data-id= "<?php echo $count; ?>"class="btn btn-danger btn-sm mb-1 rmvAttachment" style="color:white;">X</a>
                                                                @endif
                                                                <a href="/storage/sdc_attachments/{{$attachment->original_name}}" download>{{ $attachment->original_name }}</a>
                                        
                                                
                                                </li>
                                                <?php 
                                                $count+=1;
                                                ?>
                                        @endif
                                @endforeach
                        </ul>

                        
                </div>
</div>

{{-- END --}}

{{-- VISIBLE TO ALL USERS --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                              <label for="dfindings">Findings and Recommendations :</label>
                          <div class="input-group">
                              <textarea type="text" class="form-control text-area" name="dfindings" cols="5" rows="5" id="dfindings" 
                              @if (Auth::user()->role_id  != 1 OR Auth::user()->role_id  != 2 OR Auth::user()->role_id  != 3 OR Auth::user()->role_id  != 4 )
                              @if ($sdc->status != 2 && $sdc->status != 0)
                                 disabled
                              @endif
                              @endif >{{ $sdc->findings_recommendations }}</textarea>
                              <div class="invalid-tooltip " style="width: 100%;">
                                  Findings and recommendations is required
                              </div>
                          </div>   
                        </div>
                   </div>
{{-- END --}}
  
{{-- END OF 1ST PART SUPPORT FORM --}}

{{-- FOR TREASURY VISIBLE FORM --}}
@if (Auth::user()->role_id == 5 OR Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                        <hr>
                            <div style=" color:darkslategray;padding:30px" class="py-3">
                                    <h5><b> - Hard Copy for POS - </b></h5>
                            </div>
                        <hr>
                            <div class="row mb-3">
                                    <div class="col-md-4 mb-3">
                                            <label for="hclastzreading">Last Z Reading : </label>
                                            <div class="input-group">
                                              <input type="text" class="form-control input-validate" name="hclastzreading" value="{{ $sdc->hc_last_z_reading }}" id="hclastzreading"  required @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                                  disabled
                                              @endif >
                                                <div class="invalid-tooltip " style="width: 100%;">
                                                    Last Z Reading field is required
                                                </div>
                                            </div>   
                                    </div>
                                    <div class="col-md-4 mb-3">
                                            <label for="hclastdcr">Last DCR : </label>
                                            <div class="input-group">
                                             <input type="text" class="form-control input-validate" name="hclastdcr" value="{{ $sdc->hc_last_dcr }}" id="hclastdcr" @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                             disabled
                                            @endif   >
                                                <div class="invalid-tooltip " style="width: 100%;">
                                                   Last DCR
                                                </div>
                                            </div>   
                                    </div>
                                    <div class="col-md-4 mb-3">
                                            <label for="hclasttransactionid">Last Transaction ID : </label>
                                            <div class="input-group">
                                            <input type="text" class="form-control input-validate" name="hclasttransactionid" value="{{ $sdc->hc_last_transaction_id }}" id="hclasttransactionid" required @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                            disabled
                                        @endif  >
                                                <div class="invalid-tooltip " style="width: 100%;">
                                                     Last Transaction ID is required
                                                </div>
                                            </div>   
                                    </div>
                                </div>

                                <div class="row mb-3">
                                        <div class="col-md-6 mb-3">
                                                <label for="hclastzreading">Last Accumulator : </label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                             <div class="custom-control custom-checkbox">
                                                                    <input id="tally" name="hctally" type="checkbox" value="1"  <?php if($sdc->hc_last_accumulator){ echo "checked";}?> class="custom-control-input hcchk" @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                                                    disabled
                                                                @endif >
                                                                    <label class="custom-control-label" for="tally">Tally</label>
                                                              </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                                <div class="custom-control custom-checkbox">
                                                                        <input id="nottally" name="hctally" value="0" type="checkbox" <?php if($sdc->hc_last_accumulator == "0" ){ echo "checked";}?> class="custom-control-input hcchk" @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                                                        disabled
                                                                    @endif >
                                                                        <label class="custom-control-label" for="nottally">Not Tally</label>
                                                                </div>
                                                        </div>
                                                              
                                                    </div>
                                                      
                                         </div>
                                         
                                <div class="col-md-6 mb-3">
                                    <label for="hclastorno">Last OR No. : </label>
                                    <div class="input-group">
                                     <input type="text" class="form-control input-validate" name="hclastorno" value="{{ $sdc->hc_last_or_no }}" id="hclastorno" required @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                     disabled
                                 @endif >
                                        <div class="invalid-tooltip " style="width: 100%;">
                                            Last OR No. field is required
                                        </div>
                                    </div>   
                            
                            </div>
                            </div>
               
                            <hr>
                            <div style=" color:darkslategray;padding:30px" class="py-3">
                                    <h5><b> - Soft Copy for POS -</b></h5>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                            <label for="sclastzreading">Last Z Reading : </label>
                                            <div class="input-group">
                                            <input type="text" class="form-control input-validate" name="sclastzreading" value="{{ $sdc->sc_last_z_reading }}" id="sclastzreading" required @if (Auth::user()->role_id ==8 OR $sdc->status == 3 OR $sdc->status == 2)
                                            disabled
                                        @endif  >
                                                <div class="invalid-tooltip " style="width: 100%;">
                                                    Last Z Reading field is required
                                                </div>
                                            </div>   
                                    </div>
                                 
                                    <div class="col-md-6 mb-3">
                                            <label for="sclasttransactionid">Last Transaction ID : </label>
                                            <div class="input-group">
                                            <input type="text" class="form-control input-validate" name="sclasttransactionid" value="{{ $sdc->sc_last_transaction_id }}" id="sclasttransactionid" required @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                            disabled
                                        @endif  >
                                                <div class="invalid-tooltip " style="width: 100%;">
                                                    Last Transaction ID field is required
                                                </div>
                                            </div>   
                                    </div>
                                </div>

                                <div class="row mb-3">
                                        <div class="col-md-6 mb-3">
                                                <label for="sclastzreading">Last Accumulator : </label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                             <div class="custom-control custom-checkbox">
                                                                    <input id="sctally" name="sctally" type="checkbox" value="1" <?php if($sdc->sc_last_accumulator){ echo "checked";}?> class="custom-control-input scchk" @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                                                    disabled
                                                                @endif >
                                                                    <label class="custom-control-label" for="sctally">Tally</label>
                                                              </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                                <div class="custom-control custom-checkbox">
                                                                        <input id="scnottally" name="sctally" type="checkbox" value="0" <?php if($sdc->sc_last_accumulator == "0"){ echo "checked";}?> class="custom-control-input scchk" @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                                                        disabled
                                                                    @endif >
                                                                        <label class="custom-control-label" for="scnottally">Not Tally</label>
                                                                </div>
                                                        </div>
                                                              
                                                    </div>
                                                      
                                         </div>
                                         
                                <div class="col-md-6 mb-3">
                                    <label for="sclastorno">Last OR No. : </label>
                                    <div class="input-group">
                                    <input type="text" class="form-control input-validate" name="sclastorno" value="{{ $sdc->sc_last_or_no }}" id="sclastorno" required  @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                    disabled
                                @endif >
                                        <div class="invalid-tooltip " style="width: 100%;">
                                            Last OR No. field is required
                                        </div>
                                    </div>   
                            
                            </div>
                            </div>
                   
                   

                    <hr>
                    <div style="background-color:#2c3e50;color:white;" class="py-3 text-center mb-3">
                            <h4>PRE-CORRECTION VERIFICATION</h4>
                    </div>
                    
                 
@endif   
 

{{-- VIEWABLE BY GOV.COMPLIANCE/APPROVER USERS --}}
@if (Auth::user()->role_id == 5 OR Auth::user()->role_id == 6 OR Auth::user()->role_id == 7 OR Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2 )
                      <hr>

                      <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                  <label for="prezreadingno">Next Z Reading No.  :</label>
                              <div class="input-group">
                              <input type="text" class="form-control input-validate" name="prezreadingno" value="{{ $sdc->pre_next_z_reading }}" id="prezreadingno" @if (Auth::user()->role_id != 5) readonly @endif required>
                                    <div class="invalid-tooltip " style="width: 100%;">
                                            Valid Z Reading No. field is required
                                    </div>
                              </div>   
                            </div>

                            <div class="col-md-4 mb-3">
                                    <label for="prenextorno">Next OR No.  :</label>
                                      <div class="input-group">
                                      <input type="text" class="form-control input-validate" name="prenextorno" value="{{ $sdc->pre_next_or_no }}" id="prenextorno" @if (Auth::user()->role_id != 5) readonly @endif required >
                                      <div class="invalid-tooltip " style="width: 100%;">
                                              Next OR No. field is required
                                      </div>
                                </div>   
                              </div>

                              <div class="col-md-4">
                                    <label for="prelasttransactionid">Last Transaction ID :</label>
                                      <div class="input-group">
                                      <input type="text" class="form-control input-validate" name="prelasttransactionid" value="{{ $sdc->pre_last_transaction_id }}" @if (Auth::user()->role_id != 5) readonly @endif id="prelasttransactionid" required >
                                      <div class="invalid-tooltip " style="width: 100%;">
                                                Last Transaction ID field is required
                                      </div>
                                </div>   
                              </div>
                      </div>

                      
                      <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                        <label for="prelastacc">Last Accumulator :</label>
                                        <div class="input-group">
                                        <input type="text" class="form-control input-validate" name="prelastacc" value="{{$sdc->pre_last_acc}}" id="prelastacc" @if (Auth::user()->role_id != 5) readonly @endif  >
                                        <div class="invalid-tooltip " style="width: 100%;">
                                                Valid last accumulator
                                        </div>
                                </div>   
                                </div>

                                <div class="col-md-6">
                                        <label for="prelastorno">Last OR No. :</label>
                                        <div class="input-group">
                                        <input type="text" class="form-control input-validate" name="prelastorno" value="{{$sdc->pre_last_or_no}}" id="prelastorno" @if (Auth::user()->role_id != 5) readonly @endif required >
                                        <div class="invalid-tooltip " style="width: 100%;">
                                                Last OR No. field is required
                                        </div>
                                </div>   
                                </div>
                      </div>
@endif

{{-- END OF VIEW --}}

@if ($sdc->status != 0)
<div class="row mb-3">
        <div class="col-md-12">
                <label for="attachments1">Additional Attachment(s) :</label>
                <ul id ="attachments1" style="list-style: none;">
                                <?php $count = 0;?>
                        @foreach ($sdc->attachments as $attachment)
                                @if ($attachment->role_id == '5')
                                        <li  id="<?php echo $count . "1";?>">
                                        
                                                        @if ($sdc->forward_status == 1 AND $sdc->status == 1)
                                                        <a data-att_id = "{{ $attachment->id }}" data-name = "{{ $attachment->original_name }}" data-id= "<?php echo $count . "1"; ?>"class="btn btn-danger btn-sm mb-1 rmvAttachment1" style="color:white;">X</a>
                                                        @endif
                                                        <a href="/storage/sdc_attachments/{{$attachment->original_name}}" download>{{ $attachment->original_name }}</a>
                                
                                        
                                        </li>
                                <?php 
                                        $count+=1;
                                 ?>
                                @endif
                               
                        @endforeach
                </ul>

                
        </div>
</div> 
@endif
{{-- REMARKS FOR TREASURY --}}
@if (Auth::user()->role_id == 5 OR Auth::user()->role_id == 6 OR Auth::user()->role_id == 8 OR $sdc->status == 2 OR $sdc->status == 3)
@if (Auth::user()->role_id == 5)
<div class="row mb-3">
                <div class="col-md-12">
                        <label for="upfile">Upload Attachment(s) :</label>
                        <div class="input-group">
                                <input id="upfile1" name="upfile1[]" type="file" multiple hidden>
                                <label class="btn btn-danger btn-sm" onclick="document.getElementById('upfile1').click()">Choose File(s)</label>
                                
                        </div>
                     
                        <ol class="mt-2" id="selectedFiles1">
        
                        </ol>
                </div>
</div>
@endif


<div class="row mb-3">
        <div class="col-md-12">
                <label for="checkedby">Checked by:</label> 
                   
                <div class="input-group">
                <input type="text" class="form-control" style="text-transform: uppercase" name="checkedby" value="<?php 
                      if(isset($sdc->ty1_fullname)){
                              echo strtoupper($sdc->ty1_fullname);
                      }else{
                              echo strtoupper(Auth::user()->full_name);
                      }    
                      ?>" 
                      {{-- @if (Auth::user()->role_id != 5)
                         disabled 
                      @endif --}}
                       id="checkedby" readonly>
                  
                  <div class="invalid-tooltip " style="width: 100%;">
                                 Please enter your full name
                </div>
                </div>   
              </div>
</div>

                                   
<div class="row mb-3">
        <div class="col-md-12">
                <label for="date_checked">Date : </label>
                    
                       <input type="text" class="form-control" name="date_checked"  value="<?php
                            if(isset($sdc->ty1_date_verified)){
                                    echo $sdc->ty1_date_verified;
                            }else{
                                  
                                    date_default_timezone_set("Asia/Manila");
                                    $currentDate =  date('m/d/Y');
                                    $newDate = date("m/d/Y", strtotime($currentDate));    

                                    echo $newDate;
                               
                            }
                        ?>" id="date_checked"  readonly >
              
                        <div class="input-group-addon invalid-tooltip ">
                                Valid date is required.
                        </div>             
                    
                </div>
  </div>

        <div class="row mb-3">
                <div class="col-md-12">
                        <label for="tyremarks">Remarks @if (Auth::user()->role_id == 6 OR Auth::user()->role_id == 8 OR $sdc->status == 2 OR $sdc->status == 3) 
                            (Treasury 1)
                        @endif :</label>
                        <div class="input-group">
                        <textarea type="text" class="form-control text-area" name="ty1remarks" cols="5" rows="5" id="ty1remarks" required @if (Auth::user()->role_id >= 6 OR $sdc->status == 2 OR $sdc->status == 3)
                                disabled
                        @endif>{{ $sdc->ty1_remarks }}</textarea>
                        <div class="invalid-tooltip " style="width: 100%;">
                                        Remarks field is required
                        </div>
                        </div>   
                </div>
        </div>
 
 
@endif        
{{-- REMARKS END --}}  
 
@if (Auth::user()->role_id == 6 OR Auth::user()->role_id == 7 OR Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
<hr>
<div style="background-color:#2c3e50;color:white;" class="py-3 text-center mb-3">
        <h4>PRE-CORRECTION VERIFICATION</h4>
</div>
<hr>
                      <div class="row mb-3">
                            <div class="col-md-12">
                                  <label for="preverifiedby">Verified by :</label>
                                  
                              <div class="input-group">
                                    <input type="text" class="form-control" name="preverifiedby"  value="<?php 
                                        if(isset($sdc->pre_verified_by)){
                                                echo strtoupper($sdc->pre_verified_by);
                                        }else{
                                                if(Auth::user()->role_id == 6){
                                                        echo strtoupper(Auth::user()->full_name );
                                                }
                                              
                                        }   
                                     ?>" id="preverifiedby"  readonly >
                                    <div class="invalid-tooltip " style="width: 100%;">
                                            Valid verified by is required
                                    </div>
                                 
                              </div>   
                            </div>
                      </div>

                    

                                                        
                      <div class="row mb-3">
                            <div class="col-md-12">
                                    <label for="preverifieddate">Date : </label>
                                        
                                           <input type="text" class="form-control" name="preverifieddate"  value="<?php
                                                if(isset($sdc->pre_date_verified)){
                                                        echo $sdc->pre_date_verified;
                                                }else{
                                                      if(Auth::user()->role_id == 6){
                                                        date_default_timezone_set("Asia/Manila");
                                                        $currentDate =  date('m/d/Y');
                                                        $newDate = date("m/d/Y", strtotime($currentDate));    

                                                        echo $newDate;
                                                      }
                                                }
                                            ?>" id="preverifieddate"  readonly >
                                  
                                            <div class="input-group-addon invalid-tooltip ">
                                                    Valid date is required.
                                            </div>             
                                        
                                    </div>
                      </div>
 
                      <div class="row mb-3">
                        <div class="col-md-12">
                                <label for="tyremarks">Remarks :</label>
                                <div class="input-group">
                                <textarea type="text" class="form-control text-area" name="ty2remarks" cols="5" rows="5" id="ty2remarks" required @if (Auth::user()->role_id >= 7 OR $sdc->status == 3 OR $sdc->status == 2)
                                        disabled
                                @endif>{{ $sdc->ty2_remarks }}</textarea>
                                <div class="invalid-tooltip " style="width: 100%;">
                                                Remarks field is required
                                </div>
                                </div>   
                        </div>
                </div>

@endif

{{-- TREASURY FORM END  --}}

{{-- FORM VIEW FOR GOV. COMPLIANCE USERS --}}
@if (Auth::user()->role_id == 7 OR Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
<hr>
<div style="background-color:#2c3e50;color:white;" class="py-3 text-center mb-3">
        <h4>ACCUMULATORS</h4>
</div>
<hr>

<div class="row mb-4">
          
        <div class="col-md-6">
                <label for="transreset">Trans Reset Count :</label> 
                   
                <div class="input-group">
                <input type="number" class="form-control input-validate"   name="transreset" value="<?php if(isset($sdc->accumulators->trans_reset_count)){ echo $sdc->accumulators->trans_reset_count; }?>" 
                @if (Auth::user()->role_id != 7)
                         disabled 
                @endif id="transreset">
                  
                  <div class="invalid-tooltip " style="width: 100%;">
                      {{-- ERROR MESSAGE --}}
                </div>
                </div>   
        </div>
        <div class="col-md-6">
                <label for="transreset">OR Reset Count :</label> 
                   
                <div class="input-group">
                <input type="number" class="form-control input-validate" name="orreset" value="<?php if(isset( $sdc->accumulators->or_reset_count )){ echo $sdc->accumulators->or_reset_count; }?>" 
                @if (Auth::user()->role_id != 7)
                         disabled 
                @endif id="orreset">
                  
                  <div class="invalid-tooltip " style="width: 100%;">
                      {{-- ERROR MESSAGE --}}
                </div>
                </div>   
        </div>
</div>

<div class="row mb-3">
        <div class="col-md-6">
                <div style="color:darkslategray;padding:30px;" class="py-3">
                                <h5><b> -- ACCUM. NET SALES --</b></h5>
                </div>
        </div>
        <div class="col-md-6">
                <div style="color:darkslategray;padding:30px;" class="py-3">
                                <h5><b> -- ACCUM. NET RETURNS --</b></h5>
                </div>
        </div>
</div>

<div class="row mb-3">
           
        <div class="col-md-6">
                        <label for="vat_sales">Accum. VAT Sales :</label> 
                                
                        <div class="input-group">
                        <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalNetSales()"  name="vat_sales" value="<?php if(isset($sdc->accumulators->vat_sales)){echo $sdc->accumulators->vat_sales;}?>" 
                        @if (Auth::user()->role_id != 7 )
                                        disabled 
                        @endif id="vat_sales">
                                
                                <div class="invalid-tooltip " style="width: 100%;">
                                {{-- ERROR MESSAGE --}}
                        </div>
                        </div>   
        </div>

        <div class="col-md-6">
                        <label for="vat_returns">Accum. VAT Returns :</label> 
                                
                        <div class="input-group">
                        <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalNetReturns()"  name="vat_returns" value="<?php if(isset($sdc->accumulators->vat_ret)){echo $sdc->accumulators->vat_ret;}?>" 
                        @if (Auth::user()->role_id != 7 )
                                        disabled 
                        @endif id="vat_returns">
                                
                                <div class="invalid-tooltip " style="width: 100%;">
                                {{-- ERROR MESSAGE --}}
                        </div>
                        </div>   
        </div>
</div>


<div class="row mb-3">
           
                <div class="col-md-6">
                                <label for="non_vat_sales">Accum. Non VAT Sales :</label>  
                                
                                <div class="input-group">
                                <input type="text" class="form-control input-validate"  onkeypress="return isNumberKey(event,this)" onchange="getTotalNetSales()"  name="non_vat_sales" value="<?php if(isset($sdc->accumulators->non_vat_sales)){echo $sdc->accumulators->non_vat_sales;}?>"
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="non_vat_sales">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                <div class="col-md-6">
                                <label for="non_vat_returns">Accum. Non VAT Returns :</label> 
                                        
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)"  onchange="getTotalNetReturns()" name="non_vat_returns" value="<?php if(isset($sdc->accumulators->non_vat_ret)){echo $sdc->accumulators->non_vat_ret;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="non_vat_returns">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
</div>

<div class="row mb-3">
           
                <div class="col-md-6">
                                <label for="z_rated_sales">Accum. Zero Rated Sales :</label> 
                                        
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalNetSales()"  name="z_rated_sales" value="<?php if(isset($sdc->accumulators->z_rated_sales)){echo $sdc->accumulators->z_rated_sales;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="z_rated_sales">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                <div class="col-md-6">
                                <label for="z_rated_ret">Accum. Zero Rated Returns :</label> 
                                        
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalNetReturns()"  name="z_rated_ret" value="<?php if(isset($sdc->accumulators->z_rated_ret)){echo $sdc->accumulators->z_rated_ret;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="z_rated_ret">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
</div>

<div class="row mb-3">
           
                <div class="col-md-6">
                                <label for="vat_amount1">Accum. VAT Amount :</label> 
                                        
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalNetSales()"  name="vat_amount1" value="<?php if(isset($sdc->accumulators->vat_amount1)){echo $sdc->accumulators->vat_amount1;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="vat_amount1">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                <div class="col-md-6">
                                <label for="vat_amount2">Accum. VAT Amount :</label> 
                                
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalNetReturns()"  name="vat_amount2" value="<?php if(isset($sdc->accumulators->vat_amount2)){echo $sdc->accumulators->vat_amount2;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="vat_amount2">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
</div>


<div class="row mb-3">
           
                <div class="col-md-6">
                                <label for="vat_ex_amount1">Accum. VAT Exempt Amount :</label> 
                                        
                                <div class="input-group">
                                <input type="text" class="form-control input-validate"  onkeypress="return isNumberKey(event,this)" onchange="getTotalNetSales()"  name="vat_ex_amount1" value="<?php if(isset($sdc->accumulators->vat_exempt_amount1)){echo $sdc->accumulators->vat_exempt_amount1;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="vat_ex_amount1">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                <div class="col-md-6">
                                <label for="vat_ex_amount2">Accum. VAT Exempt Amount :</label> 
                                
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)"  onchange="getTotalNetReturns()"  name="vat_ex_amount2" value="<?php if(isset($sdc->accumulators->vat_exempt_amount2)){echo $sdc->accumulators->vat_exempt_amount2;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="vat_ex_amount2">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
</div>

<div class="row mb-3">
           
                <div class="col-md-6">
                                {{-- <label for="total_net_sales">TOTAL :</label>  --}}
                             <hr>           
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" id="total_net_sales"  name="total_net_sales" value="<?php if(isset($sdc->accumulators->total_net_sales)){echo $sdc->accumulators->total_net_sales;}else{echo "PHP 0.00";}?>" readonly>
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                <div class="col-md-6">
                                {{-- <label for="total_net_returns">TOTAL :</label>  --}}
                                <hr>          
                                <div class="input-group">
                                <input type="text" class="form-control input-validate"  id="total_net_returns" name="total_net_returns" value="<?php if(isset($sdc->accumulators->total_net_sales)){echo $sdc->accumulators->total_net_returns;}else{echo "PHP 0.00";}?>" readonly>
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
</div>
<div class="row mb-3">
                <div class="col-md-12">
                        <div style="color:darkslategray;padding:30px;" class="py-3">
                                        <h5><b> -- ACCUM. NET SALES AFTER RETURNS --</b></h5>
                        </div>
                </div>
               
</div>

<div class="row mb-3">
                <div class="col-md-6">
                                <label for="accum_vat">Accum. VAT :</label> 
                                
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalAfterReturns()"   name="accum_vat" value="<?php if(isset($sdc->accumulators->vat)){echo $sdc->accumulators->vat;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="accum_vat">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                <div class="col-md-6">
                                <label for="first_trx">First Transaction :</label> 
                                
                                <div class="input-group">
                                <input type="text" class="form-control input-validate"  onkeypress="return isNumberKey(event,this)"  name="first_trx" value="<?php if(isset($sdc->accumulators->first_trx)){echo $sdc->accumulators->first_trx;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="first_trx">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>  
               
</div>

<div class="row mb-3">
                <div class="col-md-6">
                                <label for="accum_non_vat">Accum. Non VAT :</label> 
                                
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalAfterReturns()"   name="accum_non_vat" value="<?php if(isset($sdc->accumulators->non_vat)){echo $sdc->accumulators->non_vat;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="accum_non_vat">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                <div class="col-md-6">
                                <label for="last_trx">Last Transaction :</label> 
                                
                                <div class="input-group">
                                <input type="text" class="form-control input-validate"  onkeypress="return isNumberKey(event,this)"  name="last_trx" value="<?php if(isset($sdc->accumulators->last_trx)){echo $sdc->accumulators->last_trx;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="last_trx">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>  
               
</div>

<div class="row mb-3">
                <div class="col-md-6">
                                <label for="accum_z_rated">Accum. Zero Rated :</label> 
                                
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalAfterReturns()"   name="accum_z_rated" value="<?php if(isset($sdc->accumulators->z_rated)){echo $sdc->accumulators->z_rated;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="accum_z_rated">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                <div class="col-md-6">
                                <label for="trx_count">Transaction Count :</label> 
                                 
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)"   name="trx_count" value="<?php if(isset($sdc->accumulators->trx_count)){echo $sdc->accumulators->trx_count;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="trx_count">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>  
               
</div>

<div class="row mb-3">
                <div class="col-md-6">
                                <label for="vat_amount3">Accum. VAT Amount :</label> 
                                
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalAfterReturns()"   name="vat_amount3" value="<?php if(isset($sdc->accumulators->vat_amount3)){echo $sdc->accumulators->vat_amount3;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="vat_amount3">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                <div class="col-md-6">
                                <label for="prev_reading">Previous Reading :</label> 
                                        
                                <div class="input-group">
                                <input type="text" class="form-control input-validate"  onkeypress="return isNumberKey(event,this)"  name="prev_reading" value="<?php if(isset($sdc->accumulators->prev_reading)){echo $sdc->accumulators->prev_reading;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="prev_reading">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>  
               
</div>

<div class="row mb-3">
                <div class="col-md-6">
                                <label for="vat_ex_amount3">Accum. VAT Exempt Amount :</label> 
                                        
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)" onchange="getTotalAfterReturns()"   name="vat_ex_amount3" value="<?php if(isset($sdc->accumulators->vat_exempt_3)){echo $sdc->accumulators->vat_exempt_3;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="vat_ex_amount3">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                <div class="col-md-6">
                                <label for="curr_reading">Current Reading :</label> 
                                        
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" onkeypress="return isNumberKey(event,this)"   name="curr_reading" value="<?php if(isset($sdc->accumulators->curr_reading)){echo $sdc->accumulators->curr_reading;}?>" 
                                @if (Auth::user()->role_id != 7 )
                                                disabled 
                                @endif id="curr_reading">
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>  
               
</div>

<div class="row mb-3">
           
                <div class="col-md-6">
                                {{-- <label for="total_net_sales">TOTAL :</label>  --}}
                             <hr>           
                                <div class="input-group">
                                <input type="text" class="form-control input-validate"   name="total_after_ret"  id ="total_after_ret" value="<?php if(isset($sdc->accumulators->total_after_returns)){echo $sdc->accumulators->total_after_returns;}else{echo "PHP 0.00";}?>" readonly>
                                        
                                        <div class="invalid-tooltip " style="width: 100%;">
                                        {{-- ERROR MESSAGE --}}
                                </div>
                                </div>   
                </div>
        
                
</div>
<hr>
<div style="background-color:#2c3e50;color:white;" class="py-3 text-center mb-3">
        <h4>PRE-CORRECTION VERIFICATION</h4>
</div>
<hr>
        <div class="row mb-3">
                <div class="col-md-12">
                  <label for="preaccumulatorverifiedby">Accumulator verified by :</label> 
                     
                  <div class="input-group">
                  <input type="text" class="form-control input-validate" name="preaccumulatorverifiedby" value="<?php 
                        if(isset($sdc->pre_acc_verified_by)){
                                echo strtoupper($sdc->pre_acc_verified_by);
                        }else{
                               if(Auth::user()->role_id == 7){
                                echo strtoupper(Auth::user()->full_name);
                               }
                        }    
                        ?>" id="preaccumulatorverifiedby" readonly >
                        <div class="invalid-tooltip " style="width: 100%;">
                                Valid accumulator verified by is required
                        </div>
                     
                  </div>   
                </div>
          </div>

          <div class="row mb-3">
                <div class="col-md-12">
                        <label for="predateaccumulator">Date : </label>
                           
                                <input type="text" id="predateaccumulator" name="predateaccumulator" value="<?php 
                                        if(isset($sdc->pre_acc_verified_date)){
                                                echo $sdc->pre_acc_verified_date;
                                        }else{
                                              if(Auth::user()->role_id == 7){
                                                date_default_timezone_set("Asia/Manila");
                                                        $currentDate =  date('m/d/Y');
                                                        $newDate = date("m/d/Y", strtotime($currentDate));    

                                                        echo $newDate;
                                              }
                                        }
                                ?>" class="form-control input-validate" readonly>
                                <div class="input-group-addon invalid-tooltip ">
                                        Valid date is required.
                                </div>             
                          
                </div>
          </div>
{{-- REMARKS HERE --}}
        <div class="row mb-3">
                <div class="col-md-12">
                        <label for="tyremarks">Remarks :</label>
                        <div class="input-group">
                        <textarea type="text" class="form-control text-area" name="govcompremarks" cols="5" rows="5" id="govcompremarks" required @if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                                        disabled
                                    @endif >{{ $sdc->govcomp_remarks }}</textarea>
                        <div class="invalid-tooltip " style="width: 100%;">
                                 Remarks field is required
                        </div>
                        </div>   
                </div>
        </div>
        
{{-- END --}}
    
@endif

{{-- END OF GOV. COMPLIANCE FORM --}}
{{-- APPROVER FORM --}}
@if (Auth::user()->role_id == 8 OR $sdc->status == 3 OR $sdc->status == 2)
                      <hr>
                      <div style="background-color:#2c3e50;color:white;" class="py-3 text-center mb-3">
                              <h4>APPROVAL OF THE CHANGE REQUEST</h4>
                      </div>

                      <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="acrapprovedby">Approved by :</label>
                                 
                              <div class="input-group">
                              <input type="text" class="form-control" name="acrapprovedby" value="<?php
                                       if(isset($sdc->app_approved_by)){
                                            echo $sdc->app_approved_by;    
                                       }else{
                                          if(Auth::user()->role_id == 8){
                                                echo strtoupper(Auth::user()->full_name);
                                          }
                                       }
                                ?>" id="acrapprovedby" readonly >
                                    <div class="invalid-tooltip " style="width: 100%;">
                                            Valid verified by is required
                                    </div>
                                 
                              </div>   
                            </div>

                            <div class="col-md-12">
                                    <label for="acrdate">Date : </label>
                                       
                                            <input type="text" id="acrdate" name="acrdate" value="<?php
                                                if(isset($sdc->app_date_approved)){
                                                        echo $sdc->app_date_approved;
                                                }else{
                                                     if(Auth::user()->role_id == 8){
                                                        date_default_timezone_set("Asia/Manila");
                                                        $currentDate =  date('m/d/Y');
                                                        $newDate = date("m/d/Y", strtotime($currentDate));    

                                                        echo $newDate;  
                                                     }
                                                }
                                            ?>" class="form-control" readonly >
                                            <div class="input-group-addon invalid-tooltip ">
                                                    Valid date is required.
                                            </div>             
                                        
                                    </div>
                      </div>

                      
                      <div class="row mb-3">
                        <div class="col-md-12">
                                <label for="app_remarks">Remarks :</label>
                                <div class="input-group">
                                <textarea type="text" class="form-control text-area" name="app_remarks" cols="5" rows="5" id="app_remarks" required @if (Auth::user()->role_id != 8)
                                        readonly
                                @endif>{{ $sdc->app_remarks }}</textarea>
                                <div class="invalid-tooltip " style="width: 100%;">
                                                Remarks field is required
                                </div>
                                </div>   
                        </div>
                </div>
@endif
{{-- END OF APPROVER FORM --}}

{{-- SUPPORTS VIEWABLE FORM AFTER APPROVER IS DONE --}}
@if(Auth::user()->role_id == 1 OR Auth::user()->role_id == 2 OR Auth::user()->role_id == 3 OR Auth::user()->role_id == 4)
@if ($sdc->status == 3)

                      <hr>
                      <div style="background-color:#2c3e50;color:white;" class="py-3 text-center mb-3">
                              <h4>CHANGE PROCESSING</h4>
                      </div>

                      <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                             <label for="cprassignedto">Request Assigned To :</label> 
                              
                              <div class="input-group">
                              <input type="text" class="form-control input-validate" name="cprassignedto" style="text-transform: uppercase" value="{{ $sdc->cp_request_assigned_to }}" id="cprassignedto" >
                                    <div class="invalid-tooltip " style="width: 100%;">
                                            Valid request assigned to is required
                                    </div>
                              </div>   
                            </div>

                            <div class="col-md-6 mb-3">
                                    <label for="cprassigneddate">Date Completed :</label>
                                <div class="input-group">
                                        <div class="input-group date" data-provide="datepicker">
                                        <input type="text" id="cprassigneddate" name="cprassigneddate" value="{{$sdc->cp_date_completed}}" class="form-control input-validate" >
                                                <div class="input-group-addon invalid-tooltip ">
                                                        Valid date is required.
                                                </div>             
                                        </div>
                                   
                                </div>   
                              </div>

                              
                      </div>

                      <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                  <label for="cprreviewedby">Request Reviewed By :</label>
                                
                              <div class="input-group">
                              <input type="text" class="form-control input-validate" name="cprreviewedby" style="text-transform: uppercase" value="{{ $sdc->cp_request_reviewed_by }}" id="cprreviewedby">
                                    <div class="invalid-tooltip " style="width: 100%;">
                                            Valid request reviewed by is required
                                    </div>
                                 
                              </div>   
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cpdatereviewed">Date Reviewed :</label>
                                <div class="input-group">
                                        <div class="input-group date" data-provide="datepicker">
                                                <input type="text" id="cpdatereviewed" name="cpdatereviewed" value="{{$sdc->cp_date_reviewed}}" class="form-control input-validate" >
                                                <div class="input-group-addon invalid-tooltip ">
                                                        Valid date is required.
                                                </div>             
                                        </div>
                                   
                                </div>   
                              </div>
                      </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                                <label for="tfaffected">Tables and Fields Affected :</label>
                            <div class="input-group">
                            <textarea type="text" class="form-control text-area" cols="5" rows="5" name="tfaffected" id="tfaffected">{{ $sdc->cp_table_fields_affected }}</textarea>
                                <div class="invalid-tooltip " style="width: 100%;">
                                      Tables and Fields Affected is required
                                </div>
                            </div>   
                        </div>
                    </div>

                    <hr>
                    <div style="background-color:#2c3e50;color:white;" class="py-3 text-center mb-3">
                            <h4>DEPLOYMENT CONFIRMATION</h4>
                    </div>

                    <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                              <label for="dcdeployedby">Deployed By :</label>
                              
                              <div class="input-group">
                              <input type="text" class="form-control input-validate" id="dcdeployedby" style="text-transform: uppercase" value="{{ $sdc->dc_deployed_by }}" name="dcdeployedby">
                                    <div class="invalid-tooltip " style="width: 100%;">
                                            Valid request reviewed by is required
                                    </div>
                                 
                              </div>   
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dcdeployeddate">Date :</label>
                                <div class="input-group">
                                        <div class="input-group date" data-provide="datepicker">
                                                <input type="text" id="dcdeployeddate" name="dcdeployeddate" value="{{$sdc->dc_date_deployed}}" class="form-control input-validate" >
                                                <div class="input-group-addon invalid-tooltip ">
                                                        Valid date is required.
                                                </div>             
                                        </div>
                                   
                                </div>   
                              </div>
                      </div>

                      <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                  <label for="dcrreviewedby">Reviewed By :</label>
                                  
                              <div class="input-group">
                              <input type="text" class="form-control input-validate" name="dcrreviewedby" style="text-transform: uppercase" value="{{ $sdc->dc_reviewed_by }}" id="dcrreviewedby">
                                    <div class="invalid-tooltip " style="width: 100%;">
                                            Valid request reviewed by is required
                                    </div>
                                 
                              </div>   
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dcrdate">Date :</label>
                                <div class="input-group">
                                        <div class="input-group date" data-provide="datepicker">
                                                <input type="text" id="dcrdate" name="dcrdate"  value="{{$sdc->dc_date_reviewed}}" class="form-control input-validate" >
                                                <div class="input-group-addon invalid-tooltip ">
                                                        Valid date is required.
                                                </div>             
                                        </div>
                                   
                                </div>   
                              </div>
                      </div>

                      <hr>
                      <div style="background-color:#2c3e50;color:white;" class="py-3 text-center mb-3">
                              <h4>POST CORRECTION VERIFICATION</h4>
                      </div>

                      <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="pcvverifiedby">Verified by :</label>
                                
                                <div class="input-group">
                                <input type="text" class="form-control input-validate" name="pcvverifiedby"  style="text-transform: uppercase" value="{{ $sdc->post_verified_by }}" id="pcvverifiedby"  >
                                      <div class="invalid-tooltip " style="width: 100%;">
                                              Valid verified by is required
                                      </div>
                                   
                                </div>    
                              </div>
  
                              <div class="col-md-12">
                                      <label for="pcvdate">Date : </label>
                                          <div class="input-group date" data-provide="datepicker">
                                                
                                              <input type="text" name="pcvdate" id="pcvdate"  value="{{$sdc->post_date_verified}}" class="form-control input-validate" >
                                             
                                              <div class="input-group-addon invalid-tooltip ">
                                                      Valid date is required.
                                              </div>             
                                          </div>
                            </div>
                      </div>
@endif                      
@endif
{{-- END OF FORM --}}

 
               
                <hr class="mb-4">
                @if (Auth::user()->role_id  != 1 OR Auth::user()->role_id  != 2 OR Auth::user()->role_id  != 3 OR Auth::user()->role_id  != 4 )  
                    
                 @if ($sdc->status == 3)
                        <button class="btn btn-danger btn-lg btn-block confirmation3" value="SUBMIT" name="action" type="submit">SUBMIT DATA CORRECTION (DONE)</button>
                        <button class="btn btn-danger btn-lg btn-block" value="SUBMIT" style="display:none;" id="confirmDone" name="action" type="submit">SUBMIT DATA CORRECTION (DONE)</button>
                 @elseif($sdc->status == 0 || $sdc->status == 2 )
                     
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
                                                
                                        <select class="custom-select d-block w-100" name="app_group" id="app_group" data-sdc="{{ $sdc->id }}" required>
                                                        <option value="">--- Choose ---</option>
                                                        @foreach ($appgroup as $group)
                                                                <option @if ($sdc->h_group == $group->group)
                                                                    selected
                                                                @endif value="{{ $group->group }}">{{ $group->group }} </option>
                                                        @endforeach
                                                        <option  @if ($sdc->h_group =="CUS")
                                                                        selected
                                                        @endif value="CUS">CUSTOM</option>
                                                
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
                @if ($sdc->status != 2)
                                <hr class="mb-4">
                                <div class="row mb-2">
                                      <div class="col-md-12">
                                              <input class="btn btn-primary btn-md btn-block" type="submit" name="action" value="SAVE"> 
                                      </div>
                                </div>
                                <div class="row ">
                                        <div class="col-md-12">
                                                <input class="btn btn-danger btn-md btn-block confirmation5" type="submit" name="action" value="CANCEL DATA CORRECTION"> 
                                                <input class="btn btn-success btn-md btn-block" style="display:none;" id="cancelSDC" type="submit" name="action" value="CANCEL DATA CORRECTION" > 
                                        </div>
                                  </div>
                @endif
                                <hr>
                                <div class="row  mb-5">
                                        <div class="col-md-12">
                                                <input class="btn btn-success btn-md btn-block confirmation2" type="submit" name="action" value="POST DATA CORRECTION" > 
                                                <input class="btn btn-success btn-md btn-block" style="display:none;" id="confirmPost" type="submit" name="action" value="POST DATA CORRECTION" > 
                                        </div>
                                </div>
                   
                 @else
                      @if (Auth::user()->role_id != 8)
                      <div class="row">
                                <div class="col-md-12">
                                        <input class="btn btn-success btn-md btn-block confirmation" type="submit" name="action" value="SUBMIT DATA CORRECTION" >
                                        <input class="btn btn-success btn-md btn-block" style="display:none;" id = "confirmSubmit" type="submit" name="action" value="SUBMIT DATA CORRECTION" >
                                </div>
                                       
                        </div>
                      @else
                      <div class="row">
                                <div class="col-md-12">
                                        <input class="btn btn-success btn-md btn-block confirmation1" type="submit" name="action" value="APPROVE DATA CORRECTION" > 
                                </div>
                                <div class="col-md-12">
                                        <input class="btn btn-success btn-md btn-block" type="submit" id="confirmApprove" style="display:none;" name="action" value="APPROVE DATA CORRECTION" > 
                                </div>
                                       
                        </div>
                      @endif
                       
                        
                       @if (Auth::user()->role_id != 4)
                       <hr>
                       <div class="row show-reject">
                                <div class="col-md-12">
                                        <span class="btn btn-danger btn-md btn-block" onclick="showRejectButton()">REJECT DATA CORRECTION</span>
                                </div>
                       </div>
                       <div class="row mt-3 submit-reject" style="display: none;">
                                <div class="col-md-12">
                                                <label for="rejectreason">Reason for Rejecting Data Correction :</label>
                                            <div class="input-group">
                                            <textarea type="text" class="form-control text-area" cols="5" rows="5" name="rejectreason" id="rejectreason"  ></textarea>
                                                <div class="invalid-tooltip " style="width: 100%;">
                                                      Reason field is required
                                                </div>
                                            </div>   
                                        </div>
                        </div>
                       <div class="row mt-3 submit-reject" style="display: none;">
                            
                            <div class="col-md-6">
                           <span class="btn btn-primary btn-md" onclick="closeRejection()">CANCEL</span> 
                           <span class="btn btn-danger btn-md confirmation4">SUBMIT REJECTION</span> 
                         
                            <span class="btn btn-danger btn-md" style="display:none;" id="confirmReject" onclick="submitRejection(this)" data-sdcno= "<?php 
                                        if(isset($sdc->id)){
                                                echo $sdc->id;
                                        }else{
                                                echo 1;
                                        }
                                ?>" data-fstatus= "<?php echo  $forward_status;?>"  data-userid= "{{ Auth::user()->id }}" id="subReject">SUBMIT REJECTION</span> 
                            </div>

                              
                      </div>  
                       @endif
                 @endif
                      
                       
                {{-- @else --}}
                        {{-- <button class="btn btn-primary btn-lg btn-block" value="SAVE" name="action" type="submit">Save Changes</button>
                        <button class="btn btn-danger btn-lg btn-block" value="POST" name="action" type="submit">Post Data Correction</button> --}}
                @endif 

                <input type="hidden" name="role_id" value="{{ Auth::user()->role_id }}">
 
             </form>
        
    </div>
    


@endsection