<?php

namespace App\Http\Controllers;

use App\Helpers\ExcelGenerator;
use App\Helpers\Logger;
use App\Payment;
use App\PaymentList;
use App\SchoolClass;
use App\SchoolSession;
use App\Student;
use App\StudStatus;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

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
        return view('Activity.Student.View',['title' => 'Students','stud' =>  Student::orderByDesc('created_at')->paginate(100), 'key' => null,'s' => "data"]);
    }
    public function ViewStudentID($token){
        //dd(Student::find(decrypt($token)));
        return view('Activity.Student.View',['title' => 'Students','stud' =>  Student::where('id' ,decrypt($token))->get(),'key' => null,'s' => null]);
    }
    public function ActionStudent($token)
    {
        $action = decrypt($token);
        if($action === 1)
        {
            return view('Activity.Student.Add',['title' => 'Add Student', 'type' => 1,'adm_id' => 1,'stud' => null,'key' => null,'s' => null]);
        }

    }
    public function ActionStudentAdd($token)
    {
        $action = decrypt($token);
        //dd($action);
        if($action != null && $token != null)
        {
            return view('Activity.Payment.Add',['title' => 'Add Payment', 'pay' =>  null,'type' => 1,'adm_id' => $action]);
        }
        return redirect()->back();

    }
    public function ActionStudentEdit($id)
    {
        return view('Activity.Student.Add',['title' => 'Add Student', 'type' => 2,'stud' => Student::find(decrypt($id)),'key' => null,'s' => null]);
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
            $stud->parent_phone_number = $request->parent_phone_number;
            //dd($stud);
            $stud->save();
            $request->type == 1 ? $action = 'Added' : $action = 'Edited';
            Session::flash('success','Student ' . $action. ' Successfully');
            $this->getLogger()->LogInfo('Student Has Been ' . $action,['stud' => $stud,'by' => Auth::id()]);
            return view('Activity.Student.View',['title' => 'Students','stud' =>  Student::where('id',$stud->id)->get(), 'key' => $stud->adm_id]);
        }
        catch (\Exception $ex)
        {
            dd($ex);
        }
        return redirect()->back();
    }
    public function searchStudent(Request $request)
    {
        //dd($request->all());
        $s = StudStatus::where('name','LIKE','%'.$request->key.'%')->first();
        //dd($s);
        if($request->key != null)
        {
            $k = $request->key;
            $query = Student::query();
            $query->orwhere('id','LIKE','%'.$k.'%')
                ->orwhere('adm_id','LIKE','%'.$k.'%')
                ->orwhere('fullname','LIKE','%'.$k.'%')
                ->orwhere('gender','LIKE','%'.$k.'%')
                ->orwhere('dob','LIKE','%'.$k.'%');
                if($s != null)
                    $query->orwhere('s_id','LIKE','%'. ($s != null ? $s->id : '')  . '%');
            //dd($request->all(), $query->get());
            return view('Activity.Student.View',['title' => 'Students','stud' =>  $query->get(), 'key' => $k,'s' => true]);
        }
        return redirect()->back();
    }

    //Class
    public function ViewClass()
    {
        return view('Activity.Class.view',['title' => 'Class', 'cls' => SchoolClass::all()]);
    }
    public function ClassAction($token)
    {
        dd(decrypt($token));
    }

    public function ClassAdd(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
           'type' => 'required',
            'name' => 'required',
        ]);
        $s = new SchoolClass();
        $s->type = $request->type;
        $s->class = $request->name;
        try{
            $s->save();
            Session::flash('success','Class Added.');
            $this->getLogger()->LogInfo('Class Added',['class'=> $s,'by'=> Auth::id()]);
        }
        catch(\Exception $ex){
            Session::flash('error','Class Not Added. Please Try Again');
            $this->getLogger()->LogError('An Error Occurred When Trying To Add class',$ex,['class'=> $s,'by'=> Auth::id()]);
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

    //Session
    public function Sess()
    {
        return view('Activity.Session.View',['title' => 'Sessions','ses' => SchoolSession::orderByDesc('created_at')->get()]);
    }
    public function SessAdd(Request $request)
    {
        $sess = new SchoolSession();
        try{
            $this->validate($request,[
                'sess' => 'required|unique:school_sessions,session',
            ],['sess.required' => 'The Session Field Is Required','sess.unique' => 'Duplicate Data Error']);
            $sess->session = $request->sess;
            $sess->save();
            Session::flash('success','Session Saved Successfully');
            $this->getLogger()->LogInfo('Session Added',['session' => $sess,'by' => Auth::id()]);
            //dd($request->all());
        }
        catch(\Exception $ex)
        {
            $this->getLogger()->LogError('Session Not Saved',$ex,['session' => $sess,'by' => Auth::id()]);
            Session::flash('error','An Error Occured When Saving Data...');
        }
        return redirect()->back();


    }


    //Payment
    public function ViewPayment()
    {
        return view('Activity.Payment.Payment',['title' => 'Payment', 'pay' => Payment::orderBydesc('created_at')->get(),'key'=> null]);
    }
    public function ViewPaymentCol($col,$val)
    {
        return view('Activity.Payment.Payment',['title' => 'Payment', 'pay' => Payment::where(decrypt($col),'=', decrypt($val))->get(),'key'=>null]);
    }


    public function PaymentAction($token)
    {
        if(decrypt($token) == 1)
        {
            return view('Activity.Payment.Add',['title' => 'Add Payment', 'pay' =>  null,'type' => 1,'adm_id' => null]);
        }
    }

    public function PaymentSave(Request $request)
    {
        $action = '';
        $pay = null;
        $old_p = null;
        $this->validate($request,[
            'stud' => 'required',
            'sess' => 'required',
            'term' => 'required',
            'c_id' => 'required',
            'pl' => 'required',
            'amount' => 'required',
        ],[
            'stud.required' => 'Please Choose A Student',
            'sess.required' => 'Please Select A Session',
            'term.required' => 'Please Select A Term',
            'c_id.required' => 'Please Select A Class',
            'pl.required' => 'Please Select A Payment',
            'amount.required' => 'Amount Field Can\'t Be Empty',
        ]);

        if($request->type == 1)
        {
            $pay = new Payment();
            $pay->p_id = Payment::GeneratePaymentID();
            $action = 'Saved';
            //dd($request->all(), $pay);
        }

        if($request->type == 2)
        {
            $pay = Payment::find($request->id);
            $old_p = $pay;
            $action = 'Edited';
        }

        $pay->stud_id = $request->stud;
        $pay->sess_id = $request->sess;
        $pay->term_id = $request->term;
        $pay->c_id = $request->c_id;
        $pay->pl_id = $request->pl;
        $pay->amount = $request->amount;
        $pay->date_of_payment = $request->date_of_payment;
        try{
            //dd($pay, $action);
            $pay->save();
            Session::flash('success','Payment ' . $action . ' Successfully.');
            $this->getLogger()->LogInfo('Payment added successfuly',['pay' => $pay,'by' => Auth::id(),'oldPay' => $old_p]);
            return redirect()->action('ActivityController@ViewPayment');

        }
        catch (\Exception $ex)
        {
            $this->getLogger()->LogError('Payment Could Not Be ' . $action, $ex,['pay' => $pay,'by' => Auth::id(),'oldPay' => $old_p]);
            Session::flash('error','Payment Count Not Be ' . $action . ' At This Time. Please Try Again Later');
            return redirect()->back();
            //dd($ex);
        }


    }

    public function PaymentSearch(Request $request)
    {
        //count = 3;
        try{
            $a = explode(',', $request->key);
            $c = count($a);
            $p = Payment::query();
            //dd($a,$c);
            if(0 < $c && $a[0] != null)
            {
                $p->where('created_at','LIKE' ,'%' . $a[0] . '%');
                //dd($p->get());
            }
            if(1 < $c && $a[1] != null)
            {
                $p->where('p_id','LIKE' ,'%' . $a[1] . '%');
            }if(2 < $c && $a[2] != null)
            {
                $ss = SchoolSession::where('session','LIKE','%' . $a[2] . '%')->first();
                $p->Where('sess_id','LIKE' ,'%' . $ss->id . '%');

                //dd($p->get());
                //$s $p->where('sess_id','LIKE' ,'%' . $a[2] . '%');
            }if(3 < $c && $a[3] != null)
            {
                $p->where('term_id','LIKE' ,'%' . $a[3] . '%');
                //dd($c, $p, $p->get());
            }if(4 < $c && $a[4] != null)
            {
                $ss = SchoolClass::where('class','LIKE','%' . $a[4] . '%')->first();
                $p->where('c_id','LIKE' ,'%' . $ss->id . '%');
            }if(5 < $c && $a[5] != null)
            {
                $ss = Student::where('adm_id','LIKE','%' . $a[5] . '%')->first();
                //dd($ss, $c);
                $p->where('created_at','LIKE' ,'%' . $ss->id . '%');
            }if(6 < $c && $a[6] != null)
            {
                $ss = PaymentList::where('name','LIKE','%' . $a[6] . '%')->first();
                $p->where('pl_id','LIKE' ,'%' . $ss->id . '%');
            }if(7 < $c && $a[7] != null)
            {
                $p->where('amount','LIKE' ,'%' . $a[7] . '%');
            }
            //dd($p->get());
            return view('Activity.Payment.Payment',['title' => 'Payment', 'pay' => $p->orderByDesc('created_at')->get(),'key' => $request->key]);
        }
        catch (\Exception $ex)
        {
            Session::flash('error','An Error Occured');
            $this->getLogger()->LogError('An Error Occured when filtering payment',$ex,['key'=>$request->key]);
        }
        //if()

    }






    //// Work oN this stuff
    public function ExcelTest()
    {
        //$n = new Excel();
        $chk = false;
        $class = 16;//Class
        $sess = 2;//Session - First Part
        $term = 1;//Term - First
        $classname = SchoolClass::find($class)->class;
        $sessname = SchoolSession::find(2)->session;
        $data = Payment::query();
        $data->where(['c_id' =>  $class, 'sess_id' => $sess, 'term_id' => $term]);
        $p = $data->orderBy('stud_id','DESC')->get();
        //$ll = $data->where
        $list = PaymentList::all();
        $ids = []; $ex = [];
        $i = 1;
        //$exx = [1,2,3,4,5,6,7,8,9];
        //Get All Student Ids
        foreach ($p->all() as $d)
        {
            if(empty($ids))
            {
                array_push($ids,$d->stud_id);
                //array_push($ex, $d->stud_id);
            }
            else{
                foreach ($ids as $id)
                {
                    if($d->stud_id === $id)
                    {
                        $chk = true;
                        break;
                    }
                }
                if(!$chk)
                {
                    array_push($ids, $d->stud_id);
                    //var_dump($ids);
                }
            }

        }
        $ids = array_reverse($ids);
        $studData = [];
        //Get All Student Data
        if(!empty($ids))
        {
            //dd($ids);
            foreach($ids as $id)
            {
                $studPay = Payment::where(['c_id' =>  $class, 'sess_id' => $sess, 'term_id' => $term,'stud_id' => $id])->get();
                print_r($id);
                //$studPay = $d->where('stud_id','=',$id)->get();
                array_push($studData, [Student::find($id)->fullname => $studPay->toArray()]);
            }
            //dd($studData);

        }
       // for($i = 0; $i < count($ids);$i++)

        //dd("stop");

        Excel::create("$classname - $sessname", function($excel)
        {
            $excel->setTitle('Dat Dat Data');
            $excel->sheet('Users', function($sheet) {
                $arr = [];
                foreach (User::all() as $us)
                {
                    if($us != null)
                    {
                        $a = ['ID' => $us->id, 'Email' => $us->email, 'Password' => $us->password, 'Role' => $us->role->role ,'Access' => $us->access];
                        array_push($arr, $a);
                    }
                }
                $sheet->setTitle('My Excel File')->setStyle(['font' => ['name' => 'Andale Mono']]);
                //dd($arr);
                $sheet->fromArray($arr);
                /*$sheet->fromArray(array(
                    array('data1', 'data2'),
                    array('data3', 'data4')
                ));*/
            });
        })->export('xlsx');
        //->download('xls')
    }


    public function Excel()
    {
       try{
           //Creating Excel

           $excel=new ExcelGenerator("myXls.xls");

           if($excel==false)
               echo $excel->error;

           $myArr=array("Name","Last Name","Address","Age");
           $excel->writeLine($myArr);

           $myArr=array("Sriram","Pandit","23 mayur vihar",24);
           $excel->writeLine($myArr);

           $excel->writeRow();
           $excel->writeCol("Manoj");
           $excel->writeCol("Tiwari");
           $excel->writeCol("80 Preet Vihar");
           $excel->writeCol(24);

           $excel->writeRow();
           $excel->writeCol("Harish");
           $excel->writeCol("Chauhan");
           $excel->writeCol("115 Shyam Park Main");
           $excel->writeCol(22);

           $myArr=array("Tapan","Chauhan","1st Floor Vasundhra",25);
           $excel->writeLine($myArr);

           $excel->close();
           echo "data is write into myXls.xls Successfully.";
       }
       catch (\Exception $ex)
       {
           dd($ex);
       }
    }

    public function ddJson(Request $request)
    {
        $checkLogin = file_get_contents(storage_path() . "/students.json"); // ie: /var/www/laravel/app/storage/json/filename.json
        for ($i = 0; $i <= 31; ++$i) {
            $checkLogin = str_replace(chr($i), "", $checkLogin);
        }
        $checkLogin = str_replace(chr(127), "", $checkLogin);

        // This is the most common part
        // Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
        // here we detect it and we remove it, basically it's the first 3 characters
        if (0 === strpos(bin2hex($checkLogin), 'efbbbf')) {
            $checkLogin = substr($checkLogin, 3);
        }

        //$checkLogin = json_decode( $checkLogin );
        //print_r($checkLogin);
        $json = json_decode($checkLogin, true);
        $err = [];
        dd(count($json));
        for($i = 3; $i < count($json); $i++)
        {
            $data = $json[$i];
            try{
                $stud = null;
                $stud = new Student();
                $stud->adm_id = $data['FIELD1'] == null ? "No Adm Id": 'S-' . $data["FIELD1"];
                $stud->fullname = ucwords(strtolower($data["FIELD2"]));
                //$stud->dob = $data[2] == null ? null : Carbon::parse($data[2]);
                try{
                    $stud->dob = $data["FIELD3"] == null ? null : Carbon::createFromFormat('d/m/Y',$data["FIELD3"]);
                }
                catch (\Exception $ex)
                {
                    array_push($err, $data);
                }
                $stud->gender = $data["FIELD4"] == "M" ? "Male" : "Female";
                $stud->parent_phone_number = $data["FIELD5"];

                //dd($stud);
                $stud->save();
            }catch (\Exception $ex)
            {
                return response()->json($ex->getMessage());
            }
        }
        dd($err);
        //dd($json, json_last_error_msg());
        return response()->json([count($json),$json]);


    }
    public function ddE(Request $request)
    {
        if($request->hasFile('file'))
        {
            $data = Excel::load($request->file('file')->getRealPath(), function ($reader){})->get();
            $array = [];
            /*foreach ($data as $key => $value) {
                $arr = ['name' => $value, 'details' => $value->details];
                array_push($array,$arr);
            }*/

            Excel::load($request->file('file')->getRealPath(), function($reader) use (&$excel) {
                $objExcel = $reader->getExcel();
                $sheet = $objExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                //  Loop through each row of the worksheet in turn
                for ($row = 2; $row <= $highestRow; $row++)
                {
                    //  Read a row of data into an array
                    //var_dump($row);
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                        NULL, TRUE, FALSE);


                    $data = $rowData[0];
                    //var_dump($data);
                    try{
                        $stud = null;
                        $stud = new Student();
                        $stud->adm_id = $data[0] == null ? "No Adm Id": "P - $data[0]";
                        $stud->fullname = ucwords(strtolower($data[1]));
                        //$stud->dob = $data[2] == null ? null : Carbon::parse($data[2]);
                        try{
                            $stud->dob = $data[2] == null ? null : Carbon::createFromFormat('dd/mm/yyyy',$data[2]);
                        }
                        catch (\Exception $ex)
                        {
                            var_dump($ex->getMessage(), $data);
                            try{
                                $stud->dob = $data[2] == null ? null : Carbon::createFromFormat('dd/mm/yyyy',$data[2]);
                            }catch (\Exception $ex)
                            {

                                var_dump($ex->getMessage());
                            }
                        }
                        $stud->gender = $data[3] === "M" ? "Male" : "Female";
                        $stud->parent_phone_number = $data[4];
                        $stud->save();
                        //var_dump($stud);
                    }
                    catch (\Exception $ex)
                    {
                        Log::info($ex,["Line" => $row]);
                        //var_dump($stud, $data);
                        dd('stop');
                    }



                    $excel[] = $rowData[0];
                }
            });


            $res = $array;
            //return response()->json($data);
            //foreach ($)
            return response()->json($excel);
        }
        //Excel::load(file_get_contents(storage_path('file.xls')), function ($reader){})->dd();
        //dd($file);
    }
}
