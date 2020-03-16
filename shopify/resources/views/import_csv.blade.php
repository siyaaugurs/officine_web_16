@extends('layouts.default')

@section('content')
    <div style="width: 60%; margin: 10px auto;">@include('messages')</div>
    
    <div class="loader" style="width: 60%; margin: 10px auto; display: none;">
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    
    <div class="card" style="width: 60%; margin: 0 auto;">        
        <div class="card-body">
            <h5 class="card-title text-center">Upload a CSV file to start importing</h5>
            <br/>
            <form id="csv_import" method="post" enctype="multipart/form-data" action="{{ route('import_csv') }}">
                @csrf
                <div class="form-group">
                    <input type="file" class="form-control-file" id="csv_file" name="csv_file" required="required" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                </div>
                <div class="form-group form-check d-none">
                    <input type="checkbox" class="form-check-input" name="header" id="header" checked="checked">
                    <label class="form-check-label" for="header">CSV contains header row?</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('home') }}" class="btn btn-secondary float-right">Back</a>
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
            title: 'Import Products by CSV'
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);
        
        
        $('form#csv_import').on('submit', function() {
            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            $('.loader').show();
        });
    </script>
@endsection