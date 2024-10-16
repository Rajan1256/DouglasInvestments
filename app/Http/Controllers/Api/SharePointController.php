<?php

namespace App\http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\SendTestMailToAllUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\AddUserListMain;
use App\Models\User;
use App\Mail\AdminRemoveUser;
use App\Mail\SendTestMail;
use App\Mail\SyncMail;
use App\Models\AuthToken;
use App\Models\ClientSharepointLogNotification;
use App\Models\ClientSharepointSynch;
use App\Models\InvestCompany;
use App\Models\ClientSharepointSyncheCompany;
use App\Models\ClientSharepointSyncheCompanyLog;
use App\Models\ClientSharepointSyncheYear;
use App\Models\ClientSharepointSyncheYearLog;
use App\Models\ClientSharepointSyncheFile;
use App\Models\ClientSharepointSyncheFileLog;
use App\Models\ClientDbUserSharepointLogNotifications;
use Exception;

class SharePointController extends Controller
{

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

    public static function getcontextheader()
    {
        $bearerToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6IlQxU3QtZExUdnlXUmd4Ql82NzZ1OGtyWFMtSSIsImtpZCI6IlQxU3QtZExUdnlXUmd4Ql82NzZ1OGtyWFMtSSJ9.eyJhdWQiOiIwMDAwMDAwMy0wMDAwLTBmZjEtY2UwMC0wMDAwMDAwMDAwMDAvZG91Z2xhc2ludmVzdG1lbnRzemEuc2hhcmVwb2ludC5jb21AZjA1ODEyMTctODYxZi00ZDNkLWJkZGYtNWU4ODBlMDM5MzlmIiwiaXNzIjoiMDAwMDAwMDEtMDAwMC0wMDAwLWMwMDAtMDAwMDAwMDAwMDAwQGYwNTgxMjE3LTg2MWYtNGQzZC1iZGRmLTVlODgwZTAzOTM5ZiIsImlhdCI6MTcwMDYzMTIwMSwibmJmIjoxNzAwNjMxMjAxLCJleHAiOjE3MDA2NjAzMDEsImFjdG9yIjoiYzJkZWY5NDktYjEwMy00MTllLTk4YWEtODI0NmY5ZDg3NjdlQGYwNTgxMjE3LTg2MWYtNGQzZC1iZGRmLTVlODgwZTAzOTM5ZiIsImlkZW50aXR5cHJvdmlkZXIiOiJ1cm46ZmVkZXJhdGlvbjptaWNyb3NvZnRvbmxpbmUiLCJuYW1laWQiOiIxMDAzMjAwMzE4QTRGNUI3In0.T1PTA5srfjw9U7FNqGMe7tJrZtIh49stGQMXsT9Zp9V-i5obfuab56FUmr5inlRWNlT7M0a9mVYhrkvPiykzLjj9dOG8dm2GTN0GP6QU2TZzuY4Q2J0mYXO_7th1Y3GZ_nBF6EVU-PwWH-VP5ciZ0-GNlPCLKzCPOx_MFFLHyrLQv6_N-pOZwvWPNUgT1jb8C3AdL53_8nFQp3dUmdZ79Oj_SxmEDirCTpd-jAOFHZUOG3jgJBOFpcBUDhqkWJetXoGtqPaul8o7gUhh36j3QbDave6sWZxD8vt6sW_G5DRNn_6kjywIXqdeNUNNCqbCwlqA4MitTzbjNmJB9yuIiQ';
        $context = stream_context_create([
            'http' => [
                'Accept' => 'application/json; odata=nometadata',
                'Authorization' => 'Bearer ' . $bearerToken,
            ],
        ]);
        return $context;
    }

    public function index()
    {
        $resp = $this->guzzle_index($this->rootfolder, $this->datafolder);
        if ($resp['statusCode'] == 401) {
            $get_token = Controller::generate_token();
            AuthToken::where('id', 1)->update(['sharepoint_auth_token' => $get_token]);
            return $this->index();
        } else {
            $data = json_decode($resp['bodyContents'], true);
            $this->update_sharepoint_synch($data);
            $cnt = ClientSharepointSynch::where('flage', 1)->pluck('Sharepoint_folder_name')->toArray();
            return $this->send_main_for_adding_user($cnt);
        }
    }

    public function sendMailCron()
    {
        $sendall = SendTestMailToAllUser::where('emailed', 0)->limit(env('CUSTOMER_EMAIL_CRONJOB_DATA_LIMIT'))->get();

        foreach ($sendall as $rw) {
            $username = User::where('id',base64_decode($rw->user_id))->value('name');
            $sendTestMailData = [
                'name' => $username,
                'title' => 'This month user synchronization process is completed.',
                'body' => 'To access a dashboard please click on link below.',
                'userid' => base64_decode($rw->user_id),
                'replytoemail'=>$rw->replyto_email,
                'replytoname'=>$rw->replyto_name,
                'link' => url('/sa-id-verification') . '/' . $rw->user_id
            ];
            try {
                Mail::to($rw->user_email)->send(new SendTestMail($sendTestMailData));
                SendTestMailToAllUser::where('id', $rw->id)->update([
                    'emailed' => 1
                ]);
            } catch (Exception $ex) {
            }
        }

        $check_first_sync = SendTestMailToAllUser::orderBy('id', 'desc')->first();
        if ($check_first_sync->emailed == 1) {
            $syncMailData = [
                'subject' => 'Client Email Notification Ended',
                'title' => 'Please note that monthly Client Email Notification process is ended.',
                'body' => ''
            ];
            Mail::to(env('CLIENT_EMAIL'))->send(new SyncMail($syncMailData));
        }

        return 'success';
    }

    public function send_main_for_adding_user($folders)
    {
        $clients = ClientSharepointLogNotification::where('emailed', 0)->get();
        $clientsdb = ClientDbUserSharepointLogNotifications::where('emailed', 0)->get();
        $mailData = [
            'title' => 'Douglas Investment | User Mismatch in Website OR Sharepoint',
            'body_first' => 'Following users are available in Sharepoint but not in Website as per required Client Code Setup',
            'body_second' => 'Following users are available in Website but not in Sharepoint as per required Client Code Setup',
            'data_first' =>  $clients,
            'data_second' => $clientsdb
        ];
        if (count($clients) != 0 && count($clientsdb) != 0) {
            Mail::to(env('CLIENT_EMAIL'))->send(new AddUserListMain($mailData));
            // ClientSharepointLogNotification::where('emailed', 0)->update([
            //     'emailed' => 1
            // ]);
            // ClientDbUserSharepointLogNotifications::where('emailed', 0)->update([
            //     'emailed' => 1
            // ]);
            ClientSharepointLogNotification::where('emailed', 0)->delete();
            ClientDbUserSharepointLogNotifications::where('emailed', 0)->delete();
            //dd("Email is sent successfully.");
        }

        /* if (count($folders) != 0) {
            $mailDataForRemoveUserFromSharepoint = [
                'title' => 'Hi Admin,',
                'body' => "Following User's are Deleted from Website but Sharepoint has those folder. Please do needful",
                'data' =>  $folders,
            ];
            Mail::to('rgharia@plusonex.com')->send(new AdminRemoveUser($mailDataForRemoveUserFromSharepoint));
            //dd("Email is sent successfully for admin removed user list.");
        }*/
        return 'success';
    }

    public function company_cron()
    {
        $month_array = [
            "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
        ];
        $check_first_sync = ClientSharepointSynch::orderBy('id', 'asc')->first();
        if ($check_first_sync->process_flage == 0) {
            $syncMailData = [
                'subject' => 'Synchronization Process Started',
                'title' => 'Please note that monthly Synchronization process is started between website and SharePoint folder.',
                'body' => ''
            ];
            Mail::to(env('CLIENT_EMAIL'))->send(new SyncMail($syncMailData));
        }
        //  $this->update_investment_comapnys();
        $user = ClientSharepointSynch::where('process_flage', 0)->where('flage', 0)->limit(env('CRONJOB_DATA_LIMIT'))->get();
        foreach ($user as $rw) {
            ClientSharepointSyncheCompany::where('client_code', $rw->client_code)->delete();
            ClientSharepointSyncheYear::where('client_code', $rw->client_code)->delete();
            ClientSharepointSyncheFile::where('client_code', $rw->client_code)->delete();
            $resp = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $rw->Sharepoint_folder_name), $this->datafolder);
            if ($resp['statusCode'] == 401) {
                $get_token = Controller::generate_token();
                AuthToken::where('id', 1)->update(['sharepoint_auth_token' => $get_token]);
                return $this->company_cron();
            } else {
                $data = json_decode($resp['bodyContents'], true);
                if (!empty($data['value'])) {
                    foreach ($data['value'] as $rs) {
                        $get_inv_com = InvestCompany::where('investment_short_code', $rs['Name'])->first();
                        if ($get_inv_com) {
                            $company =  ClientSharepointSyncheCompany::updateOrCreate([
                                'client_code' => $rw->client_code,
                                'investment_company' => $rs['Name'],
                                'invest_companie_id' => $get_inv_com->id
                            ]);
                            ClientSharepointSyncheCompanyLog::updateOrCreate([
                                'client_code' => $rw->client_code,
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
                                                       /* if (in_array($data[1], $month_array)) {
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
                                                        'data_file' => $rw_files['Name'],
                                                        'file_date' => $date_val,
                                                        'invest_companie_id' => $get_inv_com->id
                                                    ]);

                                                    ClientSharepointSyncheFileLog::updateOrCreate([
                                                        'client_code' => $filesync->client_code,
                                                        'investment_company' => $filesync->investment_company,
                                                        'financial_year' => $filesync->financial_year,
                                                        'Sharepoint_file_path' => $rw_files['ServerRelativeUrl'],
                                                        'data_file' => $rw_files['Name'],
                                                        'file_date' => $date_val,
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
                                            if (count(explode(" ", $rw_files['Name'])) >= 1) {
                                                $data = array_reverse(explode(" ", $rw_files['Name']));
                                               /* if (in_array($data[1], $month_array)) {
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
            ClientSharepointSynch::where('user_id', $rw->user_id)->update([
                'process_flage' => 1
            ]);
        }
        $p1 = ClientSharepointSynch::where('process_flage', 1)->count();
        $p2 = ClientSharepointSynch::where('flage', 0)->count();
        if ($p1 == $p2) {
            ClientSharepointSynch::where('process_flage', 1)->update([
                'process_flage' => 0
            ]);

            $syncMailData = [
                'subject' => ' Synchronization Process Ended',
                'title' => 'Please note that monthly Synchronization process is ended between website and SharePoint folder.',
                'body' => ''
            ];
            Mail::to(env('CLIENT_EMAIL'))->send(new SyncMail($syncMailData));
        }
        return 'success';
        //return $this->remove_folders();
    }

    public function update_investment_comapnys()
    {
        $cm = InvestCompany::get();
        foreach ($cm as $row) {
            ClientSharepointSyncheCompany::where('investment_company', $row->investment_company)->update([
                'invest_companie_id' => $row->id
            ]);
            ClientSharepointSyncheYear::where('investment_company', $row->investment_company)->update([
                'invest_companie_id' => $row->id
            ]);
            ClientSharepointSyncheFile::where('investment_company', $row->investment_company)->update([
                'invest_companie_id' => $row->id
            ]);
        }
    }

    /*  public function company_year_cron()
    {
        $company = ClientSharepointSyncheCompany::get();
        foreach ($company as $rs) {
            $getfolder = ClientSharepointSynch::where('client_code', $rs->client_code)->first();
            $resp = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $getfolder->Sharepoint_folder_name) . "/" . $rs->investment_company, $this->datafolder);

            if ($resp['statusCode'] == 401) {
                $get_token = Controller::generate_token();
                AuthToken::where('id', 1)->update(['sharepoint_auth_token' => $get_token]);
                return $this->company_cron();
            } else {
                $data = json_decode($resp['bodyContents'], true);
                if (!empty($data['value'])) {
                    foreach ($data['value'] as $rw) {
                        ClientSharepointSyncheYear::updateOrCreate([
                            'client_code' => $rs->client_code,
                            'investment_company' => $rs->investment_company,
                            'financial_year' => $rw['Name']
                        ]);
                    }
                }
            }
        }
        return 'success';
    }
    public function company_year_file_cron()
    {
        $yeardata = ClientSharepointSyncheYear::get();
        foreach ($yeardata as $rs) {
            $getfolder = ClientSharepointSynch::where('client_code', $rs->client_code)->first();
            $resp = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $getfolder->Sharepoint_folder_name) . "/" . $rs->investment_company . "/" . $rs->financial_year, $this->datafile);
            if ($resp['statusCode'] == 401) {
                $get_token = Controller::generate_token();
                AuthToken::where('id', 1)->update(['sharepoint_auth_token' => $get_token]);
                return $this->company_cron();
            } else {
                $data = json_decode($resp['bodyContents'], true);

                if (!empty($data['value'])) {
                    foreach ($data['value'] as $rw) {
                        ClientSharepointSyncheFile::updateOrCreate([
                            'client_code' => $rs->client_code,
                            'investment_company' => $rs->investment_company,
                            'financial_year' => $rs->financial_year,
                            'Sharepoint_file_path' => $rw['ServerRelativeUrl'],
                            'data_file' => $rw['Name']
                        ]);
                    }
                }
            }
        }
        return 'success';
    }*/

    public function remove_folders()
    {
        $user = ClientSharepointSynch::where('flage', 0)->get();
        foreach ($user as $rw) {
            $resp = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $rw->Sharepoint_folder_name), $this->datafolder);
            if ($resp['statusCode'] == 401) {
                $get_token = Controller::generate_token();
                AuthToken::where('id', 1)->update(['sharepoint_auth_token' => $get_token]);
                return $this->company_cron();
            } else {
                $data = json_decode($resp['bodyContents'], true);
                if (!empty($data['value'])) {
                    $client_company = ClientSharepointSyncheCompany::where('client_code', $rw->client_code)->get();
                    foreach ($client_company as $rcc) {
                        $respofyear = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $rw->Sharepoint_folder_name) . "/" . $rcc->investment_company, $this->datafolder);
                        $dataofyears = json_decode($respofyear['bodyContents'], true);

                        if (empty($dataofyears['value'])) {
                            ClientSharepointSyncheFile::where('client_code', $rcc->client_code)->where('investment_company', $rcc->investment_company)->delete();
                            ClientSharepointSyncheYear::where('client_code', $rcc->client_code)->where('investment_company', $rcc->investment_company)->delete();
                            ClientSharepointSyncheCompany::where('id', $rcc->id)->delete();
                        } else {
                            $client_company_year = ClientSharepointSyncheYear::where('client_code', $rw->client_code)->where('investment_company', $rcc->investment_company)->get();
                            foreach ($client_company_year as $rccy) {
                                $respoffiles = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $rw->Sharepoint_folder_name) . "/" . $rccy->investment_company . "/" . $rccy->financial_year, $this->datafile);
                                $dataoffiles = json_decode($respoffiles['bodyContents'], true);
                                if (empty($dataoffiles['value'])) {
                                    ClientSharepointSyncheFile::where('client_code', $rccy->client_code)->where('investment_company', $rccy->investment_company)->where('financial_year', $rccy->financial_year)->delete();
                                    ClientSharepointSyncheYear::where('client_code', $rccy->client_code)->where('investment_company', $rccy->investment_company)->where('financial_year', $rccy->financial_year)->delete();
                                } else {
                                    $client_company_file = ClientSharepointSyncheFile::where('client_code', $rw->client_code)->get();
                                    foreach ($client_company_file as $rccf) {
                                        $respofsingfiles = $this->guzzle_index($this->rootfolder . "/" . str_replace(' ', '%20', $rw->Sharepoint_folder_name) . "/" . $rccf->investment_company . "/" . $rccf->financial_year, $this->datafile . "('" . str_replace(' ', '%20', $rccf->data_file) . "')");
                                        $dataofsingfiles = json_decode($respofsingfiles['bodyContents'], true);

                                        if (count($dataofsingfiles) == 1) {
                                            ClientSharepointSyncheFile::where('client_code', $rccf->client_code)->where('investment_company', $rccf->investment_company)->where('financial_year', $rccf->financial_year)->where('data_file', $rccf->data_file)->delete();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $p1 = ClientSharepointSynch::where('process_flage', 1)->count();
        $p2 = ClientSharepointSynch::where('flage', 0)->count();
        if ($p1 == $p2) {
            ClientSharepointSynch::where('process_flage', 1)->update([
                'process_flage' => 0
            ]);
        }
        return 'success';
    }

    public function single_index()
    {
        $vl = '$value';
        $url = "https://douglasinvestmentsza.sharepoint.com/sites/DouglasData/_api/web/GetFolderByServerRelativeUrl('/sites/DouglasData/Shared%20Documents/Douglas%20Investments/+%20One%20X')/Files('Mutual%20NDA_+OneX Solutions_20220901_signed.pdf')/" . $vl;

        $client = new \GuzzleHttp\Client(self::getHttpHeaders());
        $response = $client->get($url, ['verify' => false]);
        $resp['statusCode'] = $response->getStatusCode();
        $resp['bodyContents'] = $response->getBody()->getContents();
        return $resp;
    }
}
