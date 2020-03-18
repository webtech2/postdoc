@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Create mapping</div>
                <div class="card-body">
                    <form method="POST" action="{{ action('MappingController@store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="dhlevel" class="col-md-4 col-form-label text-md-right">Target data highway level</label>
   
                            <div class="col-md-6">
                                <select id="dhlevel" class="type-select form-control @error('dhlevel') is-invalid @enderror" name="dhlevel" value="{{ old('dhlevel') }}" required autocomplete="dhlevel" autofocus>
                                    @foreach ($dhlevels as $dhlevel)
                                    <option value="{{ $dhlevel->hl_id }}" {{ (old('dhlevel') == $dhlevel->hl_id ? "selected":"") }}>{{ $dhlevel->hl_name}}</option>
                                    @endforeach
                                </select>

                                @error('dhlevel')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dataset" class="col-md-4 col-form-label text-md-right">Data set</label>
   
                            <div class="col-md-6">
                                <select id="dataset" data-parent="dhlevel" class="type-select sub-type-select form-control @error('dataset') is-invalid @enderror" name="dataset" required autocomplete="dataset">
                                    @foreach ($dhlevels as $dhlevel)
                                    @foreach ($dhlevel->dataSets()->whereNull('ds_deleted')->orderBy('ds_name')->get() as $dataset)
                                    <option value="{{ $dataset->ds_id}}" {{ (old('dataset') == $dataset->ds_id ? "selected":"") }} class="@if (old('dhlevel', $dhlevels[0]->hl_id)!=$dhlevel->hl_id) d-none @endif" parent-type="{{ $dhlevel->hl_id }}">{{ $dataset->ds_name}}</option>
                                    @endforeach
                                    @endforeach
                                </select>

                                @error('dataset')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dataitem" class="col-md-4 col-form-label text-md-right">Data item</label>
   
                            <div class="col-md-6">
                                <select id="dataitem" data-parent="dataset" class="sub-type-select form-control @error('dataitem') is-invalid @enderror" name="dataitem" required autocomplete="dataitem">
                                    @foreach ($dhlevels as $dhlevel)
                                    @foreach ($dhlevel->dataSets()->whereNull('ds_deleted')->orderBy('ds_name')->get() as $dataset)
                                    @foreach ($dataset->dataItems()->whereNull('di_deleted')->orderBy('di_name')->get() as $dataitem)
                                    <option value="{{ $dataitem->di_id}}" {{ (old('dataitem') == $dataitem->di_id ? "selected":"") }} class="@if (old('dataitem', $dhlevels[0]->dataSets[0]->ds_id)!=$dataset->ds_id) d-none @endif" parent-type="{{ $dataset->ds_id }}">{{ $dataitem->di_name}}</option>
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                </select>

                                @error('dataitem')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="operation" class="col-md-4 col-form-label text-md-right">Operation</label>

                            <div class="col-md-6">
                                <textarea id="operation" rows="5" class="form-control @error('operation') is-invalid @enderror" name="operation" required autocomplete="operation">{{ old('operation') }}</textarea>

                                @error('operation')
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
                    
                    
                    
                    
                    <div>
                        <div class="form-group row">
                            <label for="dhls" class="col-md-4 col-form-label text-md-right">Origin data highway level/source</label>
   
                            <div class="col-md-6">
                                <select id="dhls" class="type-select form-control @error('dhls') is-invalid @enderror" name="dhls" value="{{ old('dhls') }}" required autocomplete="dhls" autofocus >
                                    <optgroup label="Sources">
                                    @foreach ($sources as $source)
                                    <option value="s{{ $source->so_id }}" {{ (old('dhls') == 's'.$source->so_id ? "selected":"") }}>{{ $source->so_name}}</option>
                                    @endforeach
                                    </optgroup>
                                    <optgroup label="Data highway levels">
                                    @foreach ($dhlevels as $dhlevel)
                                    <option value="dhl{{ $dhlevel->hl_id }}" {{ (old('dhls') == 'dhl'.$dhlevel->hl_id ? "selected":"") }}>{{ $dhlevel->hl_name}}</option>
                                    @endforeach
                                    </optgroup>
                                </select>

                                @error('dhls')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="origdataset" class="col-md-4 col-form-label text-md-right">Origin data set</label>
   
                            <div class="col-md-6">
                                <select id="origdataset" data-parent="dhls" class="type-select sub-type-select form-control @error('origdataset') is-invalid @enderror" name="origdataset" required autocomplete="origdataset">
                                    @foreach ($dhlevels as $dhlevel)
                                    @foreach ($dhlevel->dataSets()->whereNull('ds_deleted')->orderBy('ds_name')->get() as $dataset)
                                    <option value="{{ $dataset->ds_id}}" {{ (old('origdataset') == $dataset->ds_id ? "selected":"") }} class="@if (old('dhls', 'dhl'.$dhlevels[0]->hl_id)!='dhl'.$dhlevel->hl_id) d-none @endif" parent-type="dhl{{ $dhlevel->hl_id }}">{{ $dataset->ds_name}}</option>
                                    @endforeach
                                    @endforeach
                                    @foreach ($sources as $source)
                                    @foreach ($source->dataSets()->whereNull('ds_deleted')->orderBy('ds_name')->get() as $dataset)
                                    <option value="{{ $dataset->ds_id}}" {{ (old('origdataset') == $dataset->ds_id ? "selected":"") }} class="@if (old('dhls', 's'.$sources[0]->so_id)!='s'.$source->so_id) d-none @endif" parent-type="s{{ $source->so_id }}">{{ $dataset->ds_name}}</option>
                                    @endforeach
                                    @endforeach
                                </select>

                                @error('origdataset')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="origdataitem" class="col-md-4 col-form-label text-md-right">Origin data item</label>
   
                            <div class="col-md-6">
                                <select id="origdataitem" data-parent="origdataset" class="sub-type-select form-control @error('origdataitem') is-invalid @enderror" name="origdataitem" required autocomplete="origdataitem">
                                    @foreach ($dhlevels as $dhlevel)
                                    @foreach ($dhlevel->dataSets()->whereNull('ds_deleted')->orderBy('ds_name')->get() as $dataset)
                                    @foreach ($dataset->dataItems()->whereNull('di_deleted')->orderBy('di_name')->get() as $dataitem)
                                    <option value="{{ $dataitem->di_id}}" {{ (old('origdataitem') == $dataitem->di_id ? "selected":"") }} class="@if (old('origdataitem', $dhlevels[0]->dataSets[0]->ds_id)!=$dataset->ds_id) d-none @endif" parent-type="{{ $dataset->ds_id }}">{{ $dataitem->di_name}}</option>
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                    @foreach ($sources as $source)
                                    @foreach ($source->dataSets()->whereNull('ds_deleted')->orderBy('ds_name')->get() as $dataset)
                                    @foreach ($dataset->dataItems()->whereNull('di_deleted')->orderBy('di_name')->get() as $dataitem)
                                    <option value="{{ $dataitem->di_id}}" {{ (old('origdataitem') == $dataitem->di_id ? "selected":"") }} class="@if (old('origdataitem', $sources[0]->dataSets[0]->ds_id)!=$dataset->ds_id) d-none @endif" parent-type="{{ $dataset->ds_id }}">{{ $dataitem->di_name}}</option>
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                </select>

                                @error('origdataitem')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button id="pastebtn" type="button" class="btn btn-secondary">
                                    Paste
                                </button>
                            </div>
                        </div>                        
                        
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<script>
    $('#pastebtn').on('click', function(event) {
        var cursorPosition = $('#operation').prop("selectionStart");
        var dataitem = '[?'+$("#dhls option:selected").text()+'.'+
                $("#origdataset option:selected").text()+'.'+
                $("#origdataitem option:selected").text()+'['+
                $("#origdataitem").val()+']?]';
        
        var v = $('#operation').val();
        var textBefore = v.substring(0,  cursorPosition);
        var textAfter  = v.substring(cursorPosition, v.length);

        $('#operation').val(textBefore + dataitem + textAfter);
    });
</script>
@endsection
