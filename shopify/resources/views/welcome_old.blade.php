@extends('layouts.default')

@section('content')
    <div class="card">        
        <div class="card-body">
            <h5 class="card-title text-center">Welcome <a href="#">{{ ShopifyApp::shop()->shopify_domain }}</a></h5>
            
            <a href="{{ route('import_csv') }}" class="btn btn-primary" style="margin-bottom: 15px;">Import CSV</a>
            
            <table class="table table-bordered">
                <caption style="caption-side: top;">New products for today</caption>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"></th>
                        <th scope="col">Title</th>
                        <th scope="col">Vendor</th>
                        <th scope="col">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td scope="row">{{ $loop->iteration }}</td>
                            <td><image src="{{ $product->image->src }}" alt="{{ $product->title }}" height="50"></td>
                            <td>{{ $product->title }}</td>
                            <td>{{ $product->vendor }}</td>
                            <td>{{ $product->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No new product!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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