<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\AlternativeUser;
use App\Models\SendTestMailToAllUser;
use App\Models\ClientSharepointSynch;
use App\Models\ClientSharepointSyncheCompany;
use App\Models\ClientSharepointSyncheYear;
use App\Models\ClientSharepointSyncheFile;
use App\Models\AuthToken;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendTestMail;
use App\Models\InvestCompany;
use App\Models\portfolioManager;
use DB;
use App\Models\User;
use Exception;
use Gate;
use Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{

    public $rootfolder, $datafolder, $datafile;

    public function __construct()
    {
        //$this->rootfolder = 'Douglas_User_Data';
        $this->rootfolder = 'Clients%20&%20Family/+OneX';
        $this->datafolder = 'Folders';
        $this->datafile = 'Files';
    }

    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $users = User::with(['getSharePointSyncCompany', 'roles', 'manager'])->get();
     
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');
       // $porfoliouser = DB::table('role_user')->where('role_id', 4)->pluck('user_id')->toArray();
       // $managers = User::whereIn('id', $porfoliouser)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $managers =portfolioManager::pluck('name', 'id');
        return view('admin.users.create', compact('managers', 'roles'));
    }

    public function store(StoreUserRequest $request)
    {

        if ($request->hasFile('profile_images')) {
            $fileName = time() . '.' . $request->profile_images->extension();
            $request->profile_images->move(public_path('profile_pics'), $fileName);
            $request->merge(['profile_image' => $fileName]);
        }
        $user = User::create($request->all());
        foreach ($request->a_name as $key => $rw) {
            if ($rw != '') {
                AlternativeUser::create([
                    'user_id' => $user->id,
                    'a_name' => $rw,
                    'a_email' => $request['a_email'][$key]
                ]);
            }
        }
        $user->roles()->sync([2]);
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Session::put('aid', $user->id);
        $roles = Role::pluck('title', 'id');
        $alternative_users = AlternativeUser::where(['user_id' => $user->id])->get();
        // $porfoliouser = DB::table('role_user')->where('role_id', 4)->pluck('user_id')->toArray();
        // $managers = User::whereIn('id', $porfoliouser)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $managers =portfolioManager::pluck('name', 'id');
        $user->load('roles', 'manager');
        return view('admin.users.edit', compact('managers', 'roles', 'user', 'alternative_users'));
    }

    public function getCompany($id)
    {
        $companyfolders = [];
        $user_folder = ClientSharepointSynch::where('client_code', $id)->where('flage', 0)->first();
        if ($user_folder) {
            $companyfolders = ClientSharepointSyncheCompany::with('getSharePointSyncCompanyYear')->where('client_code', $user_folder->client_code)->get();
        }
        $username = User::where('client_code',$id)->value('name');
        return view('admin.users.company', compact('companyfolders','username'));
    }

    public function getCompanyYear($usercode, $companyid)
    {
        $yearfolders = [];
        $username = User::where('client_code',$usercode)->value('name');
        $companyname = InvestCompany::where('id',$companyid)->value('investment_company');
        $yearfolders = ClientSharepointSyncheYear::with('getSharePointSyncCompanyFile')->where('client_code', $usercode)->where('invest_companie_id', $companyid)->get();
        return view('admin.users.year', compact('yearfolders', 'usercode','companyname','username'));
    }

    public function getCompanyYearFile($usercode, $companyid, $yearid)
    {
        $token = '';
        $sharepoint_files = [];
        $resp = $this->guzzle_index($this->rootfolder, $this->datafolder);
        if ($resp['statusCode'] == 401) {
            $get_token = Controller::generate_token();
            AuthToken::where('id', 1)->update(['sharepoint_auth_token' => $get_token]);
        }
        $token = AuthToken::where('id', 1)->value('sharepoint_auth_token');
        $folder_files = ClientSharepointSyncheFile::where('client_code', $usercode)->where('invest_companie_id', $companyid)->where('financial_year', $yearid)->get();
        $username = User::where('client_code',$usercode)->value('name');
        $companyname = InvestCompany::where('id',$companyid)->value('investment_company');
        return view('admin.users.document_file', compact('folder_files','username','companyname','yearid','token'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        Session::forget('aid');
        if ($request->hasFile('profile_images')) {
            $fileName = time() . '.' . $request->profile_images->extension();
            $request->profile_images->move(public_path('profile_pics'), $fileName);
            $user->update(['profile_image' => $fileName]);
        }
        $user->update($request->all());
        AlternativeUser::where('user_id', $user->id)->delete();
        if (count($request->a_name) >= 1 && count($request->a_email) >= 1) {
            foreach ($request->a_name as $key => $rw) {
                if ($rw != '') {
                    AlternativeUser::create([
                        'user_id' => $user->id,
                        'a_name' => $rw,
                        'a_email' => $request['a_email'][$key]
                    ]);
                }
            }
        }
        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles', 'manager');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        AlternativeUser::where('user_id', $user->id)->delete();
        ClientSharepointSynch::where('user_id', $user->id)->update([
            'flage' => 1
        ]);
        $user->delete();
        return back();
    }

    public function syncUser(Request $request)
    {
        $user = User::where('id', $request->userid)->first();
        if ($user) {
            return $this->synch_data_by_user_id($user);
        }
        //return $user;
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
            AlternativeUser::where('user_id', $user)->delete();
            ClientSharepointSynch::where('user_id', $user)->update([
                'flage' => 1
            ]);
        }
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function testMailPage()
    {
        $users = User::with('getSharePointSyncCompany')->get();
        return view('admin.testmail.index', compact('users'));
    }

    public function sendtestmail(Request $request)
    {
        $user = User::where('id', $request->userid)->first();
        if ($user) {
            $sendTestMailData = [
                'name' => $user->name,
                'title' => '',
                'body' => 'Your Dashboard has been updated.',
                'replytoemail'=>env('MAIL_FROM_ADDRESS'),
                'replytoname'=>env('MAIL_FROM_NAME'),
                'userid' => $user->id,
                'link' => url('/sa-id-verification') . '/' . base64_encode($user->id)
            ];
            
            try {
                Mail::to($request->test_email)->send(new SendTestMail($sendTestMailData));
            
                return redirect()->back()->with('success', 'Mail sent successfully!');
            } catch (Exception $ex) {
                // Debug via $ex->getMessage();
                return redirect()->back()->with('success', 'error!');
            }
        }
        return redirect()->back()->with('success', 'Mail sent successfully!');
    }

    public function sendMailToUser(Request $request)
    { 
        $users = User::where('id',$request->userid)->with('getSharePointSyncCompany','manager')->get();

        //dd($users);
    // get manager data

        foreach ($users as $us) {
            if ($us->getSharePointSyncCompany != null) {
                $alternative_user = AlternativeUser::where('user_id', $us->id)->get();
                if (count($alternative_user) >= 1) {
                    foreach ($alternative_user as $auser){
                        $sendTestMailData = [
                            'name' => $us->name, //$auser->a_name,
                            'title' => 'Your Dashboard has been updated.',
                            'replytoemail'=>$us->manager->email,
                            'replytoname'=>$us->manager->name,
                            'body' => '',
                            'userid' => base64_decode($auser->user_id),
                            'link' => url('/sa-id-verification') . '/' . base64_encode($auser->user_id)
                        ];
                       // dd( $sendTestMailData);
                        try {
                            Mail::to($auser->a_email)->send(new SendTestMail($sendTestMailData));
                        } catch (Exception $ex) {
                            echo $ex->getMessage();
                        }
                    }
                }
                $sendTestMailData = [
                    'name' => $us->name,
                    'title' => 'Your Dashboard has been updated.',
                    'body' => '',
                    'replytoemail'=>$us->manager->email,
                    'replytoname'=>$us->manager->name,
                    'userid' => base64_decode($us->id),
                    'link' => url('/sa-id-verification') . '/' . base64_encode($us->id)
                ];
                try {
                    Mail::to($us->email)->send(new SendTestMail($sendTestMailData));
                } catch (Exception $ex) {}
                return response()->json(['success' => true, 'data' => []]);
            }else{
                return response()->json(['success' => false, 'data' => []]);
            }
        }
    }

    public function sendMailToAllUser()
    {
        $users = User::with('getSharePointSyncCompany','manager')->get();

        foreach ($users as $us) {       

            if ($us->getSharePointSyncCompany != null) {    
                $alternative_user = AlternativeUser::where('user_id', $us->id)->get();
              
                if (count($alternative_user) >= 1) {
                    foreach ($alternative_user as $auser)
                        SendTestMailToAllUser::updateOrCreate([
                            'user_name' => $auser->a_name,
                            'user_email' => $auser->a_email,
                            'replyto_name'=>$us->manager->name,
                            'replyto_email'=>$us->manager->email,
                            'user_id' => base64_encode($auser->user_id),
                            
                        ]);
                }
                SendTestMailToAllUser::updateOrCreate([
                    'user_name' => $us->name,
                    'user_email' => $us->email,
                    'replyto_name'=>$us->manager->name,
                    'replyto_email'=>$us->manager->email,
                    'user_id' => base64_encode($us->id),
                ]);
            }
        }

       /* $sendall = SendTestMailToAllUser::where('emailed', 0)->get();
        foreach ($sendall as $rw) {
            $sendTestMailData = [
                'name' => $rw->user_name,
                'title' => 'This month data synch is completed.',
                'body' => 'To access your dashboard and view your most recent correspondence from Douglas Investments, please follow the link below and enter your password when prompted.',
                'userid' => base64_decode($rw->user_id),
                'link' => url('/go-to-dashbaord') . '/' . $rw->user_id
            ];
            try {
                Mail::to($rw->user_email)->send(new SendTestMail($sendTestMailData));
                SendTestMailToAllUser::where('id', $rw->id)->update([
                    'emailed' => 1
                ]);
            } catch (Exception $ex) {}
        }*/
        SendTestMailToAllUser::where('emailed', 1)->update([
            'emailed' => 0
        ]);
        return response()->json(['success' => true, 'data' => []]);
    }
}
