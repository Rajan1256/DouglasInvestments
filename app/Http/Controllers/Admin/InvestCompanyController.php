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

class InvestCompanyController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('invest_company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $investCompanies = InvestCompany::all();

        return view('admin.investCompanies.index', compact('investCompanies'));
    }

    public function create()
    {
        abort_if(Gate::denies('invest_company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.investCompanies.create');
    }

    public function store(StoreInvestCompanyRequest $request)
    {
        $investCompany = InvestCompany::create($request->all());

        return redirect()->route('admin.invest-companies.index');
    }

    public function edit(InvestCompany $investCompany)
    {
        abort_if(Gate::denies('invest_company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.investCompanies.edit', compact('investCompany'));
    }

    public function update(UpdateInvestCompanyRequest $request, InvestCompany $investCompany)
    {
        $investCompany->update($request->all());

        return redirect()->route('admin.invest-companies.index');
    }

    public function show(InvestCompany $investCompany)
    {
        abort_if(Gate::denies('invest_company_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.investCompanies.show', compact('investCompany'));
    }

    public function destroy(InvestCompany $investCompany)
    {
        abort_if(Gate::denies('invest_company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $investCompany->delete();

        return back();
    }

    public function massDestroy(MassDestroyInvestCompanyRequest $request)
    {
        $investCompanies = InvestCompany::find(request('ids'));

        foreach ($investCompanies as $investCompany) {
            $investCompany->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
