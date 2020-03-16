<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('shopify-app.app_name') }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://unpkg.com/tabulator-tables@4.5.3/dist/css/tabulator.min.css" rel="stylesheet">
</head>

<body>
    <div class="app-wrapper container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background-color: #e3f2fd; margin-bottom: 20px;">
            <a class="navbar-brand" href="{{ route('home') }}">Home</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link active float-right" href="{{ route('settings') }}">Settings</a>
                </div>
            </div>
        </nav>

        <div class="app-content">
            <main role="main">
                <div class="card">
                    <div class="card-body">
                        <div id="example-table"></div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.5.3/dist/js/tabulator.min.js"></script>
    <script>
        var table = new Tabulator("#example-table", {
            height: "600px",
            layout: "fitColumns", //fit columns to width of table
            tooltips: true, //show tool tips on cells
            pagination: "local", //paginate the data
            paginationSize: 500, //allow 7 rows per page of data
            // groupBy: "Shape",
            // groupStartOpen:false,
            groupToggleElement: "header", //toggle group on click anywhere in the group header
            ajaxURL: "{!! route('ajax_get_api_data') !!}",
            footerElement: "<span class='float-left' id='no-of-forms'></span>",
            dataLoaded: function(data) {
                //data - all data loaded into the table
                $('#example-table .tabulator-footer #no-of-forms').text("Total Diamonds: " + data.length);
            },
            ajaxResponse: function(url, params, response) {
                //url - the URL of the request
                //params - the parameters passed with the request
                //response - the JSON object returned in the body of the response.
                return response.data; //return the response data to tabulator
            },
            columns: [ //define the table columns
                {formatter:"rownum", align:"center", width:40},
                {
                    title: "Stock #",
                    field: "stock_no"
                },
                {
                    title: "Lab",
                    field: "lab"
                },
                {
                    title: "Report No",
                    field: "report_no",
                    formatter: "link",
                    formatterParams: {
                        urlField: "report_link",
                        target: "_blank",
                    }
                },
                {
                    title: "Shape",
                    field: "shape"
                },
                {
                    title: "Carat",
                    field: "carats"
                },
                {
                    title: "Color",
                    field: "color"
                },
                {
                    title: "Clarity",
                    field: "clarity"
                },
                {
                    title: "Cut",
                    field: "cut"
                },
                {
                    title: "Polish",
                    field: "polish"
                },
                {
                    title: "Symmetry",
                    field: "symmetry"
                },
                {
                    title: "Fluorescence Color",
                    field: "fluorescence"
                },
                {
                    title: "Measurements",
                    field: "measurements"
                },
                {
                    title: "Depth Percent",
                    field: "depth_percentage"
                },
                {
                    title: "Table Percent",
                    field: "table_percentage"
                },
                {
                    title: "Girdle Percent",
                    field: "girdle"
                },
                {
                    title: "Image",
                    field: "image_link",
                    formatter: "image",
                    formatterParams: {
                        height: "50px",
                        width: "50px",
                    }
                },
                {
                    title: "Country",
                    field: "origin"
                }
            ]
        });
    </script>
</body>

</html>
