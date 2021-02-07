<?php

namespace App\Http\Controllers;

use App\Change;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class ChangeController extends Controller
{
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
        $change = Change::find($id);
        $object = $change->object();
        return view('changes.show', compact('change','object'));
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
}
