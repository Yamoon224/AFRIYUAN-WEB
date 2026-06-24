<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.'])->onlyInput('email');
    }

    public function showRegister()
    {
        $countries = Country::with('currency')->where('is_source', true)->orderBy('name')->get();
        return view('auth.register', compact('countries'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name'         => 'required|string|max:100',
            'last_name'          => 'required|string|max:100',
            'email'              => 'required|email|unique:users,email',
            'country_id'         => 'required|exists:countries,id',
            'phone_country_code' => 'nullable|string|max:5',
            'phone_number'       => 'required|string|max:20',
            'date_of_birth'      => 'required|date|before:-18 years',
            'nationality'        => 'nullable|string|max:100',
            'password'           => 'required|min:8|confirmed',
            'terms'              => 'accepted',
        ]);

        // Dial codes fallback si le champ readonly n'a pas été soumis
        $dialCodes = [
            'CI' => '+225', 'GN' => '+224', 'SN' => '+221', 'GH' => '+233',
            'GA' => '+241', 'LR' => '+231', 'SL' => '+232', 'GW' => '+245', 'CN' => '+86',
        ];
        $country     = Country::find($data['country_id']);
        $phoneCode   = $data['phone_country_code'] ?: ($dialCodes[$country->code] ?? '');

        $user = User::create([
            'first_name'         => $data['first_name'],
            'last_name'          => $data['last_name'],
            'email'              => $data['email'],
            'country_id'         => $data['country_id'],
            'phone_country_code' => $phoneCode,
            'phone_number'       => $data['phone_number'],
            'date_of_birth'      => $data['date_of_birth'],
            'nationality'        => $data['nationality'] ?? null,
            'password'           => Hash::make($data['password']),
        ]);

        $this->otpService->generateAndSend($user);
        Auth::login($user);

        return redirect()->route('otp.show')->with('phone', $user->phone_number);
    }

    public function showOtp()
    {
        $user = Auth::user();
        return view('auth.otp', [
            'phone'  => $user->phone_number,
            'userId' => $user->id,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        $user = Auth::user();

        if (!$this->otpService->verify($user->phone_number, $request->otp)) {
            return back()->withErrors(['otp' => 'Code invalide ou expiré.']);
        }

        $user->update(['phone_verified_at' => now()]);
        return redirect()->route('dashboard');
    }

    public function resendOtp(Request $request)
    {
        $this->otpService->generateAndSend(Auth::user());
        return back()->with('status', 'Code renvoyé.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
