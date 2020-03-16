@extends('layouts.default')

@section('content')
    <div style="width: 60%; margin: 10px auto;">@include('messages')</div>
    
    <div class="card" style="width: 60%; margin: 0 auto;">        
        <div class="card-body">
            <h5 class="card-title text-center">Settings</h5>
            <br/>
            <form method="post" enctype="multipart/form-data" action="{{ route('settings_update') }}">
                @csrf
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row">Price Add-on Percentage:</th>
                            <td>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="price_addon_percentage" value="{{$setting->price_addon_percentage ?? ''}}" required="required">
                                    <div class="input-group-append">
                                        <div class="input-group-text">%</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary float-right">Submit</button>
                <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>
            </form>            
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        var AppBridge = window['app-bridge'];
        var actions = AppBridge.actions;
        var TitleBar = actions.TitleBar;
        var Button = actions.Button;
        var Redirect = actions.Redirect;
        var titleBarOptions = {
            title: 'Settings'
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);
        
        
        $('form#csv_import').on('submit', function() {
            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            $('.loader').show();
        });
    </script>
@endsection