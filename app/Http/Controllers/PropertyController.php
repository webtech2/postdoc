<?php

namespace App\Http\Controllers;

use App\Author;
use App\Change;
use App\DataSet;
use App\DataSource;
use App\MetadataProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use function redirect;
use function view;

class PropertyController extends Controller
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
        //
    }

    public function createForSource($sid) 
    {
        $source=DataSource::find($sid);
        $object = 'source';
        $id = $sid;
        $name=$source->so_name;
        return view('properties.create', compact('object', 'id', 'name'));
    }
        
    public function createForDataSet($id) 
    {
        $dset=DataSet::find($id);
        $object = 'dataset';
        $name=$dset->ds_name;
        return view('properties.create', compact('object', 'id', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $object, $id)
    { 
        $prop = new MetadataProperty();
        $objcolumn;
        $redirect;        

        switch ($object) {
            case 'source':
                $objcolumn = 'md_datasource_id';
                $prop->dataSource()->associate(DataSource::find($id));
                $redirect=redirect()->action('SourceController@show', $id)->withSuccess('New property added!');
                break;
            case 'dataset':
                $objcolumn = 'md_dataset_id';
                $prop->dataSet()->associate(DataSet::find($id));
                $redirect=redirect()->action('DataSetController@show', $id)->withSuccess('New property added!');
                break;
        }

        $validatedData = $request->validate([
            'name' => [
                'required',
                'min:3',
                'max:100',
                Rule::unique('metadataproperty','md_name')->where(function ($query) use ($objcolumn, $id) {
                    return $query->where($objcolumn, $id)->whereNull('md_deleted');
                })
            ],
            'value' => [
                'required',
                'min:1',
                'max:4000'
             ]
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
        
        $prop->md_name = $request['name'];
        $prop->md_value = $request['value'];
        $prop->md_created = Carbon::now();   
        $prop->md_id = DB::select('select METADATAPROPERTY_MD_ID_SEQ.nextval as md_id from dual')[0]->md_id; 
        $prop->author()->associate($author);        
        
        $prop->save();
        
        $change = new Change();
        $change->ch_id = DB::select('select CHANGE_CH_ID_SEQ.nextval as ch_id from dual')[0]->ch_id; 
        $change->ch_changetype_id = DB::select("select tp_id from types where tp_type='Addition'")[0]->tp_id;
        $change->ch_statustype_id = DB::select("select tp_id from types where tp_type='New'")[0]->tp_id;
        $change->metadataProperty()->associate($prop);
        $change->author()->associate($author);
        $change->ch_datetime = Carbon::now();
        $change->save();
        
        return $redirect;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prop=MetadataProperty::find($id);
        $object=$prop->object();
        return view('properties.show', compact('prop','object'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prop=MetadataProperty::find($id);
        $object=strtolower($prop->object()['objectType']);
        $name=$prop->object()['objectName'];
        $id=$prop->object()['object']->getID();
        return view('properties.edit', compact('prop', 'object', 'id', 'name'));
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
        $prop = MetadataProperty::find($id);
        $object=$prop->object();
        $objcolumn;
        $redirect;        
        switch ($object['objectType']) {
            case 'DataSource':
                $objcolumn = 'md_datasource_id';
                $prop->dataSource()->associate($object['object']);
                $redirect=redirect()->action('SourceController@show', $object['object']->so_id)->withSuccess('Property data changed!');
                break;
            case 'DataSet':
                $objcolumn = 'md_dataset_id';
                $prop->dataSet()->associate($object['object']);
                $redirect=redirect()->action('DataSetController@show', $object['object']->ds_id)->withSuccess('Property data changed!');
                break;
        }

        $validatedData = $request->validate([
            'value' => [
                'required',
                'min:1',
                'max:4000'
             ]
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
        $change->ch_attrname = 'md_value';
        $change->ch_newattrvalue = $request['value'];
        $change->ch_oldattrvalue = $prop->md_value;
        $change->metadataProperty()->associate($prop);
        $change->author()->associate($author);
        $change->ch_datetime = Carbon::now();
        $change->save();

        $prop->md_value = $request['value'];       
        $prop->save();
        
        return $redirect;        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prop = MetadataProperty::find($id);
        $prop->md_deleted = Carbon::now();       
        $prop->save();
        
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
        $change->metadataProperty()->associate($prop);
        $change->author()->associate($author);
        $change->ch_datetime = Carbon::now();
        $change->save();
        return redirect()->route(strtolower($prop->elementType()).'.show', $prop->object()['object']->getID())->withSuccess('Property deleted!');;;
    }
}
