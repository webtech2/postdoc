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
                <div class="card-header"><h4>{{ $item->di_name }}</h4></div>
                <div class="card-body">
                <p class="card-text">Data set: <a href="{{ url('dataset', $item->dataSet->ds_id) }}">{{$item->dataSet->ds_name}}</a></p>
                <p class="card-text">Type: {{$item->itemType->tp_type}}</p>
                @if ($item->role) 
                <p class="card-text">Role: {{ $item->role->tp_type }}</p>
                @endif
                <p class="card-text">Changed: {{ $item->lastChanged() }}</p>
                </div>
                <div class="card">
                <div data-toggle="collapse" data-target=".prop" class="card-header font-weight-bold">Properties | 
                    <a href="{{action('PropertyController@createForDataItem', ['id' => $item->di_id])}}">Create new<a></div>
                @if ($item->metadataProperties()->count()>0)
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
                            @each ('partials.property',  $item->metadataProperties, 'prop' )
                        </tbody>
                    </table>
                </div>
                @endif
                </div> 
                <div class="card">
                <div data-toggle="collapse" data-target=".change" class="card-header font-weight-bold">Changes</div>
                @if ($item->changes()->count()>0)
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
                            @each ('partials.change', $item->changes, 'change' )
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
