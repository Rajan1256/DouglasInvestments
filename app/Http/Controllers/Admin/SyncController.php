<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyInvestCompanyRequest;
use App\Http\Requests\StoreInvestCompanyRequest;
use App\Http\Requests\UpdateInvestCompanyRequest;
use App\Models\InvestCompany;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SyncController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('invest_company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $investCompanies = InvestCompany::all();

        return view('admin.noneSync.show_none_sync', compact('investCompanies'));
    }
    
}
