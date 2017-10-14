<?php

namespace App\Http\Controllers;

use App\Helpers\Logger;
use App\PaymentList;
use App\Student;
use App\StudStatus;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        return view('Activity.User.View',['title'=> 'Users','users' => User::where('role_id', '>', 1)->orderByDesc('created_at') ->get()]);
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
    public function ViewStudent(){
        return view('Activity.Student.View',['title' => 'Students','stud' =>  Student::orderByDesc('created_at')->get()]);
    }

    public function ActionStudent($token, $id)
    {
        $action = decrypt($token);
        if($action === 1)
        {
            return view('Activity.Student.Add',['title' => 'Add Student', 'type' => 1,'stud' => null]);
        }

    }

    public function ActionStudentEdit($id)
    {
        return view('Activity.Student.Add',['title' => 'Add Student', 'type' => 2,'stud' => Student::find(decrypt($id))]);
    }

    public function ActionStudentDelete($id)
    {
        $s = null;
        try{
            $stud = Student::find(decrypt($id));
            $s = $stud;
            $stud->delete();
            Session::flash('success','Student Has Been Deleted');
            $this->getLogger()->LogInfo('Student Record deleted',['stud' => $s->id,'by' =>  Auth::id()]);
        }
        catch (\Exception $ex)
        {
            Session::flash('error','Unable TO Delete Student At This Time. Please Try Again');
            $this->getLogger()->LogError('unable to delete student', $ex, ['stud' => $s->id, 'by' => Auth::id()]);
        }
        return redirect()->back();
    }

    public function saveStudent(Request $request)
    {
        try{
            $this->validate($request,[
                'adm_id' => 'required',
                'fullname' => 'required',
                'dob' => 'required',
            ],['adm_id.required' => 'Admission ID Field Is Required']);
            $dob = null;
            try{
                $dob = Carbon::parse($request->dob);
                //dd($dob->toDateString());
            }
            catch(\Exception $ex)
            {
                Session::flash('error','Incorrect Date Format');
                return redirect()->back();
            }
            $stud = null;
            if($request->type == 1)
            {
                $stud = new Student();
            }

            if($request->type == 2)
            {
                $stud = Student::find($request->id);
            }

            $stud->fullname = $request->fullname;
            $stud->gender = $request->gender;
            $stud->dob = $request->dob;
            $stud->adm_id = $request->adm_id;
            $stud->s_id = $request->status;
            $stud->save();
            $request->type == 1 ? $action = 'Added' : $action = 'Edited';
            Session::flash('success','Student ' . $action. ' Successfully');
            $this->getLogger()->LogInfo('Student Has Been ' . $action,['stud' => $stud,'by' => Auth::id()]);
            return redirect()->action('ActivityController@ViewStudent');
        }
        catch (\Exception $ex)
        {
            dd($ex);
        }
        return redirect()->back();
    }


    //Payment List
    public function PaymentList()
    {
        return view('Activity.Payment.List',['title' =>'Payment List','list' => PaymentList::orderByDesc('created_at')->get()]);
    }

    public function AddPaymentList(Request $request)
    {
        $this->validate($request,[
           'payname' => 'required|unique:payment_lists,name'
        ],['payname.required' => 'Payment Name Is Required',
            'payname.unique' => 'This Payment already Exists']);

        try{
            $l = new PaymentList();
            $l->name = $request->payname;
            $l->save();
            Session::flash('success','Payment Added To List.');
            $this->getLogger()->LogInfo('Payment Added To List',['List' => $l,'by' => Auth::id()]);
            //dd($request->all());
        }
        catch (\Exception $ex)
        {
            Session::flash('error','Oops An Error Occured. Please Try Again.');
            $this->getLogger()->LogError('An error Occured When Adding Payment List', $ex, ['List' => $l]);
        }

        return redirect()->back();
    }

    public function DeletePayList($token)
    {
        $s = null;
        try{
            $list = PaymentList::find(decrypt($token));
            $l = $list;
            $list->delete();
            Session::flash('success','Payment Has Been Removed From The List');
            $this->getLogger()->LogInfo('Payment Record deleted',['stud' => $l->id,'by' =>  Auth::id()]);
        }
        catch (\Exception $ex)
        {
            Session::flash('error','Unable TO Delete Payment At This Time. Please Try Again');
            $this->getLogger()->LogError('unable to delete payment', $ex, ['stud' => $l->id, 'by' => Auth::id()]);
        }
        return redirect()->back();
    }

}
