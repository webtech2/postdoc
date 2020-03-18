<?php

namespace App\Http\Controllers;

use App\Author;
use App\Change;
use App\DataHighwayLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataHighwayController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('datahighwaylevels.create');    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dhlevel = new DataHighwayLevel();

        $validatedData = $request->validate([
            'dhl_name' => [
                'min:3|max:100|unique:datahighwaylevel,hl_name'
            ],
        ]);        

        $user = Auth::user();
        $author = $user->author;
        if (!$author) {
            $author = new Author();
            $author->au_id = DB::select('select AUTHOR_AU_ID_SEQ.nextval as au_id from dual')[0]->au_id; 
            $author->au_username = $user->us_name;
            $author->user()->associate($user);
            $author->save();
        }
        
        $dhlevel->hl_name = $request['dhl_name'];
        $dhlevel->hl_created = Carbon::now();   
        $dhlevel->hl_id = DB::select('select DATAHIGHWAYLEVEL_HL_ID_SEQ.nextval as hl_id from dual')[0]->hl_id; 
        
        $dhlevel->save();
        
        $change = new Change();
        $change->ch_id = DB::select('select CHANGE_CH_ID_SEQ.nextval as ch_id from dual')[0]->ch_id; 
        $change->ch_changetype_id = DB::select("select tp_id from types where tp_type='Addition'")[0]->tp_id;
        $change->ch_statustype_id = DB::select("select tp_id from types where tp_type='New'")[0]->tp_id;
        $change->dataHighwayLevel()->associate($dhlevel);
        $change->author()->associate($author);
        $change->ch_datetime = Carbon::now();
        $change->save();
        
        return redirect()->action('DataHighwayController@show', $dhlevel->hl_id)->withSuccess('New data highway level added!');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dhlevel = DataHighwayLevel::find($id);
        return view('datahighwaylevels.show', compact('dhlevel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dhlevel = DataHighwayLevel::find($id);
        return view('datahighwaylevels.edit', compact('dhlevel'));
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
        $dhlevel = DataHighwayLevel::find($id);

        $validatedData = $request->validate([
            'dhl_name' => [
                'min:3|max:100|unique:datahighwaylevel,hl_name'
            ],
        ]);        

        $user = Auth::user();
        $author = $user->author;
        if (!$author) {
            $author = new Author();
            $author->au_id = DB::select('select AUTHOR_AU_ID_SEQ.nextval as au_id from dual')[0]->au_id; 
            $author->au_username = $user->us_name;
            $author->user()->associate($user);
            $author->save();
        }
                
        $change = new Change();
        $change->ch_id = DB::select('select CHANGE_CH_ID_SEQ.nextval as ch_id from dual')[0]->ch_id; 
        $change->ch_changetype_id = DB::select("select tp_id from types where tp_type='Metadata value update'")[0]->tp_id;
        $change->ch_statustype_id = DB::select("select tp_id from types where tp_type='New'")[0]->tp_id;
        $change->ch_attrname = 'hl_name';
        $change->ch_newattrvalue = $request['dhl_name'];
        $change->ch_oldattrvalue = $dhlevel->hl_name;
        $change->dataHighwayLevel()->associate($dhlevel);
        $change->author()->associate($author);
        $change->ch_datetime = Carbon::now();

        $dhlevel->hl_name = $request['dhl_name'];
        $dhlevel->save();

        $change->save();
        
        return redirect()->action('DataHighwayController@show', $dhlevel->hl_id)->withSuccess('Data highway level updated!');;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dhlevel = DataHighwayLevel::find($id);
        $dhlevel->hl_deleted = Carbon::now();       
        $dhlevel->save();      

        $user = Auth::user();
        $author = $user->author;
        if (!$author) {
            $author = new Author();
            $author->au_id = DB::select('select AUTHOR_AU_ID_SEQ.nextval as au_id from dual')[0]->au_id; 
            $author->au_username = $user->us_name;
            $author->user()->associate($user);
            $author->save();
        }

        $change = new Change();
        $change->ch_id = DB::select('select CHANGE_CH_ID_SEQ.nextval as ch_id from dual')[0]->ch_id; 
        $change->ch_changetype_id = DB::select("select tp_id from types where tp_type='Deletion'")[0]->tp_id;
        $change->ch_statustype_id = DB::select("select tp_id from types where tp_type='New'")[0]->tp_id;
        $change->dataHighwayLevel()->associate($dhlevel);
        $change->author()->associate($author);
        $change->ch_datetime = Carbon::now();
        $change->save();
        return redirect()->action('HomeController@index')->withSuccess('Data highway level deleted!');;;
        
    }
}
