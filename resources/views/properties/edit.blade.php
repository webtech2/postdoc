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
                    <form method="POST" action="{{ action('PropertyController@update', $prop->md_id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <div class="col-md-4 col-form-label text-md-right">Name</div>

                            <div class="col-md-6 col-form-label">
                                <div>{{ $prop->md_name }}</div>
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
