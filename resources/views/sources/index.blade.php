@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Data Sources | 
                    <a href="{{action('SourceController@create')}}">Create new<a></div>

                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Created</th>
                                <th scope="col">Deleted</th>
                                <th scope="col"></th>
                              </tr>
                        </thead>
                        <tbody>                    
                        @foreach ( $sources as $source )
                            <tr>
                                <td><a href="{{ url('source', $source->so_id) }}">{{$source->so_name}}</a></td>
                                <td><a href="{{ url('source', $source->so_id) }}">{{$source->so_description}}</a></td>
                                <td><a href="{{ url('source', $source->so_id) }}">{{$source->so_created}}</a></td>
                                <td></td>
                                <td>
                                    <a href="{{ action('SourceController@edit', $source->so_id) }}">Edit</a> |
                                    <a href="{{ action('SourceController@destroy', $source->so_id) }}">Delete</a>
                                </td>
                            </tr>                            
                        @endforeach   
                        @foreach ( $delsources as $source )
                            <tr>
                                <td><a href="{{ url('source', $source->so_id) }}">{{$source->so_name}}</a></td>
                                <td><a href="{{ url('source', $source->so_id) }}">{{$source->so_description}}</a></td>
                                <td><a href="{{ url('source', $source->so_id) }}">{{$source->so_created}}</a></td>
                                <td><a href="{{ url('source', $source->so_id) }}">{{$source->so_deleted}}</a></td>
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
