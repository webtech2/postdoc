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
        </div>
    </div>
</div>
@endsection
