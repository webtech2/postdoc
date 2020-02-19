<?php

namespace App\Http\Controllers;

use App\DataHighwayLevel;
use App\DataSet;
use App\DataSource;
use App\Http\Requests\StoreDataSet;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;
use function view;

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
    public function create($object, $id)
    {
        $coll;
        switch ($object) {
            case 'datasource':
                $coll = DataSource::find($id);
                break;
            case 'datahighwaylevel':
                $coll = DataHighwayLevel::find($id);
                break;
        }
        $types = Type::where('tp_parenttype_id','DST0000000')->where('tp_id','<>','FMT0000000')->get();
        $velocities = Type::where('tp_parenttype_id','VLT0000000')->get();
        return view('datasets.create', compact('object', 'id', 'coll', 'types', 'velocities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDataSet $request)
    {
        $temp = $request->all();
        $stmt;
        $pdo = DB::getPdo();
        switch ($temp['format']) { 
            case Type::where('tp_type','Relational')->first()->tp_id: // relational
                if ($temp['object'] == 'datahighwaylevel') {
                    $stmt = $pdo->prepare("begin POSTDOC_METADATA.GATHER_TABLE_METADATA(P_TABLE_NAME=>:P_TABLE_NAME, P_HL_ID=>:P_HL_ID, P_DS_DESC=>:P_DS_DESC, "
                            . "P_VELOCITY_ID=>:P_VELOCITY_ID, P_FORMATTYPE_ID=>:P_FORMATTYPE_ID, P_FREQ=>:P_FREQ, P_USERMAIL=>:P_USERMAIL); end;");
                    $stmt->bindParam(':P_HL_ID', $temp['id'], PDO::PARAM_INT);

                } else {
                    $stmt = $pdo->prepare("begin POSTDOC_METADATA.GATHER_TABLE_METADATA(P_TABLE_NAME=>:P_TABLE_NAME, P_SO_ID=>:P_SO_ID, P_DS_DESC=>:P_DS_DESC, "
                            . "P_VELOCITY_ID=>:P_VELOCITY_ID, P_FORMATTYPE_ID=>:P_FORMATTYPE_ID, P_FREQ=>:P_FREQ, P_USERMAIL=>:P_USERMAIL); end;");            
                    $stmt->bindParam(':P_SO_ID', $temp['id'], PDO::PARAM_INT);
                }
                $stmt->bindParam(':P_TABLE_NAME', $temp['ds_name']);
                break;
            case Type::where('tp_type','XML')->first()->tp_id: // XML
                if ($temp['object'] == 'datahighwaylevel') {
                    $stmt = $pdo->prepare("begin POSTDOC_METADATA.GATHER_XML_METADATA(P_SPEC=>:P_SPEC, P_HL_ID=>:P_HL_ID, P_DS_DESC=>:P_DS_DESC, "
                            . "P_VELOCITY_ID=>:P_VELOCITY_ID, P_FORMATTYPE_ID=>:P_FORMATTYPE_ID, P_FREQ=>:P_FREQ, P_USERMAIL=>:P_USERMAIL); end;");
                    $stmt->bindParam(':P_HL_ID', $temp['id'], PDO::PARAM_INT);

                } else {
                    $stmt = $pdo->prepare("begin POSTDOC_METADATA.GATHER_XML_METADATA(P_SPEC=>:P_SPEC, P_SO_ID=>:P_SO_ID, P_DS_DESC=>:P_DS_DESC, "
                            . "P_VELOCITY_ID=>:P_VELOCITY_ID, P_FORMATTYPE_ID=>:P_FORMATTYPE_ID, P_FREQ=>:P_FREQ, P_USERMAIL=>:P_USERMAIL); end;");            
                    $stmt->bindParam(':P_SO_ID', $temp['id'], PDO::PARAM_INT);
                }                
                $stmt->bindParam(':P_SPEC', $temp['ds_name']);
                break;
        }              
        $mail = Auth::user()->us_email;
        $stmt->bindParam(':P_DS_DESC', $temp['ds_desc']);
        $stmt->bindParam(':P_VELOCITY_ID', $temp['velocity']);
        $stmt->bindParam(':P_FORMATTYPE_ID', $temp['format']);
        $stmt->bindParam(':P_FREQ', $temp['frequency']);
        $stmt->bindParam(':P_USERMAIL', $mail);
        $stmt->execute();               
        return $request;
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
