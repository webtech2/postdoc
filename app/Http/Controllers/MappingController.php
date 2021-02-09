<?php

namespace App\Http\Controllers;

use App\Change;
use App\DataHighwayLevel;
use App\DataItem;
use App\DataSource;
use App\Mapping;
use App\MappingOrigin;
use App\MappingSource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function mb_strpos;
use function view;

class MappingController extends Controller
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
        $dhlevels = DataHighwayLevel::whereNull('hl_deleted')->orderBy('hl_name')->get();
        $sources = DataSource::whereNull('so_deleted')->orderBy('so_name')->get();
        return view('mappings.create', compact('dhlevels', 'sources'));  
    }

    public function createForDataItem($id) 
    {
        $item=DataItem::find($id);
        $dhlevels = DataHighwayLevel::whereNull('hl_deleted')->orderBy('hl_name')->get();
        $sources = DataSource::whereNull('so_deleted')->orderBy('so_name')->get();
        return view('mappings.create', compact('dhlevels', 'sources', 'item'));  
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
            'dataitem' => [
                'required|integer'
            ],
            'operation' => [
                'min:3'
            ],
        ]);  
        
        $temp = $request->all();
        $map = new Mapping();
        $map->mp_id = DB::select('select MAPPING_MP_ID_SEQ.nextval as mp_id from dual')[0]->mp_id; 
        $map->mp_target_dataitem_id=$temp['dataitem'];
        $map->mp_operation = $temp['operation'];
        $map->mp_created = Carbon::now();   
        $map->save();
        $operation=$temp['operation'];
        preg_match_all('~\[\?(.+?)\?\]~', $operation, $result);
        $order=0;
        for ($i=0; $i<count($result[1]); $i++) {
            if (mb_strpos($operation, $result[0][$i])!==FALSE) {
                preg_match_all('~\[(.+?)\]~', $result[1][$i], $ids);
                $origin = new MappingOrigin();
                $origin->ms_origin_dataitem_id=$ids[1][0];;
                $origin->ms_order=$order;
                $origin->mapping()->associate($map); 
                $origin->save();
                $operation= str_replace($result[0][$i], '?'.$order.'?', $operation);
                $order++;
                //var_dump($origin);            
            }
                //var_dump($result);
        }
        //var_dump($operation);
        $map->mp_operation=$operation;
        $map->save();

        $user = Auth::user();
        $author = $user->getAuthor();
        
        $change = new Change();
        $change->ch_id = DB::select('select CHANGE_CH_ID_SEQ.nextval as ch_id from dual')[0]->ch_id; 
        $change->ch_changetype_id = DB::select("select tp_id from types where tp_type='Addition'")[0]->tp_id;
        $change->ch_statustype_id = DB::select("select tp_id from types where tp_type='New'")[0]->tp_id;
        $change->mapping()->associate($map);
        $change->author()->associate($author);
        $change->ch_datetime = Carbon::now();
        $change->save();
        return redirect()->action('DataItemController@show', $temp['dataitem'])->withSuccess('Mapping created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $map = Mapping::find($id);
        $map->mp_deleted = Carbon::now();       
        $map->save();

        $user = Auth::user();
        $author = $user->getAuthor();

        $change = new Change();
        $change->ch_id = DB::select('select CHANGE_CH_ID_SEQ.nextval as ch_id from dual')[0]->ch_id; 
        $change->ch_changetype_id = DB::select("select tp_id from types where tp_type='Deletion'")[0]->tp_id;
        $change->ch_statustype_id = DB::select("select tp_id from types where tp_type='New'")[0]->tp_id;
        $change->mapping()->associate($map);
        $change->author()->associate($author);
        $change->ch_datetime = Carbon::now();
        $change->save();
        return redirect()->action('DataItemController@show', $map->targetDataItem->di_id)->withSuccess('Mapping deleted!');
        
    }
}
