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
                <div class="card-header"><h4>{{ $prop->md_name }}  
                    <form action="{{action('PropertyController@destroy', $prop->md_id)}}" method="post" class="delete-frm float-right" data-confirm="Are you sure to delete property: '{{$prop->md_name}}'?">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                    <a class="btn btn-success float-right" href="{{ action('PropertyController@edit', $prop->md_id) }}">Edit</a>
                </h4></div>
                <div class="card-body">
                <p class="card-text">Value: {{ $prop->md_value }}</p>
                <p class="card-text">Property of {{ strtolower($object['objectType']) }}: <a href="{{ url(strtolower($object['objectType']), $object['object']->getID()) }}">{{ $object['objectName'] }}</a></p>
                @if ($prop->author)
                <p class="card-text">Author: {{ $prop->author->au_username }}</p>
                @endif
                <p class="card-text">Created: {{ $prop->md_created }}</p>
                <p class="card-text">Changed: {{ $prop->lastChanged() }}</p>
                @if ($prop->md_deleted)
                <p class="card-text">Deleted: {{ $prop->md_deleted }}</p>
                @endif
                    
                <div class="card">
                <div  data-toggle="collapse" data-target=".change" class="card-header font-weight-bold">Changes</div>
                @if ($prop->changes()->count()>0)
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
                            @each ('partials.change', $prop->changes, 'change' )
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
