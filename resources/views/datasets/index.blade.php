@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4><a href="{{ url('source', $source->so_id) }}">{{ $source->so_name }}</a></h4></div>
                <div class="card-header">Data Sets | 
                    <a href="{{action('DataSetController@create')}}">Create new<a></div>

                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Format</th>
                                <th scope="col">Loading frequency</th>
                                <th scope="col">Velocity</th>
                                <th scope="col">Changed</th> 
                                <th scope="col">Deleted</th>
                                <th scope="col"></th>
                              </tr>
                        </thead>
                        <tbody>                    
                        @foreach ( $sets as $set )
                            <tr>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->ds_name}}</a></td>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->formatType->tp_type}}</a></td>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->ds_frequency}}</a></td>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->velocityType->tp_type}}</a></td>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->lastChanged()}}</a></td>
                                <td></td>
                                <td>
                                    <a href="{{ action('DataSetController@edit', $set->ds_id) }}">Edit</a> |
                                    <a href="{{ action('DataSetController@destroy', $set->ds_id) }}">Delete</a>
                                </td>
                            </tr>                            
                        @endforeach   
                        @foreach ( $delsets as $set )
                            <tr>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->ds_name}}</a></td>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->formatType->tp_type}}</a></td>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->ds_frequency}}</a></td>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->velocityType->tp_type}}</a></td>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->lastChanged()}}</a></td>
                                <td><a href="{{ url('dataset', $set->ds_id) }}">{{$set->ds_deleted}}</a></td>
                                <td></td>
                            </tr>
                        @endforeach   
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
