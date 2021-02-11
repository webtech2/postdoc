<?php

namespace App\Http\Controllers;

use App\Change;
use App\ChangeAdaptationProcess;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use Illuminate\Validation\Rule;

class AdaptationController extends Controller
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
     * Create change adaptation processes for new changes.
     *
     * @return \Illuminate\Http\Response
     */
    public function createChangeAdaptationProcesses()
    {
        $pdo = DB::getPdo();
        $stmt;
        $stmt = $pdo->prepare("begin change_adaptation.create_change_adaptation_proc; end;");
        $stmt->execute();               
        return redirect()->action('HomeController@index')
                ->withSuccess('Change adaptation processes created!');
    }  

    /**
     * Set change adaptation process step as executed.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setChangeAdaptationProcessExecuted($id)
    {
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("begin change_adaptation.set_process_adapted("
                . "in_process_id=>:in_process_id); end;");
        $stmt->bindParam(':in_process_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $change = ChangeAdaptationProcess::find($id)->change;
        return redirect()->action('ChangeController@show', $change->ch_id)
                ->withSuccess('Change adaptation process set as executed!');
    }    

    /**
     *  Tries to execute adaptation scenario for specific change 
     *  (only consecutive, not already adapted and automatic change scenario operations 
     *  can be executed).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function runChangeAdaptationScenario($id)
    {
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("begin change_adaptation.run_change_adaptation_scenario("
                . "in_change_id=>:in_change_id); end;");
        $stmt->bindParam(':in_change_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return redirect()->action('ChangeController@show', $id)
                ->withSuccess('Change adaptation scenario steps executed!');
    }    

    /**
     *  Set manual condition as fulfilled.
     *
     * @param  int  $ch_id
     * @param  int  $cond_id
     * @return \Illuminate\Http\Response
     */
    public function setManualConditionFulfilled($ch_id, $cond_id)
    {
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("begin change_adaptation.set_manual_condition_fulfilled("
                . "in_change_id=>:in_change_id, in_condition_id=>:in_condition_id); end;");
        $stmt->bindParam(':in_change_id', $ch_id, PDO::PARAM_INT);
        $stmt->bindParam(':in_condition_id', $cond_id, PDO::PARAM_INT);
        $stmt->execute();
        return redirect()->action('ChangeController@show', $ch_id)
                ->withSuccess('Change adaptation scenario steps executed!');
    } 

    /**
     *  Show the form for creating additional data for the change.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createAdditionalData($id)
    {
        $change = Change::find($id);
        $object = $change->object();
        $dtypes = Type::where('tp_type','Change adaptation additional data type')
                ->first()->subTypes()->orderBy('tp_type')->get();
        $types = Type::where('tp_parenttype_id','DST0000000')
                ->where('tp_id','<>','FMT0000000')->get();
        $velocities = Type::where('tp_parenttype_id','VLT0000000')->get();
        return view('changes.add_data', compact('change','object','dtypes','types','velocities'));
    }  
    
     /**
     * Store newly created additional data for the change in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdditionalData(Request $request)
    {
        $ds = $request->datasource;
        
        $validatedData = $request->validate([
            'change' => [
                'required',
                'exists:change,ch_id',
            ],
            'type' => [
                'required',
                'exists:types,tp_id',
                'starts_with:CAD',
            ],
            'file' => [
                'file',
                Rule::requiredIf($request->format != 'FMT0000031'),           
            ],
            'table_name' => [
                'alpha_num',
                Rule::requiredIf($request->format == 'FMT0000031'),           
            ],   
            'ds_name' => [
                'required',
                Rule::unique('dataset','ds_name')
                    ->where(function ($query) use ($ds) {
                        return $query->where('ds_datasource_id', $ds)->whereNull('ds_deleted');
                    })
            ],
            'velocity' => [
                'required',
                'exists:types,tp_id',
                'starts_with:VLT',
            ],
            'frequency' => [
                'required',
            ],
                            
        ]); 
        
        $data = 'Format: '.$request->format;
        
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $path = $request->file('file')->store('files');
            $data .= '; Path: '.$path; 
        }
        
        if ($request->table_name) {
            $data .= '; Table name: '.$request->table_name; 
        }
        
        $data .= '; Data source name: '.$request->ds_name;
        $data .= '; Data source description: '.$request->ds_desc;
        $data .= '; Velocity: '.$request->velocity;
        $data .= '; Frequency: '.$request->frequency;
        
        $temp = $request->all();
        
        if ($request->type == 'CAD0000001') {
            $pdo = DB::getPdo();
            $stmt = $pdo->prepare("begin change_adaptation.add_dataset_example("
                    . "in_change_id=>:in_change_id, "
                    . "in_data_type=>:in_data_type, "
                    . "in_data=>:in_data); end;");
            $stmt->bindParam(':in_change_id', $temp['change'], PDO::PARAM_INT);
            $stmt->bindParam(':in_data_type', $temp['type'], PDO::PARAM_STR);
            $stmt->bindParam(':in_data', $data);
            $stmt->execute();            
        }
        
        return redirect()->action('ChangeController@show', $temp['change'])
                ->withSuccess('Change adaptation data successfully added!');
    }
}
