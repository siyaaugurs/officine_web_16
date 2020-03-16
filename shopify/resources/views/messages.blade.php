@if ($errors->any()) 
    <ul class="list-group m-b-20">
        @foreach ($errors->all() as $error)
            <li class="list-group-item list-group-item-danger">{!! $error !!}</li>
        @endforeach
    </ul>
@endif

@if(session('flash_success'))
    @alert(['class' => 'success'])
        {!! session('flash_success') !!}
    @endalert
@endif

@if(session('flash_error'))
    @alert(['class' => 'danger'])
        {!! session('flash_error') !!}
    @endalert
@endif

@if(session('flash_warning'))
    @alert(['class' => 'warning'])
        {!! session('flash_warning') !!}
    @endalert
@endif

@if(session('flash_info'))
    @alert(['class' => 'info'])
        {!! session('flash_info') !!}
    @endalert
@endif

@if(session('flash_dark'))
    @alert(['class' => 'dark'])
        {!! session('flash_dark') !!}
    @endalert
@endif