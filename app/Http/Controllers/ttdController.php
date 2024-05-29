<?php

namespace App\Http\Controllers;

use App\Models\TtdDataModel;
use Illuminate\Http\Request;

class ttdController extends Controller
{
    //

    public function index() {
        $data['full'] = (TtdDataModel::all());
        return view('ttd_dashboard', $data);
    }
}
