@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Update data highway level {{ $dhlevel->hl_name }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ action('DataHighwayController@update', $dhlevel->hl_id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="dhl_name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <textarea id="dhl_name" rows="5" class="form-control @error('dhl_name') is-invalid @enderror" name="dhl_name" required autocomplete="dhl_name" autofocus>{{ old('dhl_name', $dhlevel->hl_name) }}</textarea>

                                @error('dhl_name')
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
