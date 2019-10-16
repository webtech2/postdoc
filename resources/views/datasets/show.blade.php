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
                <div class="card-header"><h4>{{ $dset->ds_name }}</h4></div>
                <div class="card-body">
                <p class="card-text">{{ $dset->ds_description }}</p>
                @if ($dset->dataSource) 
                <p class="card-text">Data source: <a href="{{ url('source', $dset->dataSource->so_id) }}">{{$dset->dataSource->so_name}}</a></p>
                @endif
                @if ($dset->dataHighwayLevel) 
                <p class="card-text">Data highway level: <a href="{{ url('source', $dset->dataHighwayLevel->hl_id) }}">{{$dset->dataHighwayLevel->hl_name}}</a></p>
                @endif
                <p class="card-text">Format: {{$dset->formatType->tp_type}}</p>
                <p class="card-text">Loading frequency: {{ $dset->ds_frequency }}</p>
                <p class="card-text">Velocity: {{ $dset->velocityType->tp_type }}</p>
                @if ($dset->roleType) 
                <p class="card-text">Role: {{ $dset->roleType->tp_type }}</p>
                @endif
                <p class="card-text">Created: {{ $dset->ds_created }}</p>
                <p class="card-text">Changed: {{ $dset->lastChanged() }}</p>
                @if ($dset->ds_deleted)
                <p class="card-text">Deleted: {{ $dset->ds_deleted }}</p>
                @endif
                </div>
                <div class="card">
                <div class="card-header font-weight-bold"><a href="{{action('PropertyController@index')}}">Properties</a> | 
                    <a href="{{action('PropertyController@createForDataSet', ['id' => $dset->ds_id])}}">Create new<a></div>
                @if ($dset->metadataProperties()->count()>0)
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
                            @foreach ( $dset->metadataProperties()->take(5)->get() as $prop )
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
                @if ($dset->changes()->count()>0)
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
                            @foreach ( $dset->changes()->orderBy('ch_datetime')->take(5)->get() as $change )
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
