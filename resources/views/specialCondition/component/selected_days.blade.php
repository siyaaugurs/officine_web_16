<table class="table table-bordered">
    @foreach($day_data as $days)
        <tr>
        <td>{{ $days->name }}</td>
        <td><a href="#" class="btn btn-danger delete_selected_days" data-dayid="{{ $days->id }}"><i class="fa fa-trash"></i></a></td> 
        </tr>
    @endforeach 
</table>