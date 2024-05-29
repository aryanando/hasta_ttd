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

    public function store(Request $request) {
        $postData = $request;
        
        $validatedData = $postData->validate([
            'nosurat' => 'required|string|unique:ttd_data',
            'ttd' => 'required|string',
            'waktu' => 'required|string',
        ]);

        //create post
        if ($validatedData) {
            TtdDataModel::create([
                'nosurat'     => $request->nosurat,
                'ttd'     => $request->ttd,
                'waktu'   => $request->waktu,
                'unique_id'     => md5($request->nosurat)
            ]);
    
            //redirect to index
            return redirect()->route('v2-dashboard')->with(['success' => 'Data Berhasil Disimpan!']);
        }
    }

    public function show($id) {
        $data = TtdDataModel::select('*')->
        where('id','=',$id)->
        get();
        if (count($data) > 0) {
            $dataSprin['data'] = $data[0];
            return view('sprin_v1', $dataSprin);
            # code...
        } else {
            
        }
    }

    public function showV2($id) {
        $data = TtdDataModel::select('*')->
        where('unique_id','=',$id)->
        get();
        if (count($data) > 0) {
            $dataSprin['data'] = $data[0];
            return view('sprin_v2', $dataSprin);
            # code...
        } else {
            
        }
    }
}
