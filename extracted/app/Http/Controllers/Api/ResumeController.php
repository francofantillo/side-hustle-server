<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\ResumeHobbies;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class ResumeController extends Controller
{
    public function getResume(Request $request)
    {
        $userid = Auth::user()->id;
        $resume = Resume::with('hobbies')->where('user_id', $userid)->first();
        if ($resume != null) {
            return $this->success($resume);
        } else {
            return $this->success(null);
        }
    }

    protected function resumeFile($request) {
        $dirPath  = "uploads/files/resumes/";
        $fileName = $dirPath.time().'-'.$request->file->getClientOriginalName();
        $request->file->move(public_path($dirPath), $fileName);

        // $pdftext = file_get_contents($request->file);
        // $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);

        return $request->file->getClientOriginalName();
    }

    public function updateResume(Request $request) {

        $userid = auth()->user()->id;

        if (!User::find($userid)?->first()) return $this->error('User not found', 404, [], $validator->errors());
        $resume = Resume::where('user_id', $userid)->first();

        if ($resume == null) {

            $resume = new Resume();
            $resume->user_id                 = $userid;
            $resume->actual_name             = $request->actual_name;
            $resume->profession              = $request->profession;
            $resume->nick_name               = $request->nick_name;
            $resume->family_ties             = $request->family_ties;
            $resume->professional_background = $request->professional_background;
            $resume->favourite_quote         = $request->favourite_quote;
            $resume->description             = $request->description;

            if ($request->has('file')) {

                $get_bytes = $request->file('file')->getSize();
                $size_kb   = round($get_bytes/1024);
                $size_mb   = round($get_bytes/1048576, 1);

                $dirPath  = "uploads/files/resumes/";
                $fileName = $dirPath.time().'-'.$request->file->getClientOriginalName();
                $request->file->move(public_path($dirPath), $fileName);
        
                $resume->file     = asset($fileName);
                $resume->filename = $request->file->getClientOriginalName();
                if($size_mb >= 1) {
                    $resume->file_size= $size_mb.' MB';
                } else {
                    $resume->file_size= $size_kb.' KB';
                }
            } 
            
            if ($request->has('profile_image')) {
                $dirPath  = "uploads/files/resumes/";
                $fileName = $dirPath.time().'-'.$request->profile_image->getClientOriginalName();
                $request->profile_image->move(public_path($dirPath), $fileName);

                $resume->profile_image = asset($fileName);
            }
            $resume->save();

            if ($request->has('hobbies')) {
                $hobbies = explode(',', $request->hobbies);
                $arr     = [];
                foreach ($hobbies as $hobby) {
                    $hobbies = ResumeHobbies::create([
                        "resume_id" => $resume->id,
                        "hobby"     => $hobby,
                    ]);
                }
            } else {
                $resume->hobbies()->delete();   
            }
            $resume = Resume::with('hobbies')->where('user_id', $userid)->first();

            $arr["id"]                      = $resume->id;
            $arr["user_id"]                 = $resume->user_id;
            $arr["actual_name"]             = $resume->actual_name;
            $arr["nick_name"]               = $resume->nick_name;
            $arr["profession"]              = $resume->profession;
            $arr["family_ties"]             = $resume->family_ties;
            $arr["professional_background"] = $resume->professional_background;
            $arr["favourite_quote"]         = $resume->favourite_quote;
            $arr["description"]             = $resume->description;
            $arr["filename"]                = $resume->filename;
            $arr["file_size"]               = $resume->file_size;
            $arr["file"]                    = $resume->file;
            $arr["profile_image"]           = $resume->profile_image;
           
            if(count($resume->hobbies) > 0) {
                $arr["hobbies"] = $resume->hobbies;
            } else {
                $arr["hobbies"] = null;
            }

            return $this->success($resume, 'Resume created successfully');

        } else {

            $resume              = Resume::find($resume->id);
            $resume->actual_name = $request->actual_name;
            $resume->profession  = $request->profession;
            $resume->nick_name   = $request->nick_name;
            $resume->family_ties = $request->family_ties;
            $resume->professional_background = $request->professional_background;
            $resume->favourite_quote = $request->favourite_quote;
            $resume->description     = $request->description;

            if ($request->has('file')) {

                $get_bytes = $request->file('file')->getSize();
                $size_kb   = round($get_bytes/1024);
                $size_mb   = round($get_bytes/1048576, 1);
 
                $dirPath  = "uploads/files/resumes/";
                $fileName = $dirPath.time().'-'.$request->file->getClientOriginalName();
                $request->file->move(public_path($dirPath), $fileName);
        
                $resume->filename = $request->file->getClientOriginalName();
                $resume->file     = asset($fileName);
                if($size_mb >= 1) {
                    $resume->file_size= $size_mb.' MB';
                } else {
                    $resume->file_size= $size_kb.' KB';
                }
                
            } 
            if ($request->has('profile_image')) {
                $dirPath  = "uploads/files/resumes/";
                $fileName = $dirPath.time().'-'.$request->profile_image->getClientOriginalName();
                $request->profile_image->move(public_path($dirPath), $fileName);
                $resume->profile_image = asset($fileName);
            } 
            $resume->save();

            if ($request->hobbies != "" || $request->hobbies != null) {
                $hobbies = explode(',', $request->hobbies);
                $resume->hobbies()->delete();
                foreach ($hobbies as $hobby) {
                    $resume->hobbies()->updateOrCreate(['hobby' => $hobby]);
                }
            } else {
                $resume->hobbies()->delete();   
            }

            $resume = Resume::with('hobbies')->where('user_id', $userid)->first();

            $arr["id"]                      = $resume->id;
            $arr["user_id"]                 = $resume->user_id;
            $arr["actual_name"]             = $resume->actual_name;
            $arr["nick_name"]               = $resume->nick_name;
            $arr["profession"]              = $resume->profession;
            $arr["family_ties"]             = $resume->family_ties;
            $arr["professional_background"] = $resume->professional_background;
            $arr["favourite_quote"]         = $resume->favourite_quote;
            $arr["description"]             = $resume->description;
            $arr["filename"]                = $resume->filename;
            $arr["file_size"]               = $resume->file_size;
            $arr["file"]                    = $resume->file;
            $arr["profile_image"]           = $resume->profile_image;
           
            if(count($resume->hobbies) > 0) {
                $arr["hobbies"] = $resume->hobbies;
            } else {
                $arr["hobbies"] = null;
            }

            return $this->success($arr, 'Resume updated successfully');

        }

       
    }

    public function deleteResume() {

        try {

            $userid = Auth::user()->id;
            $resume = Resume::where('user_id', $userid)->first();

            if($resume != null) {
                $resume->file      = null;
                $resume->filename  = null;
                $resume->file_size = null;
                $resume->save();
            }
            return $this->success($resume, "Resume deleted");

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    // public function updateResume(Request $request) {

    //     // $validator  = Validator::make($request->all(), [
    //     //     "user_id" => "required",
    //     // ]);
    //     // if ($validator->fails()){
    //     //     return $this->error('Validation Error', 200, [], $validator->errors());
    //     // }
    //     $userid = auth()->user()->id;

    //     if (!User::find($userid)?->first()) return $this->error('User not found', 404, [], $validator->errors());
    //     $resume = Resume::where('user_id', $userid)->first();
    //     $data = $request->only([
    //         'family_ties',
    //         'professional_background',
    //         'favourite_quote',
    //         'description',
    //         'actual_name',
    //         'nick_name'
           
    //     ]);
    //     $data['user_id'] = $userid;
    //     if ($resume == null) {
    //         if ($request->has('file')) {
    //             $data['file'] = $this->resumeFile($request);
    //         }
    //         if ($request->has('profile_image')) {
    //             $dirPath  = "uploads/files/resumes/";
    //             $fileName = $dirPath.time().'-'.$request->profile_image->getClientOriginalName();
    //             $request->profile_image->move(public_path($dirPath), $fileName);
    //             $data['profile_image'] = asset($fileName);
    //         }

    //         $resumeCreate = Resume::create($data);
    //         if ($request->has('hobbies')) {
    //             $hobbies = explode(',', $request->hobbies);
    //             $arr = [];
    //             foreach ($hobbies as $hobby) {
    //                 $arr[] = ['hobby' => $hobby];
    //             }
    //             $resumeCreate->hobbies()->createMany($arr);
    //         }
    //     } else {
    //         foreach ($data as $key => $val) {
    //             if (isset($key)) {
    //                 $resume->$key = $val;
    //             }
    //         }
    //         if ($request->has('file')) {
    //             $resume->file = $this->resumeFile($request);
    //         }
    //         if ($request->has('profile_image')) {
    //             $dirPath  = "uploads/files/resumes/";
    //             $fileName = $dirPath.time().'-'.$request->profile_image->getClientOriginalName();
    //             $request->profile_image->move(public_path($dirPath), $fileName);
    //             $data['profile_image'] = asset($fileName);
    //         }
    //         if ($request->has('hobbies')) {
    //             $hobbies = explode(',', $request->hobbies);
    //             $resume->hobbies()->delete();
    //             foreach ($hobbies as $hobby) {
    //                 $resume->hobbies()->updateOrCreate(['hobby' => $hobby]);
    //             }
    //         }
    //         $resume->save();
    //     }
    //     return $this->success([], 'Resume updated successfully');
    // }
}
