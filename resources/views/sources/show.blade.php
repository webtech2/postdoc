@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
            @endif
            <div class="card">
                <div class="card-header"><h4>{{ $source->so_name }}</h4></div>
                <div class="card-body">
                <p class="card-text">{{ $source->so_description }}</p>
                <p class="card-text">Created: {{ $source->so_created }}</p>
                <p class="card-text">Changed: {{ $source->lastChanged() }}</p>
                @if ($source->so_deleted)
                <p class="card-text">Deleted: {{ $source->so_deleted }}</p>
                @endif
                <div class="card">
                <div class="card-header font-weight-bold"><a href="{{action('DataSetController@sourceDSIndex', ['sid' => $source->so_id])}}">Data Sets</a> | 
                    <a href="{{action('DataSetController@create')}}">Create new<a></div>
                @if ($source->dataSets()->count()>0)
                <div class="card-text">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Format</th>
                                <th scope="col">Loading frequency</th>
                                <th scope="col">Velocity</th>
                                <th scope="col">Changed</th> 
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $source->dataSets()->take(5)->get() as $dset )
                            <tr>
                                <td><a href="{{ url('dataset', $dset->ds_id) }}" title="{{$dset->ds_description}}">{{$dset->ds_name}}</a></td>
                                <td><a href="{{ url('dataset', $dset->ds_id) }}">{{$dset->formatType->tp_type}}</a></td>
                                <td><a href="{{ url('dataset', $dset->ds_id) }}">{{$dset->ds_frequency}}</a></td>
                                <td><a href="{{ url('dataset', $dset->ds_id) }}">{{$dset->velocityType->tp_type}}</a></td>
                                <td><a href="{{ url('dataset', $dset->ds_id) }}">{{$dset->lastChanged()}}</a></td>
                                <td>
                                    <a href="{{ action('DataSetController@edit', $dset->ds_id) }}">Edit</a> |
                                    <a href="{{ action('DataSetController@destroy', $dset->ds_id) }}">Delete</a>
                                </td>
                            </tr>
                            @endforeach 
                        </tbody>
                    </table>
                </div>
                @endif
                </div>
                <div class="card">
                <div class="card-header font-weight-bold"><a href="{{action('PropertyController@index')}}">Properties</a> | 
                    <a href="{{action('PropertyController@createForSource', ['sid' => $source->so_id])}}">Create new<a></div>
                @if ($source->metadataProperties()->count()>0)
                <div class="card-text">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Value</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $source->metadataProperties()->take(5)->get() as $prop )
                            <tr>
                                <td><a href="{{ url('property', $prop->md_id) }}">{{$prop->md_name}}</a></td>
                                <td><a href="{{ url('property', $prop->md_id) }}">{{$prop->md_value}}</a></td>
                                <td>
                                    <a href="{{ action('PropertyController@edit', $prop->md_id) }}">Edit</a> |
                                    <a href="{{ action('PropertyController@destroy', $prop->md_id) }}">Delete</a>
                                </td>                            </tr>
                            @endforeach 
                        </tbody>
                    </table>
                </div>
                @endif
                </div>                    
                <div class="card">
                <div class="card-header font-weight-bold"><a href="{{action('ChangeController@index')}}">Changes</a></div>
                @if ($source->changes()->count()>0)
                <div class="card-text">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Type</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $source->changes()->orderBy('ch_datetime')->take(5)->get() as $change )
                            <tr>
                                <td><a href="{{ url('dataset', $change->ch_id) }}" title="{{$change->ch_description}}">{{$change->changeType->tp_type}}</a></td>
                                <td><a href="{{ url('dataset', $change->ch_id) }}">{{$change->statusType->tp_type}}</a></td>
                                <td><a href="{{ url('dataset', $change->ch_id) }}">{{$change->ch_datetime}}</a></td>
                            </tr>
                            @endforeach 
                        </tbody>
                    </table>
                </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
