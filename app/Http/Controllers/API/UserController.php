<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('isAdmin');
                if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            return User::latest()->paginate(10);
            // return User::latest()->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:25',
            'email' => 'required|unique:users',
            'type' => 'required',
            'password' => 'sometimes|min:6',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = $request->type;
        $user->bio = $request->bio;
        $user->password = Hash::make($request->password);
        $user->save();
        return $user;
    }

    public function profile()
    {
        return auth('api')->user();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $user = User::find($id);

        $request->validate([
            'name' => 'required|string|max:25',
            'email' => 'required|unique:users,email,'.$user->id,
            'type' => 'required',
            'password' => 'sometimes|min:6',
        ]);

        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = $request->type;
        $user->bio = $request->bio;
        $user->password = Hash::make($request->password);
        $user->save();
        return $user;
    }

    public function updateProfile(Request $request){
        $user = auth('api')->user();


        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($request->photo != $user->photo) {
            $strpos = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $strpos);
            $ex = explode('/', $sub)[1];
            $name = time().'.'.$ex;
            $img = Image::make($request->photo)->resize(512,512);
            $path = public_path(). '/admin/image/user/';
            $image = $path.$user->photo;
            $img->save($path.$name);

            if (file_exists($image)) {
            @unlink($image);
            }
        }else{
            $name = $user->photo;
        }

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = $request->type;
        $user->bio = $request->bio;
        $user->education = $request->education;
        $user->location = $request->location;
        $user->photo = $name;
        $user->save();
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);
        $img_path = public_path(). '/admin/image/user/';
        $image = $img_path.$user->photo;
        if (file_exists($image)) {
        @unlink($image);
        }
        $user->delete();
        return $user;
    }

    public function destroyAll($id)
    {
        $this->authorize('isAdmin');
        $allId = explode(",",$id);
        foreach ($allId as $id) {
            $user = User::find($id);
            $user->delete();
        }

    }
}
