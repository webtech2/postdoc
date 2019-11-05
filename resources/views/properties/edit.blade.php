@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Update property for {{$object}} 
                        <a href="{{ url($object, $id) }}">{{$name}}</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ action('PropertyController@update', ['id' => $prop->md_id]) }}">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $prop->md_name) }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="value" class="col-md-4 col-form-label text-md-right">Value</label>

                            <div class="col-md-6">
                                <textarea id="value" rows="5" class="form-control @error('value') is-invalid @enderror" name="value" required autocomplete="value" autofocus>{{ old('value', $prop->md_value) }}</textarea>

                                @error('value')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
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
