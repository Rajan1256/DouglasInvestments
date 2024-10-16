<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\SyncMail;
use App\Models\ClientSharepointSynch;
use App\Models\ClientSharepointLogNotification;
use App\Models\ClientDbUserSharepointLogNotifications;
use App\Models\ClientSharepointSyncheCompany;
use App\Models\ClientSharepointSyncheCompanyLog;
use App\Models\ClientSharepointSyncheYear;
use App\Models\ClientSharepointSyncheYearLog;
use App\Models\ClientSharepointSyncheFile;
use App\Models\ClientSharepointSyncheFileLog;
use App\Models\InvestCompany;
use App\Models\User;
use App\Models\AuthToken;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $rootfolder, $datafolder, $datafile;
    public function __construct()
    {
        //$this->rootfolder = 'Douglas_User_Data';
        //$this->rootfolder = 'Clients%20&%20Family/+OneX';
        $this->rootfolder = env('ROOT_FOLDER_PATH');
        $this->datafolder = 'Folders';
        $this->datafile = 'Files';
    }

    public static function getHttpHeaders()
    {
        $authtoken = AuthToken::where('id', 1)->first();
        $headers    =   [
            'headers' => [
                'Accept' => 'application/json; odata=nometadata',
                'Authorization' => 'Bearer ' . $authtoken->sharepoint_auth_token,
            ],
            'http_errors' => false,
        ];
        return $headers;
    }

    public static function getHttpBody()
    {
        return [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => 'c2def949-b103-419e-98aa-8246f9d8767e@f0581217-861f-4d3d-bddf-5e880e03939f',
                'client_secret' => 'PCYAxez1j5EvlQqyHqlQt8acvR0B2DCYon30KOmFD58=',
                'resource' => '00000003-0000-0ff1-ce00-000000000000/douglasinvestmentsza.sharepoint.com@f0581217-861f-4d3d-bddf-5e880e03939f',
                'refresh_token' => 'PAQABAAEAAAAmoFfGtYxvRrNriQdPKIZ-f51zIReqC7s5YDF5wXNxNtw0ZKXMQ4yvieLS9pAnbUJXnF0um5eCmftS1BeyfKnPoRn0s1Lz8GKNKvC-7Kt50qmYOZJrcG3B3GGtOaPtBcOJKk310YH3nqNtSJajIt_O_6EkLM5eVB2tiDfjKgGFSacqQAbfBlbU8HQN2ExhMsvgwiEXWjs4gXaf-zUzZA53wq4c_DCINS_Ap4Ob7WEI1pheUXja8Y7h0NuBMj9404KGJGmNxD-AmSs3GezJja4AGQtiWtxSiC2cUL6WfHF39iAA',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ];
    }

    public function guzzle_index($folder, $fileorfolder)
    {
        $url = "https://douglasinvestmentsza.sharepoint.com/sites/DouglasData/_api/web/GetFolderByServerRelativeUrl('/sites/DouglasData/Shared%20Documents/Douglas%20Investments/" . $folder . "')/" . $fileorfolder;
        $client = new \GuzzleHttp\Client(self::getHttpHeaders());
        $response = $client->get($url, ['verify' => false]);
        $resp['statusCode'] = $response->getStatusCode();
        $resp['bodyContents'] = $response->getBody()->getContents();
        return $resp;
    }

    public function generate_token()
    {

        $headers    =   [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ];

        $url = "https://accounts.accesscontrol.windows.net/f0581217-861f-4d3d-bddf-5e880e03939f/tokens/OAuth/2";
        $client = new \GuzzleHttp\Client($headers);
        $response = $client->post($url, self::getHttpBody());
        $resp['statusCode'] = $response->getStatusCode();
        $resp['bodyContents'] = $response->getBody()->getContents();
        $data = json_decode($resp['bodyContents'], true);
        return $data['access_token'];
    }

    public function update_sharepoint_synch_of_user($user_folder_data)
    {
        $arr = [""];
        foreach ($user_folder_data['value'] as $rw) {
            array_push($arr, explode("_", $rw['Name'])[0]);
        }
        $role = DB::table('role_user')->where('role_id', 2)->pluck('user_id')->toArray();
        $us = User::whereIn('id', $role)->where('deleted_at', NULL)->whereNotIn('client_code', $arr)->get();
        foreach ($us as $rs) {
            $c_share_synch_log_notification = ClientDbUserSharepointLogNotifications::where('client_code', $rs->client_code)->count();
            if ($c_share_synch_log_notification == 0) {
                ClientDbUserSharepointLogNotifications::create([
                    'client_code' => $rs->client_code,
                    'db_client_folder_name' => $rs->client_code . '_' . $rs->name
                ]);
            }
        }
    }

    public function update_sharepoint_synch($user_folder_data)
    {
        try {
            self::update_sharepoint_synch_of_user($user_folder_data);
            foreach ($user_folder_data['value'] as $rw) {
                $check_user = User::where('deleted_at', NULL)->where('client_code', explode("_", $rw['Name'])[0])->first();
                if ($check_user) {
                    $c_share_synch = ClientSharepointSynch::where('client_code', $check_user->client_code)->count();
                    if ($c_share_synch == 0) {
                        ClientSharepointSynch::create([
                            'client_code' => $check_user->client_code,
                            'user_id' => $check_user->id,
                            'Sharepoint_folder_name' => $rw['Name'],
                            'Sharepoint_folder_path' => $rw['ServerRelativeUrl'],
                        ]);
                    }
                } else {
                    $c_share_synch_log_notification = ClientSharepointLogNotification::where('client_code', explode("_", $rw['Name'])[0])->count();
                    if ($c_share_synch_log_notification == 0) {
                        ClientSharepointLogNotification::create([
                            'client_code' => explode("_", $rw['Name'])[0],
                            'Sharepoint_folder_name' => $rw['Name']
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }
        return 'success';
    }

    public function synch_data_by_user_id($user)
    {
        try {
            $cd = ClientSharepointSynch::where('user_id', $user->id)->first();
            if ($cd) {
                $resp = $this->guzzle_index($this->rootfolder . "/" . $cd->Sharepoint_folder_name, $this->datafolder);
            } else {
                $resp = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $user->client_code . '_' . $user->name), $this->datafolder);
            }
            if ($resp['statusCode'] == 401) {
                $get_token = Controller::generate_token();
                AuthToken::where('id', 1)->update(['sharepoint_auth_token' => $get_token]);
                return $this->synch_data_by_user_id($user);
            } else {
                $data = json_decode($resp['bodyContents'], true);
                if (!empty($data['value'])) {
                    $cuser = ClientSharepointSynch::where('user_id', $user->id)->count();
                    if ($cuser != 1) {
                        ClientSharepointSynch::create([
                            'client_code' => $user->client_code,
                            'user_id' => $user->id,
                            'Sharepoint_folder_name' => $user->client_code . '_' . $user->name,
                            'Sharepoint_folder_path' => 'Not getting path',
                        ]);
                    }
                    $this->synch_operation($data['value'], $user->client_code);
                    return response()->json(['success' => true, 'data' => []]);
                } else {
                    return response()->json(['success' => false, 'data' => []]);
                }
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }
        //return 'success';
    }

    public function synch_operation($data_arr, $user_code)
    {
        $month_array = [
            "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
        ];

        ClientSharepointSyncheCompany::where('client_code', $user_code)->delete();
        ClientSharepointSyncheYear::where('client_code', $user_code)->delete();
        ClientSharepointSyncheFile::where('client_code', $user_code)->delete();
        $checkrec  = ClientSharepointSynch::where('client_code', $user_code)->first();
        if ($checkrec) {
            $syncMailData = [
                'subject' => 'User Synchronization process for ' . $checkrec->Sharepoint_folder_name . ' started',
                'title' => 'User Synchronization process for  ' . $checkrec->Sharepoint_folder_name . ' started',
                'body' => ''
            ];
            Mail::to(env('CLIENT_EMAIL'))->send(new SyncMail($syncMailData));
        }

        foreach ($data_arr as $rs) {
            $get_inv_com = InvestCompany::where('investment_short_code', $rs['Name'])->first();
            if ($get_inv_com) {
                $company =  ClientSharepointSyncheCompany::updateOrCreate([
                    'client_code' => $user_code,
                    'investment_company' => $rs['Name'],
                    'invest_companie_id' => $get_inv_com->id
                ]);
                ClientSharepointSyncheCompanyLog::updateOrCreate([
                    'client_code' => $user_code,
                    'investment_company' => $rs['Name'],
                    'invest_companie_id' => $get_inv_com->id
                ]);
                $com_data = ClientSharepointSyncheCompany::where('id', $company->id)->first();
                if ($com_data) {
                    $getfolder = ClientSharepointSynch::where('client_code', $com_data->client_code)->first();
                    $respofyear = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $getfolder->Sharepoint_folder_name) . "/" . $com_data->investment_company, $this->datafolder);
                    $dataofyears = json_decode($respofyear['bodyContents'], true);
                    if (!empty($dataofyears['value'])) {
                        foreach ($dataofyears['value'] as $rw_year) {
                            $years = ClientSharepointSyncheYear::updateOrCreate([
                                'client_code' => $com_data->client_code,
                                'investment_company' => $com_data->investment_company,
                                'financial_year' => $rw_year['Name'],
                                'invest_companie_id' => $get_inv_com->id
                            ]);
                            ClientSharepointSyncheYearLog::updateOrCreate([
                                'client_code' => $com_data->client_code,
                                'investment_company' => $com_data->investment_company,
                                'financial_year' => $rw_year['Name'],
                                'invest_companie_id' => $get_inv_com->id
                            ]);
                            $filesync = ClientSharepointSyncheYear::where('id', $years->id)->first();
                            if ($filesync) {
                                $getfolder = ClientSharepointSynch::where('client_code', $filesync->client_code)->first();
                                $respfiles = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $getfolder->Sharepoint_folder_name) . "/" . $filesync->investment_company . "/" . $filesync->financial_year, $this->datafile);
                                $datafiles = json_decode($respfiles['bodyContents'], true);

                                if (!empty($datafiles['value'])) {
                                    foreach ($datafiles['value'] as $rw_files) {
                                        $date_val = null;
                                        if (count(explode(" ", $rw_files['Name'])) >= 1) {
                                            $data = array_reverse(explode(" ", $rw_files['Name']));
                                          /*  if (in_array($data[1], $month_array)) {
                                                $year = strtok($data[0], '.');
                                                $month = $data[1];
                                                $date_val = $month . ' ' . $year;
                                            }*/
                                            $date_val = strtok($data[0], '.');
                                        }
                                        ClientSharepointSyncheFile::updateOrCreate([
                                            'client_code' => $filesync->client_code,
                                            'investment_company' => $filesync->investment_company,
                                            'financial_year' => $filesync->financial_year,
                                            'Sharepoint_file_path' => $rw_files['ServerRelativeUrl'],
                                            'file_date' => $date_val,
                                            'data_file' => $rw_files['Name'],
                                            'invest_companie_id' => $get_inv_com->id
                                        ]);
                                        ClientSharepointSyncheFileLog::updateOrCreate([
                                            'client_code' => $filesync->client_code,
                                            'investment_company' => $filesync->investment_company,
                                            'financial_year' => $filesync->financial_year,
                                            'Sharepoint_file_path' => $rw_files['ServerRelativeUrl'],
                                            'file_date' => $date_val,
                                            'data_file' => $rw_files['Name'],
                                            'invest_companie_id' => $get_inv_com->id
                                        ]);
                                    }
                                }
                            }
                        }
                    } else {
                        $getfolder = ClientSharepointSynch::where('client_code', $com_data->client_code)->first();
                        $respfiles = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $getfolder->Sharepoint_folder_name) . "/" . $com_data->investment_company, $this->datafile);
                        $datafiles = json_decode($respfiles['bodyContents'], true);

                        if (!empty($datafiles['value'])) {
                            foreach ($datafiles['value'] as $rw_files) {
                                
                                 $date_val = null;
                                            if (count(explode(" ", $rw_files['Name'])) >= 2) {
                                                $data = array_reverse(explode(" ", $rw_files['Name']));
                                                /*if (in_array($data[1], $month_array)) {
                                                    $year = strtok($data[0], '.');
                                                    $month = $data[1];
                                                    $date_val = $month . ' ' . $year;
                                                }*/
                                                $date_val = strtok($data[0], '.');
                                            }
                                ClientSharepointSyncheFile::updateOrCreate([
                                    'client_code' => $getfolder->client_code,
                                    'investment_company' => $com_data->investment_company,
                                    'financial_year' => 0,
                                    'Sharepoint_file_path' => $rw_files['ServerRelativeUrl'],
                                    'data_file' => $rw_files['Name'],
                                    'file_date' => $date_val,
                                    'invest_companie_id' => $get_inv_com->id
                                ]);

                                ClientSharepointSyncheFileLog::updateOrCreate([
                                    'client_code' => $getfolder->client_code,
                                    'investment_company' => $com_data->investment_company,
                                    'financial_year' => 0,
                                    'Sharepoint_file_path' => $rw_files['ServerRelativeUrl'],
                                    'data_file' => $rw_files['Name'],
                                    'file_date' => $date_val,
                                    'invest_companie_id' => $get_inv_com->id
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
