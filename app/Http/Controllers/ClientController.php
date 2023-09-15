<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Validator;

class ClientController extends Controller
{
    public $method;
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->method = $request->method();
    }

    public function store(Request $request) 
    {
        $status = "failed";
        $msg    = "Something went wrong.";
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|unique:client,name',
        ]);
        if ($validator->passes()) {
            $client = new Client;
            $client->name = $request->client_name;
            $save = $client->save();
            if($save) {
                $status = "success";
                $msg    = "Client added successfully.";
                if($request->ajax()) {  
                    $data['status'] = 200;
                    $data['msg']    = $msg;
                    return response()->json($data,200);
                }
            }
            return redirect()->back();
        } elseif ($validator->fails()) {
            if($request->ajax()) {  
                $data['status'] =403;
                $data['msg']    = $validator->errors()->first('client_name');
                return response()->json($data,403);
            }
            return redirect()->back()->withErrors($validator);
        }
    }
}
