@extends('layouts.dashboardLayout')
@section('title','Search Results')
@section('submenu')
<ul class="submenu__ul"> 
        <li class="submenu__li">
         
        
    </ul>
@endsection

@section('content')
    <main>
       <div class="row">
            <div class="col-1-of-4">
                    <aside class="side">
                            <div class="side__title">
                                <h3 class="heading-tertiary">Data Corrections</h3>
                                 
                            </div>
                            <div class="side__content">
                                <dl class="side__dl" >
                                    <dt class="side__dt">All types</span></dt>
                                        <dd class="side__dd">System Data Corrections </dd>
                                    </dl>
                            </div>
                        </aside>
            </div>
            <div class="col-3-of-4">
                    <div class="side">
                            <h3 class="side__title "> Found {{ count($sdc) }} search result(s) :</h3>
                            <div class="side__content">
                                @if ($user_role == 5)
                                    @if(!count($sdc) == 0)
                                        @foreach ($sdc as $item)
                                            @if ($item->forward_status >= 2)
                                                <a class="search__item" href="{{route('sdc.printer',['id' => $item->id])}}" target="_blank">
                                                        <div class="search__label">SDC #{{$item->id}} ({{$item->ticket->issue->subject}})</div>
                                                        <span class="search__details">
                                                            {{$item->ticket->issue->details}}
                                                        </span><br>
                                                    <span class="table__info">DONE</span>
                                                </a>
                                               
                                            @else
                                                <a class="search__item" href="{{route('sdc.edit',['sdc' => $item->id])}}">
                                                        <div class="search__label">SDC #{{$item->id}} ({{$item->ticket->issue->subject}})</div>
                                                        <span class="search__details">
                                                            {{$item->ticket->issue->details}}
                                                        </span><br>
                                                        <span class="table__info">PENDING</span>
                                                </a>
                                            @endif
                                           
                                        @endforeach
                                    @else
                                        <h1 class="search__error">No Search Result(s) Found</h1>
                                    @endif
                               
                                @endif
                               
                                {{-- TREASURY 2 SEARCH RESULTS --}}
                                @if ($user_role == 6)
                                @if(!count($sdc) == 0)
                                    @foreach ($sdc as $item)
                                        @if ($item->forward_status >= 3)
                                            <a class="search__item" href="{{route('sdc.printer',['id' => $item->id])}}" target="_blank">
                                                    <div class="search__label">SDC #{{$item->id}} ({{$item->ticket->issue->subject}})</div>
                                                    <span class="search__details">
                                                        {{$item->ticket->issue->details}}
                                                    </span><br>
                                                <span class="table__info">DONE</span>
                                            </a>
                                           
                                        @else
                                            <a class="search__item" href="{{route('sdc.edit',['sdc' => $item->id])}}" >
                                                    <div class="search__label">SDC #{{$item->id}} ({{$item->ticket->issue->subject}})</div>
                                                    <span class="search__details">
                                                        {{$item->ticket->issue->details}}
                                                    </span><br>
                                                    <span class="table__info">PENDING</span>
                                            </a>
                                        @endif
                                        <hr>
                                    @endforeach
                                @else
                                    <h1 class="search__error">No Search Result(s) Found</h1>
                                @endif
                           
                            @endif
                            
                             {{-- GOV COMP SEARCH RESULTS --}}
                             @if ($user_role == 7)
                             @if(!count($sdc) == 0)
                                 @foreach ($sdc as $item)
                                     @if ($item->forward_status >= 4)
                                         <a class="search__item" href="{{route('sdc.printer',['id' => $item->id])}}" target="_blank">
                                                 <div class="search__label">SDC #{{$item->id}} ({{$item->ticket->issue->subject}})</div>
                                                 <span class="search__details">
                                                     {{$item->ticket->issue->details}}
                                                 </span><br>
                                             <span class="table__info">DONE</span>
                                         </a>
                                        
                                     @else
                                         <a class="search__item" href="{{route('sdc.edit',['sdc' => $item->id])}}">
                                                 <div class="search__label">SDC #{{$item->id}} ({{$item->ticket->issue->subject}})</div>
                                                 <span class="search__details">
                                                     {{$item->ticket->issue->details}}
                                                 </span><br>
                                                 <span class="table__info">PENDING</span>
                                         </a>
                                     @endif
                                     <hr>
                                 @endforeach
                             @else
                                 <h1 class="search__error">No Search Result(s) Found</h1>
                             @endif
                        
                         @endif


                           {{-- APPROVER SEARCH RESULTS --}}
                           @if ($user_role == 8)
                           @if(!count($sdc) == 0)
                               @foreach ($sdc as $item)
                                   @if ($item->forward_status >= 5)
                                       <a class="search__item" href="{{route('sdc.printer',['id' => $item->id])}}" target="_blank">
                                               <div class="search__label">SDC #{{$item->id}} ({{$item->ticket->issue->subject}})</div>
                                               <span class="search__details">
                                                   {{$item->ticket->issue->details}}
                                               </span><br>
                                            
                                           <span class="table__info">DONE</span>
                                       </a>
                                      
                                   @else
                                       <a class="search__item" href="{{route('sdc.edit',['sdc' => $item->id])}}" >
                                               <div class="search__label">SDC #{{$item->id}} ({{$item->ticket->issue->subject}})</div>
                                               <span class="search__details">
                                                   {{$item->ticket->issue->details}}
                                               </span><br>
                                               <span class="table__info">PENDING</span>
                                       </a>
                                   @endif
                                   <hr>
                               @endforeach
                           @else
                               <h1 class="search__error">No Search Result(s) Found</h1>
                           @endif
                      
                       @endif

                            </div>
                        </div>
            </div>
       </div>
    </main>
@endsection
