@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        <button id="sub" onclick="subscribe()" type="button" class="btn btn-success">Subscribe</button>
                        <button id="unsub" onclick="unsubscribe()" type="button" class="btn btn-danger" style="display: none">Unsubscribe</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/socket.js')}}"></script>
    {{--<script src="{{asset('js/client.js')}}"></script>--}}
@stop
