@extends('layouts.master_layouts')
@section('content')
<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title">Hour list </h6>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
                <a class="list-icons-item" data-action="remove"></a>
            </div>
        </div>
    </div>
    <div class="card-body" id="days_hour_section">
        <ul class="media-list media-chat-scrollable mb-3">
            <li class="media text-muted">
                <button type="button" id="hours_btn" class="btn btn-success popup_btn" data-modalname="hours_popup">
                <i class="icon-plus3"></i>&nbsp;Add Hour list</button>
            </li>
            @forelse($get_workshop_weekly_days as $days_list)
            <li class="media">
                <div class="mr-3">
                    {{ $loop->iteration ."." }}
                </div>
                <div class="media-body">
                    <div class="media-title d-flex flex-nowrap">
                        <a href="#" class="font-weight-semibold mr-3">{{ $days_list->name  }}</a>
                        <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                            <a data-daysid="{{ $days_list->id }}" href="#" class="ml-3 icn-sm red-bdr delete_daystiming">
                                <i class="icon-x icon-2x"></i>
                            </a>
                        </span>
                    </div>
                    @if(!empty($days_list->is_whole_opening))
                        24 Hour opening 
                    @else
                    @php
                        $timing_data = sHelper::get_workshop_timing($days_list->id);  @endphp   
                        @if(!empty($timing_data->start_time))
                        {{ $timing_data->start_time." - ".$timing_data->end_time }}
                        @endif
                        <br />
                        @if(!empty($timing_data->start_time_2))
                            {{ $timing_data->start_time_2." - ".$timing_data->end_time_2 }}
                        @endif
                    @endif
                </div>
            </li>
            @empty
            <li class="media">
                <div class="media-body">
                    <div class="media-title d-flex flex-nowrap">
                        <a class="font-weight-semibold mr-3">No Hour list available . </a>
                    </div>
                </div>
            </li>
            @endforelse   
        </ul>
    </div>
</div>
<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title">Add or Remove Special Holydays and Leave  </h6>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
                <a class="list-icons-item" data-action="remove"></a>
            </div>
        </div>
    </div>
    <div class="card-body" id="special_days_hour_section">
        <ul class="media-list media-chat-scrollable mb-3">
            <li class="media text-muted">
                <button type="button" class="btn btn-success popup_btn" data-modalname="special_days_pop">
                <i class="icon-plus3"></i>&nbsp;@lang('messages.AddOrRemoveSpecialDays')</button>
            </li>
             <div class="card">
                <table class="table datatable-show-all">
                    <thead>
                        <tr>
                            <th>@lang('messages.SN')</th>
                            <th>@lang('messages.offDate')</th>
                            <th>@lang('messages.Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($get_workshop_weekly_days_details as $days_list)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $days_list->off_date }}</td>
                                <td><a href = '{{ url("") }}' class="btn btn-danger delete_pakages">Delete</a></td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="3">@lang('messages.SpecialDaysAreNotAvailable')</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>   
        </ul>
    </div>
</div>
<!--Add special days-->
<div class="modal" id="special_days_pop">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                 <h6 class="card-title">Add or Remove Holidays </h6>
                <hr />
            </div>
            <!-- Modal body -->
                <div class="modal-body">
                        <form id="special_days" style="height:250px;" autocomplete="off">
                            <div class="row form-group">
                                <div class="col-sm-10 col-md-11 col-lg-11">
                                    <div class="row">
          <div class="col-md-12">
             <label>Select date</label>
             <input type="text" class="form-control datepicker off_date" id="datetimepicker3" name="date">
          </div>
      </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="response_details">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">  
                                        <button type="button" class="btn btn-success"  id="add_off_date">Save &nbsp;<i class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <div id="response_timing"></div> 
                </div>
        </div>
    </div>
</div>
<!--End-->
<!--Add Hour modal popup-->
<div class="modal" id="hours_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add hours </h4>
                <hr />
            </div>
            <!-- Modal body -->
                <div class="modal-body">
                    @if($all_weekly_day != FALSE)
                        <form id="weekly_schedule_form">
                            @csrf
                            @foreach($all_weekly_day as $days)
                            <div class="row form-group chk_hours_listing">
                                <div class="col-sm-10 col-md-11 col-lg-11">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-control-styled weekly_days" id="d{{$days->id}}" name="week_days[]" value="{{$days->id}}"  data-fouc>
                                                {{ $days->name }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-1 col-lg-1">
                                    <p>Closed</p>
                                </div>
                                <div class="col-sm-12 gap-margin main_div" id="hour_sectiond{{$days->id}}" style="display:none;">
                                    <div class="timing_row" id="timingrow{{$days->id}}"> 
                                        <div class="row more_row" id="add_more_timing_section" style="margin-top:15px;">
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control start_time{{$days->id}}" placeholder="Select Start timing" name="first_timing[]" id='start_time'  />
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control end_time{{$days->id}}" placeholder="Select End timing" name="second_timing[]" id="end_time"  />
                                            </div>
                                        </div>
                                        <div class="row add_more_timing_section_copy more_row"  style="margin-top:15px; display:none;">
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control first_timing_1{{$days->id}}" placeholder="Select Start timing" name="first_timing_1[]" id='start_time'  />
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control second_timing_1{{$days->id}}" placeholder="Select End timing" name="second_timing_1[]" id="end_time"  />
                                            </div>
                                            <div class="col-sm-2">
                                                <a href="#" data-rowid="{{$days->id}}" class="ml-3 btn btn-danger icn-sm red-bdr remove_more_timing">
                                                    <i class="icon-x icon-2x"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <a style="margin-top:15px;" href="#" class="add_more_time_btn" id="add_more_btn{{$days->id}}" data-days="{{ $days->id }}">Add more hours</a>
                                    <div class="d-flex justify-content-between align-items-center pull-right">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-control-styled" id="repeat_all" name="repeat_all" value="{{$days->id}}"  data-fouc>
                                        Repeat all
                                    </label>
                                </div>
                              </div>
                                    <div class="row" style="margin-left:15px;">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-control-styled day_24_checkbox whole_days{{$days->id}}" name="day_24[]" value="1_{{$days->id}}" id="day_24_{{$days->id}}" data-crowid="{{$days->id}}"  data-fouc>
                                                        Open 24 hours
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">  
                                        <button type="submit" class="btn btn-success"  id="add_workshop_timing">Save &nbsp;<i class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <div id="response_timing"></div> 
                    @else
                        <h1>day is not available .</h1>
                    @endif
                </div>
        </div>
    </div>
</div>
<!--End--->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home </a>
            <span class="breadcrumb-item active"> Add hours </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
    <script src="{{ url('validateJS/admin.js') }}"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.it.min.js"></script>
    <script src="{{ url('validateJS/vendors.js') }}"></script>
<script>
$(document).ready(function(e) {
    $('.datepicker').datepicker({
      language: 'it',
	  format:'dd-mm-yyyy'
    });
 });
</script>
@endpush