@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Create a new data source</div>
                <div class="card-body">
                    <form method="POST" action="{{ action('SourceController@store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="ds_name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="so_name" type="text" class="form-control @error('so_name') is-invalid @enderror" name="so_name" value="{{ old('so_name') }}" required autocomplete="so_name">

                                @error('so_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="so_description" class="col-md-4 col-form-label text-md-right">Description</label>

                            <div class="col-md-6">
                                <textarea id="so_description" rows="5" class="form-control @error('so_description') is-invalid @enderror" name="so_description" autocomplete="so_description">{{ old('so_description') }}</textarea>

                                @error('so_description')
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
