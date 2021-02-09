<?php

namespace App\Http\Controllers;

use App\Change;
use App\DataSource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SourceController extends Controller
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
        $sources = DataSource::whereNull('so_deleted')->orderBy('so_name')->get();
        $delsources = DataSource::whereNotNull('so_deleted')->orderBy('so_deleted','desc')->get();
        return view('sources.index', compact('sources', 'delsources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'so_name' => [
                'min:3|max:100|unique:datasource,so_name'
            ],
            'so_description' => [
                'max:4000'
            ],
        ]);        

        $user = Auth::user();
        $author = $user->getAuthor();
        
        $source = new DataSource();

        $source->so_name = $request['so_name'];
        $source->so_description = $request['so_description'];
        $source->so_created = Carbon::now();   
        $source->so_id = DB::select('select datasource_so_id_seq.nextval as so_id from dual')[0]->so_id; 
        
        $source->save();
        
        $change = new Change();
        $change->ch_id = DB::select('select CHANGE_CH_ID_SEQ.nextval as ch_id from dual')[0]->ch_id; 
        $change->ch_changetype_id = DB::select("select tp_id from types where tp_type='Addition'")[0]->tp_id;
        $change->ch_statustype_id = DB::select("select tp_id from types where tp_type='New'")[0]->tp_id;
        $change->dataSource()->associate($source);
        $change->author()->associate($author);
        $change->ch_datetime = Carbon::now();
        $change->save();
        
        return redirect()->action('SourceController@show', $source->so_id)->withSuccess('New data source added!');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $source = DataSource::find($id);
        return view('sources.show', compact('source'));
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
