@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{action('SourceController@index')}}">Data Sources<a> | 
                    <a href="{{action('SourceController@create')}}">Create new<a>
                </div>

                <div class="card-body">
                    @foreach ( $sources as $source )
                    <div class="card">
                        <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ url('source', $source->so_id) }}">{{ $source->so_name }}</a>
                        </h5>
                        <p class="card-text">
                            <span class="badge">{{$source->so_description}}</span>
                        </p>
                        </div>
                    </div>
                    @endforeach                    
                </div>
            </div>
            <div class="card">
                <div class="card-header">Data Highway Levels</div>

                <div class="card-body">
                    @foreach ( $dhlevels as $dhlevel )
                    <div class="card">
                        <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ url('dhlevel', $dhlevel['hl_id']) }}">{{ $dhlevel->hl_name }}</a>
                        </h5>
                        </div>
                    </div>
                    @endforeach                    
                    
                </div>
            </div>
            <div class="card">
                <div class="card-header">Changes</div>

                <div class="card-body">
                    @foreach ( $changes as $change )
                    <div class="card">
                        <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ url('change', $change['ch_id']) }}">{{ $change->changeType->tp_type }}</a>
                        </h5>
                        <p class="card-text">
                            <span class="badge">{{$change->statusType->tp_type}}</span>
                        </p>
                        </div>
                    </div>
                    @endforeach                    
                    
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
