@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4>Additional data for change</h4></div>
                <div class="card-body">
                    <p class="card-text">Type: {{ $change->getChangeType()->tp_type }}</p>
                    <p class="card-text">Status: {{ $change->statusType->tp_type }}</p>
                    <p class="card-text">Change of {{ strtolower($object['objectType']) }}: 
                        <a href="{{ url(strtolower($object['objectType']), $object['object']->getID()) }}">{{ $object['objectName'] }}</a>
                    </p>
                    @if ($change->author)
                    <p class="card-text">Author: {{ $change->author->au_username }}</p>
                    @endif
                    <p class="card-text">Date: {{ $change->ch_datetime }}</p>
                    @if ($change->ch_attrname)
                    <p class="card-text">Changed attribute name: {{ $change->ch_attrname }}</p>
                    <p class="card-text">Value before update: {{ $change->ch_oldattrvalue }}</p>
                    <p class="card-text">Value after update: {{ $change->ch_newattrvalue }}</p>
                    @endif  

                    <form id="create" method="POST" enctype="multipart/form-data" 
                          action="{{ action('AdaptationController@storeAdditionalData') }}">
                        @csrf
                        <input type="hidden" value="{{ $change->ch_id}}" name="change">
                        <input type="hidden" value="{{ $object['object']->getID()}}" name="{{strtolower($object['objectType'])}}">

                        <div class="form-group row">
                            <label for="dtype" class="col-md-4 col-form-label text-md-right">Type of additional data</label>
   
                            <div class="col-md-6">
                                <select id="dtype" class="type-select form-control @error('type') is-invalid @enderror" 
                                        name="type" value="{{ old('type') }}" required autocomplete="type" autofocus>
                                    @foreach ($dtypes as $type)
                                    <option value="{{ $type->tp_id }}" {{ (old('type') == $type->tp_id ? "selected":"") }}>{{ $type->tp_type}}</option>
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
                            <label for="ds_name" class="col-md-4 col-form-label text-md-right">Dataset name</label>

                            <div class="col-md-6">
                                <input id="ds_name" type="text" class="form-control @error('ds_name') is-invalid @enderror" name="ds_name" value="{{ old('ds_name') }}" autocomplete="ds_name">

                                @error('ds_name')
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
                        
                        <div class="form-group row">
                            <label for="velocity" class="col-md-4 col-form-label text-md-right">Velocity</label>
   
                            <div class="col-md-6">
                                <select id="velocity" class="form-control @error('velocity') is-invalid @enderror" name="velocity" value="{{ old('velocity') }}" required autocomplete="velocity" >
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
                            <label for="type" class="col-md-4 col-form-label text-md-right">Data set type</label>
   
                            <div class="col-md-6">
                                <select id="type" class="type-select form-control @error('type') is-invalid @enderror" value="{{ old('type') }}" required autocomplete="type" autofocus>
                                    @foreach ($types as $type)
                                    <option value="{{ $type->tp_id }}" {{ (old('type') == $type->tp_id ? "selected":"") }}>{{ $type->tp_type}}</option>
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
                                <select id="format" data-parent="type" class="sub-type-select form-control @error('format') is-invalid @enderror" name="format" required autocomplete="format">
                                    @foreach ($types as $type)
                                    @foreach ($type->subTypes as $stype)
                                    <option value="{{ $stype->tp_id}}" {{ (old('format') == $stype->tp_id ? "selected":"") }} class="@if (old('type', $types[0]->tp_id)!=$type->tp_id) d-none @endif" parent-type="{{ $type->tp_id }}">{{ $stype->tp_type}}</option>
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
                            <label for="file" class="col-md-4 col-form-label text-md-right">Data set example</label>

                            <div class="col-md-6">
                                <input id="file" type="file" class="form-control @error('file') is-invalid @enderror" name="file" value="{{ old('file') }}" autocomplete="file">

                                @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="table_name" class="col-md-4 col-form-label text-md-right">Table name</label>

                            <div class="col-md-6">
                                <input id="table_name" type="text" class="form-control @error('table_name') is-invalid @enderror" name="table_name" value="{{ old('table_name') }}" autocomplete="table_name">

                                @error('table_name')
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

<script>
    function checkFormat () {
        if ($('#format option:selected').val() == 'FMT0000031') {
            $('#file').prop( "disabled", true );
            $('#table_name').prop( "disabled", false );
        } else {
            $('#file').prop( "disabled", false );
            $('#table_name').prop( "disabled", true );            
        }
    }
    
    checkFormat();
    
    $('#format').on('change', checkFormat);

</script>
@endsection
