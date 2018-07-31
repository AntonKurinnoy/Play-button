@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{--<div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>--}}


        <button type="button" class="btn btn-success btn-lg" name="play">ИГРАТЬ!</button>

    </div>
</div>

@push('scripts')
    <script src="{{asset('js/scripts.js')}}?<?php echo filemtime("js/scripts.js")?>"></script>
@endpush

@endsection
