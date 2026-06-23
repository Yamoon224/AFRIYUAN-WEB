@extends('layouts.auth')

@section('title', 'Vérification OTP')
@section('subtitle', 'Vérifiez votre numéro')

@section('content')
<div class="text-center">
    <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
    </div>

    <h1 class="text-2xl font-bold text-gray-900 mb-2">Vérification SMS</h1>
    <p class="text-gray-500 text-sm mb-8">
        Nous avons envoyé un code à 6 chiffres au<br>
        <span class="font-semibold text-gray-700">{{ $phone ?? '+XXX XXXXXXXXX' }}</span>
    </p>

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 text-left">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}" x-data="otpForm()" class="space-y-6">
        @csrf
        <input type="hidden" name="user_id" value="{{ $userId ?? '' }}">
        <input type="hidden" name="type" value="phone">

        {{-- OTP 6 digit inputs --}}
        <div class="flex justify-center gap-3">
            @for($i = 0; $i < 6; $i++)
            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                   x-ref="digit{{ $i }}"
                   @input="handleInput($event, {{ $i }})"
                   @keydown.backspace="handleBackspace($event, {{ $i }})"
                   @paste.prevent="handlePaste($event)"
                   class="w-12 h-14 text-center text-xl font-bold rounded-xl border-2 border-gray-200
                          focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 focus:outline-none
                          transition-all duration-150">
            @endfor
        </div>

        <input type="hidden" name="otp" x-bind:value="getOtp()">

        <button type="submit" class="btn-primary w-full">
            Vérifier le code
        </button>
    </form>

    <div class="mt-6" x-data="{ countdown: 60, interval: null }" x-init="
        interval = setInterval(() => { if (countdown > 0) countdown--; else clearInterval(interval); }, 1000)
    ">
        <p class="text-sm text-gray-500">
            Code non reçu ?
            <template x-if="countdown > 0">
                <span class="text-gray-400">Renvoyer dans <span x-text="countdown" class="font-medium text-gray-700"></span>s</span>
            </template>
            <template x-if="countdown === 0">
                <form method="POST" action="{{ route('otp.resend') }}" class="inline">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $userId ?? '' }}">
                    <button type="submit" class="text-primary-600 font-semibold hover:underline">Renvoyer le code</button>
                </form>
            </template>
        </p>
    </div>
</div>

@push('scripts')
<script>
function otpForm() {
    return {
        digits: ['', '', '', '', '', ''],
        getOtp() { return this.digits.join(''); },
        handleInput(e, idx) {
            const val = e.target.value.replace(/\D/g, '');
            this.digits[idx] = val ? val[val.length - 1] : '';
            e.target.value = this.digits[idx];
            if (this.digits[idx] && idx < 5) this.$refs[`digit${idx + 1}`].focus();
        },
        handleBackspace(e, idx) {
            if (!e.target.value && idx > 0) this.$refs[`digit${idx - 1}`].focus();
        },
        handlePaste(e) {
            const text = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            text.split('').forEach((c, i) => {
                this.digits[i] = c;
                if (this.$refs[`digit${i}`]) this.$refs[`digit${i}`].value = c;
            });
            if (text.length > 0) this.$refs[`digit${Math.min(text.length - 1, 5)}`].focus();
        }
    }
}
</script>
@endpush
@endsection
