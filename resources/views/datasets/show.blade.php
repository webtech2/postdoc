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
                <p class="card-text">Data source: <a href="{{ url('datasource', $dset->dataSource->so_id) }}">{{$dset->dataSource->so_name}}</a></p>
                @endif
                @if ($dset->dataHighwayLevel) 
                <p class="card-text">Data highway level: <a href="{{ url('datahighwaylevel', $dset->dataHighwayLevel->hl_id) }}">{{$dset->dataHighwayLevel->hl_name}}</a></p>
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
                <div class="card-header font-weight-bold">Schema: Data Items</div>
                <div class="card-text">
                    @if ($dset->formatType->tp_type == 'XML')
                    <ul>
                        @each('partials.dataitem_tree', $dset->topDataItems, 'item')
                    </ul>
                    @elseif ($dset->formatType->tp_type == 'Relational')
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @each('partials.dataitem_table', $dset->dataItems, 'item')
                        </tbody>
                    </table>
                    @endif
                </div>
                <div class="card">
                <div data-toggle="collapse" data-target=".prop" class="card-header font-weight-bold">Properties | 
                    <a href="{{action('PropertyController@createForDataSet', ['id' => $dset->ds_id])}}">Create new<a></div>
                @if ($dset->metadataProperties()->whereNull('md_deleted')->count()>0)
                <div class="card-text">
                    <table class="table table-hover">
                        <thead>
                            <tr class="collapse show prop">
                                <th scope="col">Name</th>
                                <th scope="col">Value</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @each ('partials.property',  $dset->metadataProperties()->whereNull('md_deleted')->get(), 'prop' )
                        </tbody>
                    </table>
                </div>
                @endif
                </div> 
                <div class="card">
                <div data-toggle="collapse" data-target=".change" class="card-header font-weight-bold">Changes</div>
                @if ($dset->changes()->count()>0)
                <div class="card-text">
                    <table class="table table-hover">
                        <thead>
                            <tr class="collapse show change">
                                <th scope="col">Type</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                                <th scope="col">Object Type</th>
                                <th scope="col">Object</th>
                            </tr>
                        </thead>
                        <tbody>
                            @each ('partials.change', $dset->changes()->orderBy('ch_datetime')->get(), 'change' )
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
