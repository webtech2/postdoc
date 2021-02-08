<?php

namespace App\Http\Controllers;

use App\ChangeAdaptationProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class AdaptationController extends Controller
{
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
        return redirect()->action('HomeController@index')->withSuccess('Change adaptation processes created!');
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
        $stmt = $pdo->prepare("begin change_adaptation.set_process_adapted(in_process_id=>:in_process_id); end;");
        $stmt->bindParam(':in_process_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $change = ChangeAdaptationProcess::find($id)->change;
        return redirect()->action('ChangeController@show', $change->ch_id)->withSuccess('Change adaptation process set as executed!');
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
        $stmt = $pdo->prepare("begin change_adaptation.run_change_adaptation_scenario(in_change_id=>:in_change_id); end;");
        $stmt->bindParam(':in_change_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return redirect()->action('ChangeController@show', $id)->withSuccess('Change adaptation scenario steps executed!');
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
        $stmt = $pdo->prepare("begin change_adaptation.set_manual_condition_fulfilled(in_change_id=>:in_change_id, in_condition_id=>:in_condition_id); end;");
        $stmt->bindParam(':in_change_id', $ch_id, PDO::PARAM_INT);
        $stmt->bindParam(':in_condition_id', $cond_id, PDO::PARAM_INT);
        $stmt->execute();
        return redirect()->action('ChangeController@show', $ch_id)->withSuccess('Change adaptation scenario steps executed!');
    } 
    
}
