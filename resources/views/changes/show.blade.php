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
                <div class="card-header"><h4>{{ $change->getChangeType()->tp_type }}</h4></div>
                <div class="card-body">
                <p class="card-text">Status: {{ $change->statusType->tp_type }}</p>
                <p class="card-text">Change of {{ strtolower($object['objectType']) }}: <a href="{{ url(strtolower($object['objectType']), $object['object']->getID()) }}">{{ $object['objectName'] }}</a></p>
                @if ($change->author)
                <p class="card-text">Author: {{ $change->author->au_username }}</p>
                @endif
                <p class="card-text">Date: {{ $change->ch_datetime }}</p>
                <p class="card-text">Description: {{ $change->ch_descriptinon }}</p>
                @if ($change->ch_attrname)
                <p class="card-text">Changed attribute name: {{ $change->ch_attrname }}</p>
                <p class="card-text">Value before update: {{ $change->ch_oldattrvalue }}</p>
                <p class="card-text">Value after update: {{ $change->ch_newattrvalue }}</p>
                @endif                    
                </div>
            </div>
            <div class="card">
            <div data-toggle="collapse" data-target=".data" class="card-header font-weight-bold">Change adaptation additional data
                @if ($change->statusType->tp_type=='In progress')
                <a class="btn btn-info float-right" 
                   href="{{ action('AdaptationController@createAdditionalData', $change->ch_id) }}">Create additional data</a>
                @endif
            </div>            
            @if ($change->changeAdaptationAdditionalData()->count()>0)
            <div class="card-text">
                <table class="table table-hover">
                    <thead>
                        <tr class="collapse show set">
                            <th scope="col">Type</th>
                            <th scope="col">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @each ('partials.adapt_data', $change->changeAdaptationAdditionalData, 'data' )
                    </tbody>
                </table>
            </div>
            @endif
            </div> 
            @if ($change->changeAdaptationProcesses()->count()>0)
            <div class="card">
            <div data-toggle="collapse" data-target=".process" class="card-header font-weight-bold">Change adaptation process steps
                @if ($change->statusType->tp_type=='In progress')
                <a class="btn btn-success float-right" 
                   href="{{ action('AdaptationController@runChangeAdaptationScenario', $change->ch_id) }}">Run change adaptation scenario</a>
                @endif
            </div>
            <div class="card-text">
                <table class="table table-hover">
                    <thead>
                        <tr class="collapse show set">
                            <th scope="col">Operation</th>
                            <th scope="col">Status</th>
                            <th scope="col">Type</th>
                            <th scope="col">Condition type</th>
                            <th scope="col">Condition</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @each ('partials.adapt_process', $change->changeAdaptationProcesses()->orderBy('cap_scenario_id')->get(), 'process' )
                    </tbody>
                </table>
            </div>
            </div>            
            @endif
        </div>
    </div>
</div>
@endsection
