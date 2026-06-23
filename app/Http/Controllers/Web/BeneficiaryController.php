<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeneficiaryController extends Controller
{
    public function index()
    {
        $beneficiaries = Auth::user()->beneficiaries()->latest()->paginate(12);
        return view('beneficiaries.index', compact('beneficiaries'));
    }

    public function create()
    {
        return view('beneficiaries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'beneficiary_type'    => 'required|in:china,africa',
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'nickname'            => 'nullable|string|max:100',
            'phone_number'        => 'nullable|string|max:30',
            'receive_method'      => 'required|in:bank_transfer,alipay,wechat_pay,cash_pickup',
            'bank_name'           => 'required_if:receive_method,bank_transfer|nullable|string|max:150',
            'bank_account_number' => 'required_if:receive_method,bank_transfer|nullable|string|max:100',
            'bank_routing_number' => 'nullable|string|max:50',
            'wallet_account_number' => 'required_if:receive_method,alipay,wechat_pay|nullable|string|max:100',
        ]);

        Auth::user()->beneficiaries()->create($data);

        return redirect()->route('beneficiaries.index')->with('success', 'Bénéficiaire ajouté avec succès.');
    }

    public function edit(Beneficiary $beneficiary)
    {
        abort_unless($beneficiary->user_id === Auth::id(), 403);
        return view('beneficiaries.create', compact('beneficiary'));
    }

    public function update(Request $request, Beneficiary $beneficiary)
    {
        abort_unless($beneficiary->user_id === Auth::id(), 403);

        $data = $request->validate([
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'nickname'            => 'nullable|string|max:100',
            'phone_number'        => 'nullable|string|max:30',
            'receive_method'      => 'required|in:bank_transfer,alipay,wechat_pay,cash_pickup',
            'bank_name'           => 'required_if:receive_method,bank_transfer|nullable|string|max:150',
            'bank_account_number' => 'required_if:receive_method,bank_transfer|nullable|string|max:100',
            'bank_routing_number' => 'nullable|string|max:50',
            'wallet_account_number' => 'required_if:receive_method,alipay,wechat_pay|nullable|string|max:100',
        ]);

        $beneficiary->update($data);

        return redirect()->route('beneficiaries.index')->with('success', 'Bénéficiaire mis à jour.');
    }

    public function destroy(Beneficiary $beneficiary)
    {
        abort_unless($beneficiary->user_id === Auth::id(), 403);
        $beneficiary->delete();
        return redirect()->route('beneficiaries.index')->with('success', 'Bénéficiaire supprimé.');
    }
}
