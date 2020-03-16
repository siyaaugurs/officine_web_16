@extends('layouts.default')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-center">Welcome <a href="#">{{ ShopifyApp::shop()->shopify_domain }}</a></h5>

            <a class="btn btn-success" href="{{ route('import_csv') }}">Import CSV</a>
            <a class="btn btn-primary" href="{{ route('ftp_import') }}">FTP Import</a>

            <form method="GET" action="{{ route('home') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                    <span class="input-group-append">
                        <button class="btn btn-secondary grey_bg m-l-0" type="submit">Search</button>
                    </span>
                </div>
            </form>

            <table class="table table-bordered">
                <caption style="caption-side: top; text-align: center;">Total {{ $total_products }} Diamonds</caption>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Stock No</th>
                        <th scope="col">Diamond Name</th>
                        <th scope="col">Details</th>
                        <th scope="col">Final Price</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Updated At</th>
                        <!--<th scope="col">Published</th>-->
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->stock_no }}</td>
                            <td>{{ $product->product_title }}</td>
                            <td>
                                <table class="table table-bordered">
                                    <tbody>
                                        @if($product->lab)
                                        <tr>
                                            <th scope="row">Lab:</th>
                                            <td>{{ $product->lab }}</td>
                                        </tr>
                                        @endif
                                        @if($product->report_no)
                                        <tr>
                                            <th scope="row">Report No:</th>
                                            <td>{{ $product->report_no }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th scope="row">Cut:</th>
                                            <td>{{ $product->cut }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Polish:</th>
                                            <td>{{ $product->polish }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Symmetry:</th>
                                            <td>{{ $product->symmetry }}</td>
                                        </tr>
                                        @if($product->fluorescence)
                                        <tr>
                                            <th scope="row">Fluorescence:</th>
                                            <td>{{ $product->fluorescence }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th scope="row">Measurements:</th>
                                            <td>{{ $product->measurements }}</td>
                                        </tr>
                                        @if($product->table_percentage)
                                        <tr>
                                            <th scope="row">Table(%):</th>
                                            <td>{{ $product->table_percentage }}</td>
                                        </tr>
                                        @endif
                                        @if($product->depth_percentage)
                                        <tr>
                                            <th scope="row">Depth(%):</th>
                                            <td>{{ $product->depth_percentage }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th scope="row">Total Amount:</th>
                                            <td>${{ $product->total_amount }}</td>
                                        </tr>
                                        @if(!empty($product->video_link) && filter_var($product->video_link, FILTER_VALIDATE_URL))
                                        <tr>
                                            <th scope="row">Video:</th>
                                            <td><a class="btn btn-link" target="_blank" href="{{$product->video_link}}">Show Video</a></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                            <td>${{ $product->final_price }}</td>
                            <td>{{ $product->created_at->diffForHumans() }}</td>
                            <td>{{ $product->updated_at->diffForHumans() }}</td>
<!--                            <td>
                                @if($product->published)
                                <h5><span class="badge badge-success">Yes</span></h5>
                                @else
                                <h5><span class="badge badge-danger">No</span></h5>
                                @endif
                            </td>-->
                            <td>
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No new product!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $products->links() }}
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
            title: 'Welcome',
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);
    </script>
@endsection
