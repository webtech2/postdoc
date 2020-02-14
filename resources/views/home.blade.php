@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div data-toggle="collapse" data-target=".source" class="card-header font-weight-bold">Data Sources | <a href="{{action('SourceController@create')}}">Create new<a></div>

                <div class="card-text">
                    <table class="table table-hover">
                    <thead>
                        <tr class="collapse show source">
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ( $sources as $source )
                        <tr class="collapse show source">
                            <td><a href="{{ action('SourceController@show', $source->so_id) }}">{{ $source->so_name }}</a></td>
                            <td><a href="{{ action('SourceController@show', $source->so_id) }}">{{$source->so_description}}</a></td>
                        </tr>
                    @endforeach                    
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div data-toggle="collapse" data-target=".level" class="card-header font-weight-bold">Data Highway Levels | <a href="{{action('DataHighwayController@create')}}">Create new<a></div>

                <div class="card-text">
                    <table class="table table-hover">
                    <thead>
                        <tr class="collapse show level">
                            <th scope="col">Name</th>
                            <th scope="col">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @each ('partials.datahighwaylevel', $dhlevels, 'dhlevel' )
                    </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
            <div  data-toggle="collapse" data-target=".change" class="card-header font-weight-bold">Changes</div>
            @if ($changes->count()>0)
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
                        @each ('partials.change', $changes, 'change' )
                    </tbody>
                </table>
            </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection
