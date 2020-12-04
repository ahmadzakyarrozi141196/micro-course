<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chapter;
use App\Lesson;
use Illuminate\Support\Facades\Validator;


class LessonController extends Controller
{
    //
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'video' => 'required|string',
            'chapter_id'=>'required|integer'
        ];

        //ambil semua request
        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $chapterId = $request->input('chapter_id');
        $chapter = Chapter::find($chapterId);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chapter not found'
            ], 404);
        }

        $lesson = Lesson::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }

    public function update(Request $request, $id){
        $rules = [
            'name' => 'string',
            'video' => 'string',
            'chapter_id'=>'integer'
        ];

        //ambil semua request
        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $lesson = Lesson::find($id);
            if (!$lesson){
                return response()->json([
                    'status' => 'error',
                    'data' =>'Lesson Not found'
                ]);
            }
            //input dari chapterid

            $chapterId = $request->input('chapter_id');
            if($chapterId){
                $chapter = Chapter::find($chapterId);

                if(!$chapter){
                    return response()->json([
                        'status'=> 'error',
                        'data'=> 'Chapter Not Found'
                    ]);
                }

            }
            $lesson->fill($data);
            $lesson->save();

            return response()->json([
                'status'=> 'Sukses',
                'data'=> $lesson
            ]);

    }

    public function index(Request $request){
        $lessons = Lesson::query();
        $chapterId= $request->query('chapter_id');

        $lessons->when($chapterId, function($query) use ($chapterId){

            return $query-> where('chapter_id', '=', $chapterId);
        });

        return response()->json([
            'status'=> 'success',
            'data'=> $lessons->get()
        ]);
    }

    public function show($id){
        $lessons = Lesson::find($id);

        if(!$lessons){
            return response()->json([
                'status'=> 'error',
                'data'=> 'Get by Id Error'
            ]);
        }
        return response()->json([
            'status'=> 'error',
            'data'=> $lessons
        ]);
    }

    public function destroy($id){
        $lessons = Lesson::find($id);
        if(!$lessons){
            return response()->json([
                'status'=> 'error',
                'data'=> 'Get by Id Error'
            ]);
        }
        $lessons->delete();
        return response()->json([
            'status'=> 'error',
            'data'=> $lessons
        ]);
    }

}
