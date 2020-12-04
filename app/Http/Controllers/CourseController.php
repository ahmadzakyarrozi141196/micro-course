<?php

namespace App\Http\Controllers;

use App\Course;
use App\Mentor;
use App\MyCourse;
use App\Chapter;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    //

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'string|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            'mentor_id' => 'required|integer',
            'description' => 'string'
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

        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        $course = Course::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function update(Request $request, $id){
        $rules = [
            'name' => 'string',
            'certificate' => 'boolean',
            'thumbnail' => 'url',
            'type' => 'in:free,premium',
            'status' => 'in:draft,published',
            'price' => 'integer',
            'level' => 'in:all-level,beginner,intermediate,advance',
            'mentor_id' => 'integer',
            'description' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $course = Course::find($id);
        if(!$course){
            return response()->json([
                'status'=> 'error',
                'message'=> 'course not found'
            ], 404);
        }

        //jika diisi dari sisi frontend
        $mentorId = $request->input('mentor_id');
        if($mentorId){
            $mentor = Mentor::find($mentorId);
            if(!$mentor){
                return response()->json([
                    'status'=> 'error',
                    'message'=> 'Mentor not found'
                ], 404);
            }
        }

        $course->fill($data);
        $course->save();

        return response()->json([
            'status'=> 'success',
            'data'=> $course
        ]);

    }

    //ini ambil dari query maka harus di paramater
    //jika ga ada paramater maka
    // $courses = Course::all();
    public function index(Request $request){
        $courses = Course::query();
        $q = $request->query('q');
        $status = $request->query('status');
        $courses->when($q, function($query) use ($q){
            //membuat filter gate dari q
            return $query->whereRaw("name LIKE '%".strtolower($q)."%'");
        });
        $courses->when($status, function($query) use ($status){
            return $query->where('status', '=', $status);
        });



        return response()->json([
            'status'=> 'sukses',
            'data'=>$courses->paginate(10)
        ]);
    }

    public function destroy($id){
        $course = Course::find($id);

        if (!$course){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Course Not found'
            ]);
        }

        $course->delete();

        return response()->json([
            'status'=> 'success',
            'message'=> 'Course deleted'
        ]);
    }

    public function show($id){
        //mengarah ke model chapter kemudian lessons
        $course = Course::with('chapter.lessons')
        ->with('mentor')
        ->with('images')
        ->find($id);

        if(!$course){
            return response()->json([
                'status'=> 'error',
                'message'=>'Course Not Found'
            ],404);
        }


        //mendapatkan hasil review bersdarkan course_id yang dipilih ke array
        //mendapatkan total student berdasarkan course_id yang dipilih
        $review = Review::where('course_id', '=', $id)->get()->toArray();
        $reviews = Review::where('course_id', '=', $id)->get()->toArray();
        if (count($reviews) > 0) {
            $userIds = array_column($reviews, 'user_id');
            $users = getUserByIds($userIds);

            echo "<pre>".print_r($users,1)."</pre>";
            if ($users['message'] === 'user not found') {
                $reviews = [];
            } else {
                foreach($reviews as $key => $review) {
                    $userIndex = array_search($review['user_id'], array_column($users['data'], 'id'));
                    $reviews[$key]['users'] = $users['data'][$userIndex];
                }
            }
        }

        $totalStudent = MyCourse::where('course_id', '=', $id)->count();
        $totalVideos = Chapter::where('course_id', '=', $id)->withCount('lessons')->get()->toArray();
        $finalTotalVideos = array_sum(array_column($totalVideos, 'lessons_count'));

        $course['reviews'] = $reviews;
        $course['total_videos'] = $finalTotalVideos;
        $course['total_student'] = $totalStudent;

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

}
