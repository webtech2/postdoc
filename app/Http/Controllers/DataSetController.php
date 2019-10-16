<?php

namespace App\Http\Controllers;

use App\DataSet;
use App\DataSource;
use Illuminate\Http\Request;

class DataSetController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function sourceDSIndex($sid) 
    {
        $source=DataSource::find($sid);
        $sets = DataSet::whereNull('ds_deleted')->where('ds_datasource_id','=',$sid)->orderBy('ds_name')->orderBy('ds_created','desc')->get();
        $delsets = DataSet::whereNotNull('ds_deleted')->where('ds_datasource_id','=',$sid)->orderBy('ds_name')->orderBy('ds_deleted','desc')->get();
        return view('datasets.index', compact('sets', 'delsets', 'source'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dset = DataSet::where('ds_id','=',$id)->get()[0];
        return view('datasets.show', compact('dset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
