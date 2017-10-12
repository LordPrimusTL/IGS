<?php

namespace App\Http\Controllers;

use App\Helpers\Logger;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ActivityController extends Controller
{
    private function getLogger()
    {
        return new Logger();
    }


    //User
    public function ViewUsers()
    {
        return view('Activity.User.View',['title'=> 'Users','users' => User::where('role_id', '>', 1) ->get()]);
    }
    public function AddUser()
    {
        return view('Activity.User.Add',['title' => 'Add User', 'type' => 1,'user' => null]);
    }
    public function EditUser($token)
    {
        return view('Activity.User.Add',['title' => 'Edit User', 'type' => 2,'user' => User::find(decrypt($token))]);
    }
    public function SaveUser(Request $request)
    {

        $user = null;
        if($request->type == 1)
        {
            $this->validate($request,[
                'email' => 'required|unique:users,email',
                'password' => 'required',
                'role' => 'required',
                'access' => 'required',
            ]);

            $user = new User();
            //dd($request->all());
        }

        if($request->type == 2)
        {
            $this->validate($request,[
                'email' => 'required',
                'role' => 'required',
                'access' => 'required',
            ]);
            $user = User::FindUserByEmail($request->email);
        }

        $user->email = $request->email;
        $user->password = $request->password != null ? Hash::make($request->password) : $user->password;
        $user->role_id = $request->role;
        $user->access = (bool)$request->access;
        try{
            $user->save();
            $request->type == 1 ? $action = 'Added' : $action = 'Edited';
            Session::flash('success','User Saved Successfully');
            $this->getLogger()->LogInfo('User ' . $action .' Successfully',['AddedUser' => $user, 'by' => Auth::id()]);
            return redirect()->action('ActivityController@ViewUsers');
        }
        catch(\Exception $ex)
        {
            Session::flash('error','An Error Occurred When saving User');
            $this->getLogger()->LogError('An Error Occurred When saving User.', $ex, ['user' => $user, 'by' => Auth::id()]);
            return redirect()->back();
        }
    }
    public function RevokeUser($token)
    {
        try{
            $user = User::FindUserByEmail(decrypt($token));
            if($user->access)
            {
                $user->access = false;
                Session::flash('success', $user->email . ' Access Has Been Revoked');
            }
            else{
                $user->access = true;
                Session::flash('success', $user->email . ' Access Has Been Granted');
            }
            $user->save();
            $this->getLogger()->LogInfo('Access Revoked',['user' => $user,'by' => Auth::id()]);
            //dd($user);
        }
        catch (\Exception $ex)
        {
            Session::flash('error','Unable To Revoke Access. Please Try Again');
            $this->getLogger()->LogError('Unable To Revoke Access', $ex, ['$user' => decrypt($token),'by' => Auth::id()]);
        }
        return redirect()->back();
        dd($token);
    }
    public function DeleteUser($token){
        $u = null;
        try{
            $user = User::find(decrypt($token));
            $u = $user;
            $user->delete();
            //$user->save();
            Session::flash('success',$u->email .' has been deleted successfully.');
            $this->getLogger()->LogInfo('User Has Been Deleted',['user' => $u,'by' => Auth::id()]);
        }
        catch (\Exception $ex)
        {
            Session::flash('error','An Error Occured.');
            $this->getLogger()->LogError('Unable To Delete User',$ex,['user' => $u,'by' => Auth::id()]);
        }
        return redirect()->back();
    }

    //Student
    public function ViewStudent(){}

}
