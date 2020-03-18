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
                <div class="card-header"><h4>{{ $dhlevel->hl_name }}</h4></div>
                <div class="card-body">
                <p class="card-text">Created: {{ $dhlevel->hl_created }}</p>
                <p class="card-text">Changed: {{ $dhlevel->lastChanged() }}</p>
                @if ($dhlevel->so_deleted)
                <p class="card-text">Deleted: {{ $dhlevel->hl_deleted }}</p>
                @endif
                <div class="card">
                <div data-toggle="collapse" data-target=".set" class="card-header font-weight-bold">Data Sets | 
                    <a href="{{action('DataSetController@create', ['object' => 'datahighwaylevel', 'id' => $dhlevel->hl_id])}}">Create new<a></div>
                @if ($dhlevel->dataSets()->whereNull('ds_deleted')->count()>0)
                <div class="card-text">
                    <table class="table table-hover">
                        <thead>
                            <tr class="collapse show set">
                                <th scope="col">Name</th>
                                <th scope="col">Format</th>
                                <th scope="col">Loading frequency</th>
                                <th scope="col">Velocity</th>
                                <th scope="col">Changed</th> 
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @each ('partials.dataset', $dhlevel->dataSets()->whereNull('ds_deleted')->get(), 'dset' )
                        </tbody>
                    </table>
                </div>
                @endif
                </div>
                <div class="card">
                <div data-toggle="collapse" data-target=".prop"  class="card-header font-weight-bold">Properties | 
                    <a href="{{action('PropertyController@createForDHlevel', ['hlid' => $dhlevel->hl_id])}}">Create new<a></div>
                @if ($dhlevel->metadataProperties()->whereNull('md_deleted')->count()>0)
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
                            @each ('partials.property', $dhlevel->metadataProperties()->whereNull('md_deleted')->get(), 'prop' )
                        </tbody>
                    </table>
                </div>
                @endif
                </div>                    
                <div class="card">
                <div  data-toggle="collapse" data-target=".change" class="card-header font-weight-bold">Changes</div>
                @if ($dhlevel->changes()->count()>0)
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
                            @each ('partials.change', $dhlevel->changes, 'change' )
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
