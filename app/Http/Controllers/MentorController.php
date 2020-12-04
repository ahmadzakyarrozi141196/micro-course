<?php

namespace App\Http\Controllers;

use App\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{
    //
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'profile' => 'required|url',
            'profession'=> 'required|string',
            'email'=> 'required|email'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()){
            return response()->json([
                'status'=> 'error',
                'message'=>$validator->errors()
            ], 400);
        }

        $mentor = Mentor::create($data);

        return response()->json([
            'status'=> 'success',
            'data'=> $mentor
        ]);
    }

    public function update(Request $request, $id){

        $rules = [
            'name' => 'string',
            'profile' => 'url',
            'profession'=> 'string',
            'email'=> 'email'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()){
            return response()->json([
                'status'=> 'error',
                'message'=>$validator->errors()
            ], 400);
        }

        $mentor = Mentor::find($id);

        if(!$mentor){
            return response()->json([
                'status'=> 'error',
                'message'=> 'mentor not Found'
            ], 404);
        }

        //isi dlu
        $mentor->fill($data);
        //simpan
        $mentor->save();

        //tampilan ke json
        return response()->json([
            'status'=> 'success',
            'data'=> $mentor
        ]);
    }

    //ini get all
    public function index(){
        $mentor = Mentor::all();
        return response()->json([
            'status'=> 'success',
            'data'=> $mentor
        ]);
    }


    //get by id
    public function show($id){
        $mentor = Mentor::find($id);

        if(!$mentor){
            return response()->json([
            'status'=> 'error',
            'message'=> 'Mentor Not found'
            ], 404);
        }

        return response()->json([
            'status'=> 'success',
            'data'=> $mentor
        ]);
    }

    //delete
    public function destroy($id){
        $mentor = Mentor::find($id);
        if(!$mentor){
            return response()->json([
            'status'=> 'error',
            'message'=> 'Mentor Not found'
            ], 404);
        }

        //jika nemu lalu delete
        $mentor->delete();
        //bagian kedepan
        return response()->json([
            'status'=> 'success',
            'data'=> $mentor
        ]);
    }
}
