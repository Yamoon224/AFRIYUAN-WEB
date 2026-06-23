<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\TransferFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    private array $settingKeys = [
        'app_maintenance_mode',
        'transfers_enabled',
        'africa_to_china_enabled',
        'china_to_africa_enabled',
        'kyc_required',
        'support_email',
        'max_transfer_per_day',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->settingKeys as $key) {
            $settings[$key] = AppSetting::getValue($key);
        }
        $fees = TransferFee::orderBy('from_currency')->get();

        return view('admin.settings.index', compact('settings', 'fees'));
    }

    public function update(Request $request)
    {
        foreach ($this->settingKeys as $key) {
            $value = $request->input($key, '0');
            AppSetting::updateOrCreate(['key' => $key], ['value' => $value]);
            Cache::forget("setting_{$key}");
        }

        return redirect()->route('admin.settings.index')->with('settings_success', 'Paramètres enregistrés.');
    }
}
