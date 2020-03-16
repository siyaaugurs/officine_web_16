<table class="table table-bordered">
    @foreach($car_data as $cars)
        <tr>
        <td>{{ $cars->cars_name }}</td>
        <td><a href="#" class="btn btn-danger delete_selected_cars" data-carid="{{ $cars->id }}"><i class="fa fa-trash"></i></a></td> 
        </tr>
    @endforeach 
</table>