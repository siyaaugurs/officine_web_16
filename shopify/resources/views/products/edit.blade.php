@extends('layouts.default')

@section('content')
    @include('messages')
    <style>
        form#product_update label {
            font-weight: bold;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-center">{{$product->product_title}}<a href="{{ route('home') }}" class="btn btn-secondary float-right">Back</a></h5>
            <br/>
            <form id="product_update" method="post" enctype="multipart/form-data" action="{{ route('product.update', $product->id) }}">
                @method('PUT')
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="stock_no">Stock Number</label>
                        <input type="text" id="stock_no" class="form-control" name="stock_no" value="{{$product->stock_no}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="shape">Shape</label>
                        <select id="shape" class="form-control" name="shape">
                            <option value="" selected>Choose...</option>
                            @foreach($shapes as $shape)
                            <option value="{{$shape}}" {{ ($shape == $product->shape) || ($shape == 'ROUND' && $product->shape == 'ROUND BRILLIANT') ? 'selected' : ''}}>{{ucfirst(strtolower($shape))}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="lab">Lab</label>
                        <select id="lab" class="form-control" name="lab">
                            <option value="" selected>Choose...</option>
                            @foreach($labs as $lab)
                            <option value="{{$lab}}" {{ $lab == $product->lab ? 'selected' : ''}}>{{$lab}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="report_no">Report Number</label>
                        <input type="text" id="report_no" class="form-control" name="report_no" value="{{$product->report_no}}">
                    </div>
                </div>
                <br/>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="carats">Carat Weight</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="carats" name="carats" min="0" step="0.01" value="{{$product->carats}}" required="required">
                            <div class="input-group-append">
                                <div class="input-group-text">Ct.</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="color">Color</label>
                        <select id="color" class="form-control" name="color">
                            <option value="" selected>Choose...</option>
                            @foreach($colors as $color)
                            <option value="{{$color}}" {{ $color == $product->color ? 'selected' : ''}}>{{$color}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="clarity">Clarity</label>
                        <select id="clarity" class="form-control" name="clarity">
                            <option value="" selected>Choose...</option>
                            @foreach($clarities as $clarity)
                            <option value="{{$clarity}}" {{ $clarity == $product->clarity ? 'selected' : ''}}>{{$clarity}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="cut">Cut</label>
                        <select id="cut" class="form-control" name="cut">
                            <option value="" selected>Choose...</option>
                            <option value="N/A" {{ in_array($product->cut, ['NA', 'N/A']) ? 'selected' : ''}}>NA</option>
                            <option value="ID" {{ "ID" == $product->cut ? 'selected' : ''}}>Ideal</option>
                            <option value="EX" {{ "EX" == $product->cut ? 'selected' : ''}}>Excellent</option>
                            <option value="VG" {{ "VG" == $product->cut ? 'selected' : ''}}>Very Good</option>
                            <option value="GD" {{ in_array($product->cut, ['G', 'GD']) ? 'selected' : ''}}>Good</option>
                        </select>
                    </div>
                </div>
                <br/>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="polish">Polish</label>
                        <select id="polish" class="form-control" name="polish">
                            <option value="" selected>Choose...</option>
                            <option value="EX" {{ "EX" == $product->polish ? 'selected' : ''}}>Excellent</option>
                            <option value="VG" {{ "VG" == $product->polish ? 'selected' : ''}}>Very Good</option>
                            <option value="GD" {{ in_array($product->polish, ['G', 'GD']) ? 'selected' : ''}}>Good</option>
                            <option value="FAIR" {{ "FAIR" == $product->polish ? 'selected' : ''}}>Fair</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="symmetry">Symmetry</label>
                        <select id="symmetry" class="form-control" name="symmetry">
                            <option value="" selected>Choose...</option>
                            <option value="EX" {{ "EX" == $product->symmetry ? 'selected' : ''}}>Excellent</option>
                            <option value="VG" {{ "VG" == $product->symmetry ? 'selected' : ''}}>Very Good</option>
                            <option value="GD" {{ in_array($product->symmetry, ['G', 'GD']) ? 'selected' : ''}}>Good</option>
                            <option value="FAIR" {{ "FAIR" == $product->symmetry ? 'selected' : ''}}>Fair</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="fluorescence">Fluorescence</label>
                        <select id="fluorescence" class="form-control" name="fluorescence">
                            <option value="" selected>Choose...</option>
                            @foreach($fluorescences as $fluorescence)
                            <option value="{{$fluorescence}}" {{ $fluorescence == $product->fluorescence ? 'selected' : ''}}>{{ucfirst(strtolower($fluorescence))}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="measurements">Measurements</label>
                        <div class="input-group">
                            <input type="text" id="measurements" class="form-control" name="measurements" value="{{$product->measurements}}" required="required">
                            <div class="input-group-append">
                                <div class="input-group-text">mm</div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="table_percentage">Table %</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="table_percentage" name="table_percentage" min="0" step="0.01" value="{{$product->table_percentage}}" required="required">
                            <div class="input-group-append">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="depth_percentage">Depth %</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="depth_percentage" name="depth_percentage" min="0" step="0.01" value="{{$product->depth_percentage}}" required="required">
                            <div class="input-group-append">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="ratio">Ratio</label>
                        <input type="number" class="form-control" id="ratio" name="ratio" min="0" step="0.01" value="{{$product->ratio}}">
                    </div>
                </div>
                <br/>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="total_amount">Total Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <div class="input-group-text">$</div>
                            </div>
                            <input type="number" class="form-control" id="total_amount" name="total_amount" min="0" step="0.01" value="{{$product->total_amount}}" required="required">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="final_price">Price</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <div class="input-group-text">$</div>
                            </div>
                            <input type="number" class="form-control" id="final_price" name="final_price" min="0" step="0.01" value="{{$product->final_price}}" required="required">
                        </div>
                    </div>
                </div>
                <br/>
                <div class="form-group row">
                    <label for="report_link" class="col-sm-2 col-form-label">Report Link</label>
                    <div class="col-sm-10">
                        <input type="url" id="report_link" class="form-control" name="report_link" value="{{$product->report_link}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="image_link" class="col-sm-2 col-form-label">Image Link</label>
                    <div class="col-sm-10">
                        <input type="url" id="image_link" class="form-control" name="image_link" value="{{$product->image_link}}">
                        @if(filter_var($product->image_link, FILTER_VALIDATE_URL))
                        <div class="text-center col-md-2" style="padding: 10px 0;">
                            <img src="{{$product->image_link}}" class="img-fluid img-thumbnail" />
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="video_link" class="col-sm-2 col-form-label">Video Link</label>
                    <div class="col-sm-10">
                        <input type="url" id="video_link" class="form-control" name="video_link" value="{{$product->video_link}}">
                        @if(filter_var($product->video_link, FILTER_VALIDATE_URL))
                        <br/>
                        <iframe src="{{$product->video_link}}" style="top: 0; left: 0; bottom: 0; right: 0; width: 100%; height: 500px; border: none; margin: 0; padding: 0; overflow: hidden;"></iframe>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
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
            title: 'Update Diamond Details'
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);


        $('form#csv_import').on('submit', function() {
            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            $('.loader').show();
        });
    </script>
@endsection
