<?php

namespace App\Http\Controllers;

use App\Change;
use App\DataHighwayLevel;
use App\DataSource;
use App\MetadataProperty;
use Illuminate\Http\Request;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sources = DataSource::whereNull('so_deleted')->orderBy('so_created','desc')->get();
        $dhlevels = DataHighwayLevel::whereNull('hl_deleted')->orderBy('hl_created','desc')->get();
        $changes = Change::orderBy('ch_datetime', 'desc')->get();
        $mdproperties = MetadataProperty::all();
        return view('home', compact('sources', 'dhlevels', 'changes', 'mdproperties'));
    }
}
