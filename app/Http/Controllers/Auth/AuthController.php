<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthToken;
use App\Models\portfolioManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;
use App\Models\InvestCompany;
use App\Models\ClientSharepointSyncheFile;
use App\Models\ClientSharepointSynch;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public $rootfolder, $datafolder, $datafile;

    public function __construct()
    {
        $this->rootfolder = 'Douglas_User_Data';
        $this->datafolder = 'Folders';
        $this->datafile = 'Files';
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {
            $user = User::with('roles')->where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    "status" => false,
                    "errors" => ["Invalid credentials"]
                ]);
            } else {
                if ($user['roles'][0]['title'] != 'Client') {
                    return response()->json([
                        "status" => false,
                        "errors" => ["Only Clients can access this system"]
                    ]);
                } else {
                    // if (Auth::attempt($request->only(["email", "password"]))) {
                    if (\Hash::check($request->input('password'), $user->password) == true) {
                        Session::put('User', $user);
                        return response()->json([
                            "status" => true,
                            "redirect" => url("dashboard")
                        ]);
                    } else {
                        return response()->json([
                            "status" => false,
                            "errors" => ["Invalid credentials"]
                        ]);
                    }
                }
            }
        }
    }


    public function getSaVerifyLogin($id)
    {
        return view('salogin', compact('id'));
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postsaLogin(Request $request)
    {

        $user = User::with('roles')->where('id', base64_decode($request->user_id))->where('sa_id', $request->sa_id)->first();
        if (!$user) {
            return response()->json([
                "status" => false,
                "errors" => ["Invalid SA ID!"]
            ]);
        } else {
            Session::put('User', $user);
            return response()->json([
                "status" => true,
                "redirect" => url("dashboard")
            ]);
        }
    }

    public function portfolioLogin(Request $request)
    {

        $user = User::with('roles')->where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                "status" => false,
                "errors" => ["Invalid credentials"]
            ]);
        } else {
            if ($user['roles'][0]['title'] != 'Portfolio Manager') {
                return response()->json([
                    "status" => false,
                    "errors" => ["Only portfolio manager can access this system"]
                ]);
            } else {

                if ($user->sa_id == $request->sa_id) {
                    return response()->json([
                        "status" => false,
                        "errors" => ["Invalid SA ID!"]
                    ]);
                } else {
                    Session::put('User', $user);
                    return response()->json([
                        "status" => true,
                        "redirect" => url("dashboard")
                    ]);
                }
            }
        }
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        $updated_at = '';
        if (Session::get('User')) {
            $manager_data = portfolioManager::where('id', Session::get('User')->manager_id)->first();
            if (!$manager_data) {
                $manager_data = portfolioManager::where('id', Session::get('User')->id)->first();
            }
            $sync_date = ClientSharepointSynch::where('user_id', Session::get('User')->id)->value('updated_at');
            if ($sync_date) {
                $updated_at = date('d-M-Y', strtotime($sync_date));
            } else {
                $updated_at = date('d-M-Y', strtotime(Session::get('User')->updated_at));
            }
            $invest_company = InvestCompany::with(['usercompany' => fn ($query) => $query->where('client_code', Session::get('User')->client_code)])->get();
            return view('client.dashboard', compact('invest_company', 'manager_data', 'updated_at'));
        }
        return redirect("/")->withSuccess('Opps! You do not have access');
    }

    public function getalldata($id)
    {
        $flage = 0;
        if ($id == 2 || $id == 10) {
            $flage = 1;
        }
        if (Session::get('User')) {
            $manager_data = portfolioManager::where('id', Session::get('User')->manager_id)->first();
            if (!$manager_data) {
                $manager_data = portfolioManager::where('id', Session::get('User')->id)->first();
            }
            $sync_date = ClientSharepointSynch::where('user_id', Session::get('User')->id)->value('updated_at');
            if ($sync_date) {
                $updated_at = date('d-M-Y', strtotime($sync_date));
            } else {
                $updated_at = date('d-M-Y', strtotime(Session::get('User')->updated_at));
            }
            $company_details = InvestCompany::where('id', $id)->first();
            //$sharepoint_files = ClientSharepointSyncheFile::where('invest_companie_id',$id)->where('client_code',Auth::user()->client_code)->get();
            return view('client.sharepoint_files', compact('company_details', 'manager_data', 'updated_at','flage'));
        }
        return redirect("/")->withSuccess('Opps! You do not have access');
    }


    public function sharepointData(Request $request)
    {
        $token = '';
        $sharepoint_files = [];
        $resp = $this->guzzle_index($this->rootfolder, $this->datafolder);
        if ($resp['statusCode'] == 401) {
            $get_token = Controller::generate_token();
            AuthToken::where('id', 1)->update(['sharepoint_auth_token' => $get_token]);
        }
        $filter_date = str_replace(",", "", $request->date);
        $token = AuthToken::where('id', 1)->value('sharepoint_auth_token');
        if($request->flage==0){
        $sharepoint_files = ClientSharepointSyncheFile::where('invest_companie_id', $request->cid)->where('file_date', 'LIKE', '%' . $filter_date . '%')->where('client_code', Session::get('User')->client_code)->orderBy('data_file', 'ASC')->get();
        }else{
        $sharepoint_files = ClientSharepointSyncheFile::where('invest_companie_id', $request->cid)->where('financial_year',0)->where('client_code', Session::get('User')->client_code)->orderBy('data_file', 'ASC')->get();
        }
        return response()->json([
            "status" => count($sharepoint_files) >= 1 ? true : false,
            "data" => $sharepoint_files,
            "token" => $token
        ]);
    }


    public function getDashboard($userId)
    {
        //Auth::loginUsingId(base64_decode($userId));
        return redirect('/sa-id-verification' . '/' . $userId);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout()
    {
        Session::forget('User');
        return Redirect('/');
    }

    public function adminlogout()
    {
        Auth::logout();
        return Redirect('/login');
    }
}
