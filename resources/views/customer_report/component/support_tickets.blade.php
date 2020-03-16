 <table class="table datatable-basic table-hover" id="DataTables_Table_2">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>TicketID</th>
                    <th>Client</th>
                    <th>@lang('messages.Email')</th>
                    <th>@lang('messages.Mobile')</th>
                    <th>Status</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($tickets as $ticket)
                @php $enctype = encrypt($ticket->id) @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ "Ticket-".$ticket->id }}</td>
                    <td>{{  !empty($ticket->f_name) ? $ticket->f_name." ".$ticket->l_name : "N/A" }}</td>
                    <td>{{ $ticket->email }}</td>
                    <td>{{ $ticket->mobile_number }}</td>
                    <th>{!! sHelper::support_ticket_status($ticket->status) !!}</th>
                    <td>{{ sHelper::date_format_for_database($ticket->created_at , 1) }}</td>
                    <td>@if($ticket->status == 'A') <a href='{{ url("admin/customer_report/remove_ticket/$ticket->id") }}' class="btn btn-danger">
                        <i class="fa fa-remove"></i></a> @endif 
                      <a href='{{ url("admin/customer_report/messages/$enctype") }}' class="btn btn-primary">
                        <i class="glyphicon glyphicon-eye-open"></i></a>
                    </td>
                </tr>
            @empty
                <tr>
                <td colspan="5">No ticket available !!!</td>
                </tr>
            @endforelse
            </tbody>
        </table> 
        <!--<table class="table datatable-ajax">
						<thead>
							<tr>
				                <th>Name</th>
				                <th>Position</th>
				                <th>Location</th>
				                <th>Extn.</th>
				                <th>Start date</th>
				                <th>Salary</th>
				            </tr>
						</thead>
					</table>        -->