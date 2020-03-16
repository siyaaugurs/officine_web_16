@extends('layouts.default')

@section('content')
    <div class="card" style="width: 50%; margin: 0 auto;">        
        <div class="card-body">
            <h5 id="action" class="card-title text-center">Importing in Progress</h5>
            <p id="result" class="card-text text-center">Importing may take some time. Please do not close your browser or refresh the page until the process is complete.</p>
            <!--<h6 id="filename" class="card-title text-center text-primary">PURE Example Sheet.csv</h6>-->
            <!--<br/>-->
            <div id="loader" class="d-flex justify-content-center">
                <div class="spinner-border text-success" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            
            <div id="import_progress">
                <span id="left_progress">Time Elapsed: <span id="time-elapsed">00:00:00</span></span>
            </div>
            
            <a id="back_btn" href="{{ route('home') }}" class="btn btn-secondary float-right">Back</a>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        $('a#back_btn').hide();
        
        var AppBridge = window['app-bridge'];
        var actions = AppBridge.actions;
        var TitleBar = actions.TitleBar;
        var Button = actions.Button;
        var Redirect = actions.Redirect;
        var titleBarOptions = {
            title: 'Import Products from FTP'
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);
        
        
        // record start time
        var startTime = new Date();

        function display() {
            // later record end time
            var endTime = new Date();

            // time difference in ms
            var timeDiff = endTime - startTime;

            // strip the miliseconds
            timeDiff /= 1000;

            // get seconds
            var seconds = Math.round(timeDiff % 60);

            // remove seconds from the date
            timeDiff = Math.floor(timeDiff / 60);

            // get minutes
            var minutes = Math.round(timeDiff % 60);

            // remove minutes from the date
            timeDiff = Math.floor(timeDiff / 60);

            // get hours
            var hours = Math.round(timeDiff % 24);

            // remove hours from the date
            //timeDiff = Math.floor(timeDiff / 24);

            // the rest of timeDiff is number of days
            //var days = timeDiff;

            $("#time-elapsed").text((hours <= 9 ? '0':'') + hours + ":" + (minutes <= 9 ? '0':'') + minutes + ":" + (seconds <= 9 ? '0':'') + seconds);
        }

        var timer = setInterval(display, 1000);
        
        $(document).ready(function(){
            $.get("{{ route('ajax_ftp_import') }}", function(response){
                if(response.success) {
                    document.getElementById("action").innerHTML = "Import Completed!";
                } else {
                    document.getElementById("action").innerHTML = "Import Stopped!";
                }
                clearInterval(timer);
                document.getElementById("loader").remove();
//                document.getElementById("filename").remove();
                document.getElementById("result").innerHTML = response.message;
                $('a#back_btn').show();
            });
        });        
    </script>
@endsection