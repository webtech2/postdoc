@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Create new data set for 
                    @if ($object='datasource')
                        data source <a href="{{ url($object, $id) }}">{{$coll->so_name}}</a>
                    @elseif ($object='datahighwaylevel')
                        data highway level <a href="{{ url($object, $id) }}">{{$coll->hl_name}}</a>
                    @endif
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ action('DataSetController@store') }}">
                        @csrf
                        <input type="hidden" name="object" value="{{ $object }}">
                        <input type="hidden" name="id" value="{{ $id }}">
                        <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">Data set type</label>
   
                            <div class="col-md-6">
                                <select id="type" class="type-select form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type') }}" required autocomplete="type" autofocus>
                                    @foreach ($types as $type)
                                    <option value="{{ $type->tp_id }}">{{ $type->tp_type}}</option>
                                    @endforeach
                                </select>

                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="format" class="col-md-4 col-form-label text-md-right">Format type</label>
   
                            <div class="col-md-6">
                                <select id="format" class="sub-type-select form-control @error('type') is-invalid @enderror" name="format" value="{{ old('format') }}" required autocomplete="format">
                                    @foreach ($types as $type)
                                    @foreach ($type->subTypes as $stype)
                                    <option value="{{ $stype->tp_id}}" class="@if ($type->tp_id!=$types[0]->tp_id) d-none @endif" parent-type="{{ $type->tp_id }}">{{ $stype->tp_type}}</option>
                                    @endforeach
                                    @endforeach
                                </select>

                                @error('format')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="velocity" class="col-md-4 col-form-label text-md-right">Velocity</label>
   
                            <div class="col-md-6">
                                <select id="velocity" class="form-control @error('type') is-invalid @enderror" name="velocity" value="{{ old('velocity') }}" required autocomplete="velocity" >
                                    @foreach ($velocities as $velocity)
                                    <option value="{{ $velocity->tp_id }}">{{ $velocity->tp_type}}</option>
                                    @endforeach
                                </select>

                                @error('velocity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="frequency" class="col-md-4 col-form-label text-md-right">Frequency</label>

                            <div class="col-md-6">
                                <textarea id="frequency" rows="5" class="form-control @error('frequency') is-invalid @enderror" name="frequency" required autocomplete="frequency">{{ old('frequency') }}</textarea>

                                @error('frequency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ds_name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="ds_name" type="text" class="form-control @error('ds_name') is-invalid @enderror" name="ds_name" value="{{ old('ds_name') }}" required autocomplete="ds_name">

                                @error('ds_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <input id="table" type="hidden" name="table" value="{{ old('table') }}" required>                                
                                @error('table')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <input id="owner" type="hidden" name="owner" value="{{ old('owner') }}" required>                                
                                @error('owner')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror                              
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ds_desc" class="col-md-4 col-form-label text-md-right">Description</label>

                            <div class="col-md-6">
                                <textarea id="ds_desc" rows="5" class="form-control @error('ds_desc') is-invalid @enderror" name="ds_desc" autocomplete="ds_desc">{{ old('ds_desc') }}</textarea>

                                @error('ds_desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>                        
                    </form>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection
