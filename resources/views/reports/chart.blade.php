@include('reports.chartheader')
    
     
              <div class="container" style="margin-top:100px;margin-bottom:10px;">
                <div class="row">
                    <div class="col-md-3">
                        <div class="panel panel-primary panel-colorful">
                            <div class="pad-all text-center">
                            <span class="text-3x text-thin">{{ $downCounts }}/{{  $pendingDays  }}</span>
                                <p>NETWORK DOWN</p>
                                <i class="demo-pli-shopping-bag icon-lg"></i>
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-md-3">
                        <div class="panel panel-dark panel-colorful">
                            <div class="pad-all text-center">
                            <span class="text-3x text-thin">{{ $ssCountRes }}/{{ $ssCountLog }}</span>
                                <p>STORE SUPPORT</p>
                                <i class="demo-pli-shopping-bag icon-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="panel panel-primary panel-colorful">
                            <div class="pad-all text-center">
                            <span class="text-3x text-thin">{{ $dcsCountRes }}/{{ $dcsCountLog }}</span>
                                <p>DC SUPPORT</p>
                                <i class="demo-pli-shopping-bag icon-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="panel panel-dark panel-colorful">
                            <div class="pad-all text-center">
                            <span class="text-3x text-thin">{{ $sscCountRes }}/{{$sscCountLog }}</span>
                                <p>SSC SUPPORT</p>
                                <i class="demo-pli-shopping-bag icon-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                   

                    <div class="col-md-4">
                        <div class="panel panel-primary panel-colorful">
                            <div class="pad-all text-center">
                            <span class="text-3x text-thin">{{ $devDoneCount }}/{{$devProjects }}</span>
                                <p>DEV Projects</p>
                                <i class="demo-pli-shopping-bag icon-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="panel panel-dark panel-colorful">
                            <div class="pad-all text-center">
                            <span class="text-3x text-thin">{{ $visitDoneCount }}/{{  $visitCount  }}</span>
                                <p>Technical Visits</p>
                                <i class="demo-pli-shopping-bag icon-lg"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="panel panel-primary panel-colorful">
                            <div class="pad-all text-center">
                            <span class="text-3x text-thin">{{ $issueDoneCount }}/{{ $issueCount }}</span>
                                <p>Master Data Services</p>
                                <i class="demo-pli-shopping-bag icon-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
               
                
             
              
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-dark panel-colorful">
                            <div class="pad-all text-center">
                            <span class="text-3x text-thin">{{ $totalSRCount }}/{{ $totalSCount }}</span>
                                <p>Support</p>
                                <i class="demo-pli-shopping-bag icon-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="panel panel-primary panel-colorful">
                            <div class="pad-all text-center">
                            <span class="text-3x text-thin">{{ $totalTRCount }}/{{ $totalTCount }}</span>
                                <p>Technical</p>
                                <i class="demo-pli-shopping-bag icon-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                            <div class="panel panel-dark panel-colorful">
                                    <div class="pad-all text-center">
                                    <span class="text-3x text-thin">{{ $totalMDataResCount }}/{{ $totalMDataCount }}</span>
                                        <p>Master Data</p>
                                        <i class="demo-pli-shopping-bag icon-lg"></i>
                                    </div>
                                </div>
                        
                    </div>
                </div>
                <hr>
                  {{-- MONTHLY LOGGED VS. RESOLVED --}}
                    <div class="logggedvsresolved" >
                            <h2>Logged vs. Resolved (<span class="lvryear1"></span>) </h2>
                            <hr>
                            <div class="row">
                                  <div class="col-md-6">
                                        <div class="form-group">
                                                <label class="control-label">Month : </label>
                                                <?php 
                                                     date_default_timezone_set("Asia/Manila");
                                                        $currentDate =  date('m/d/Y');
                                                        $month =  date("m", strtotime($currentDate));
                                                        $year =   date("Y", strtotime($currentDate));  
                                                       
                                                ?>
                                                <select data-placeholder="Choose Month" id="ivrmonth" name="ivrmonth" class="filter demo-chosen-select" tabindex="2">
                                                    <option value="1" <?php if($month==1){ echo "selected";}?>>JANUARY</option>
                                                    <option value="2" <?php if($month==2){ echo "selected";}?>>FEBRUARY</option>
                                                    <option value="3" <?php if($month==3){ echo "selected";}?>>MARCH</option>
                                                    <option value="4" <?php if($month==4){ echo "selected";}?>>APRIL</option>
                                                    <option value="5" <?php if($month==5){ echo "selected";}?>>MAY</option>
                                                    <option value="6" <?php if($month==6){ echo "selected";}?>>JUNE</option>
                                                    <option value="7" <?php if($month==7){ echo "selected";}?>>JULY</option>
                                                    <option value="8" <?php if($month==8){ echo "selected";}?>>AUGUST</option>
                                                    <option value="9" <?php if($month==9){ echo "selected";}?>>SEPTEMBER</option>
                                                    <option value="10" <?php if($month==10){ echo "selected";}?>>OCTOBER</option>
                                                    <option value="11" <?php if($month==11){ echo "selected";}?>>NOVEMBER</option>
                                                    <option value="12" <?php if($month==12){ echo "selected";}?>>DECEMBER</option>
                                                </select>
                                        </div>
                                  </div>

                                  <div class="col-md-6">
                                        <div class="form-group">
                                                <label class="control-label">Year : </label>
                                                <select data-placeholder="Choose Year" id="ivryear" name="ivryear" class="filter demo-chosen-select" tabindex="2">
                                                    <option value="2018" <?php if($year=="2018"){ echo "selected";}?>>2018</option>
                                                    <option value="2019" <?php if($year=="2019"){ echo "selected";}?>>2019</option>
                                                </select>
                                        </div>
                                  </div>
                            </div>
                              <div class="row">
                                  <div class="col-md-12">
                                          <div id="logvsresolved" style="height:250px"></div>
                                          <hr class="new-section-xs bord-no">
                                          <ul class="list-inline text-center">
                                              <li><span class="label label-info" id="ivrlogged"></span> Logged</li>
                                              <li><span class="label label-danger" id="ivrresolved"></span> Resolved</li>
                                          </ul>
                                  </div>
                                  
                              </div>
                    </div>
                    {{-- END OF MONTHLY LOGGED VS. RESOLVED --}} 
                    <hr>

                    {{-- MONTHLY CATEGORY VS. RESOLVED --}}
                    <div class="catresolved" style="margin-top:100px;">
                            <h2>Issues per Category vs. Resolved (<span id="selectedcryear"></span>) </h2>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Category : </label>
                                                <select data-placeholder="Choose Category" id="crcategory" name="crcategory" class="filter demo-chosen-select" tabindex="2">
                                                 <option value="all">All</option>
                                                  @foreach ($categories as $category)
                                                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                  @endforeach
                                                </select>
                                        </div>
                                </div>
                                 <div class="col-md-4">
                                        <label class="control-label">Month : </label>
                                        <?php 
                                             date_default_timezone_set("Asia/Manila");
                                                $currentDate =  date('m/d/Y');
                                                $month =  date("m", strtotime($currentDate));  
                                                $year =   date("Y", strtotime($currentDate)); 
                                        ?>
                                        <select data-placeholder="Choose Month" id="crmonth" name="crmonth" class="filter demo-chosen-select" tabindex="2">
                                            <option value="1" <?php if($month==1){ echo "selected";}?>>JANUARY</option>
                                            <option value="2" <?php if($month==2){ echo "selected";}?>>FEBRUARY</option>
                                            <option value="3" <?php if($month==3){ echo "selected";}?>>MARCH</option>
                                            <option value="4" <?php if($month==4){ echo "selected";}?>>APRIL</option>
                                            <option value="5" <?php if($month==5){ echo "selected";}?>>MAY</option>
                                            <option value="6" <?php if($month==6){ echo "selected";}?>>JUNE</option>
                                            <option value="7" <?php if($month==7){ echo "selected";}?>>JULY</option>
                                            <option value="8" <?php if($month==8){ echo "selected";}?>>AUGUST</option>
                                            <option value="9" <?php if($month==9){ echo "selected";}?>>SEPTEMBER</option>
                                            <option value="10" <?php if($month==10){ echo "selected";}?>>OCTOBER</option>
                                            <option value="11" <?php if($month==11){ echo "selected";}?>>NOVEMBER</option>
                                            <option value="12" <?php if($month==12){ echo "selected";}?>>DECEMBER</option>
                                        </select>
                                 </div>

                                 <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Year : </label>
                                                <select data-placeholder="Choose Year" id="cryear" name="cryear" class="filter demo-chosen-select" tabindex="2">
                                                    <option value="2018" <?php if($year=="2018"){ echo "selected";}?>>2018</option>
                                                    <option value="2019" <?php if($year=="2019"){ echo "selected";}?>>2019</option>
                                                </select>
                                        </div>
                                 </div>
                            </div>
                              <div class="row">
                                  <div class="col-md-12">
                                          <div id="catlogsres" style="height:250px"></div>
                                          <hr class="new-section-xs bord-no">
                                          <ul class="list-inline text-center">
                                              <li><span class="label label-info" id="crlogged"></span> Logged</li>
                                              <li><span class="label label-danger" id="crresolved"></span> Resolved</li>
                                          </ul>
                                  </div>
                                  
                              </div>
                    </div>
                    {{-- END OF MONTHLY CATEGORY VS. RESOLVED --}}
                    <hr>
                    {{-- MONTHLY TOP 10 RESOLVERS --}}
                    <div class="catresolved" style="margin-top:100px;margin-bottom:100px;">
                            <h2>Top 10 Resolvers (<span id="selectedtryear"></span>) </h2>
                            <hr>
                            <div class="row">
                                 <div class="col-md-6">
                                        <label class="control-label">Month : </label>
                                        <?php 
                                             date_default_timezone_set("Asia/Manila");
                                                $currentDate =  date('m/d/Y');
                                                $month =  date("m", strtotime($currentDate));  
                                                $year =   date("Y", strtotime($currentDate)); 
                                        ?>
                                        <select data-placeholder="Choose Month" id="trmonth" name="trmonth" class="filter demo-chosen-select" tabindex="2">
                                            <option value="1" <?php if($month==1){ echo "selected";}?>>JANUARY</option>
                                            <option value="2" <?php if($month==2){ echo "selected";}?>>FEBRUARY</option>
                                            <option value="3" <?php if($month==3){ echo "selected";}?>>MARCH</option>
                                            <option value="4" <?php if($month==4){ echo "selected";}?>>APRIL</option>
                                            <option value="5" <?php if($month==5){ echo "selected";}?>>MAY</option>
                                            <option value="6" <?php if($month==6){ echo "selected";}?>>JUNE</option>
                                            <option value="7" <?php if($month==7){ echo "selected";}?>>JULY</option>
                                            <option value="8" <?php if($month==8){ echo "selected";}?>>AUGUST</option>
                                            <option value="9" <?php if($month==9){ echo "selected";}?>>SEPTEMBER</option>
                                            <option value="10" <?php if($month==10){ echo "selected";}?>>OCTOBER</option>
                                            <option value="11" <?php if($month==11){ echo "selected";}?>>NOVEMBER</option>
                                            <option value="12" <?php if($month==12){ echo "selected";}?>>DECEMBER</option>
                                        </select>
                                 </div>

                                 <div class="col-md-6">
                                        <div class="form-group">
                                                <label class="control-label">Year : </label>
                                                <select data-placeholder="Choose Year" id="tryear" name="tryear" class="filter demo-chosen-select" tabindex="2">
                                                        <option value="2018" <?php if($year=="2018"){ echo "selected";}?>>2018</option>
                                                        <option value="2019" <?php if($year=="2019"){ echo "selected";}?>>2019</option>
                                                </select>
                                        </div>
                                 </div>
                            </div>
                              <div class="row">
                                  <div class="col-md-12">
                                          <div id="topresolvers" style="height:250px"></div>
                                          <hr class="new-section-xs bord-no">
                                          <ul class="list-inline text-center">
                                              {{-- <li><span class="label label-info" id="trlogged"></span> Logged</li>
                                              <li><span class="label label-danger" id="trresolved"></span> Resolved</li> --}}
                                          </ul>
                                  </div>
                                  
                              </div>
                    </div>
                    
              </div>
      
@include('reports.chartfooter')