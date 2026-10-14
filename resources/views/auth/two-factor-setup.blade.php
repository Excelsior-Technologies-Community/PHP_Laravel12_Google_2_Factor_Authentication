<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Two-Factor Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-code { font-family: 'JetBrains Mono', monospace; }
        .otp-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
        }
        @media print {
            body { background: white !important; color: black !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            .print-card { box-shadow: none !important; border: 1px solid #ccc !important; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/40 to-slate-100 min-h-screen py-10 px-4 flex items-center justify-center">

    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden print-card">

        <!-- Top Accent Gradient -->
        <div class="h-2.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 no-print"></div>

        <div class="p-6 sm:p-10">

            <!-- Title & Overview -->
            <div class="text-center mb-8 no-print">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 mb-3">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span> 2FA Setup Onboarding Wizard
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Protect Your Account with 2FA</h1>
                <p class="text-sm text-slate-500 mt-1.5 max-w-lg mx-auto">
                    Two-factor authentication adds an extra layer of security. Follow this 4-step wizard to configure your authenticator app.
                </p>
            </div>

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-r-xl text-sm font-semibold shadow-sm flex items-center gap-2 no-print">
                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 text-amber-800 p-4 rounded-r-xl text-sm font-semibold shadow-sm flex items-center gap-2 no-print">
                    <svg class="w-5 h-5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            <!-- Interactive Step Progress Bar -->
            <div class="mb-8 no-print">
                <div class="flex justify-between items-center relative mb-2">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 z-0"></div>
                    <div id="progress-bar-fill" class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-indigo-600 transition-all duration-300 z-0" style="width: 0%;"></div>

                    <!-- Step 1 Dot -->
                    <button type="button" onclick="goToStep(1)" id="step-btn-1" class="step-btn relative z-10 w-9 h-9 rounded-full font-bold text-xs flex items-center justify-center transition border-2 border-indigo-600 bg-indigo-600 text-white shadow-md">
                        1
                    </button>
                    <!-- Step 2 Dot -->
                    <button type="button" onclick="goToStep(2)" id="step-btn-2" class="step-btn relative z-10 w-9 h-9 rounded-full font-bold text-xs flex items-center justify-center transition border-2 border-slate-300 bg-white text-slate-500">
                        2
                    </button>
                    <!-- Step 3 Dot -->
                    <button type="button" onclick="goToStep(3)" id="step-btn-3" class="step-btn relative z-10 w-9 h-9 rounded-full font-bold text-xs flex items-center justify-center transition border-2 border-slate-300 bg-white text-slate-500">
                        3
                    </button>
                    <!-- Step 4 Dot -->
                    <button type="button" onclick="goToStep(4)" id="step-btn-4" class="step-btn relative z-10 w-9 h-9 rounded-full font-bold text-xs flex items-center justify-center transition border-2 border-slate-300 bg-white text-slate-500">
                        4
                    </button>
                </div>
                <div class="flex justify-between text-[11px] font-bold text-slate-500 uppercase tracking-wider px-1">
                    <span id="step-text-1" class="text-indigo-600">1. Get App</span>
                    <span id="step-text-2">2. Scan QR</span>
                    <span id="step-text-3">3. Verify</span>
                    <span id="step-text-4">4. Recovery Kit</span>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- STEP 1: DOWNLOAD AUTHENTICATOR APP             -->
            <!-- ============================================== -->
            <div id="step-content-1" class="step-content">
                <div class="bg-indigo-50/60 border border-indigo-100 rounded-2xl p-6 mb-6">
                    <h3 class="font-bold text-slate-800 text-lg mb-2 flex items-center gap-2">
                        <span>📲</span> Step 1: Install an Authenticator App
                    </h3>
                    <p class="text-sm text-slate-600 mb-5">
                        Download and install any standard TOTP authenticator app on your mobile device. If you already have one, click Next.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Google Authenticator -->
                        <div class="bg-white p-4 rounded-xl border border-slate-200 text-center shadow-sm">
                            <div class="w-10 h-10 mx-auto mb-2 flex items-center justify-center text-2xl">
                                🌐
                            </div>
                            <h4 class="font-bold text-sm text-slate-800">Google Authenticator</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 mb-3">Popular & lightweight</p>
                            <div class="flex flex-col gap-1.5">
                                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank"
                                   class="text-[11px] bg-slate-900 text-white font-semibold py-1 px-2 rounded hover:bg-slate-800 transition">Android Play Store</a>
                                <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank"
                                   class="text-[11px] bg-slate-900 text-white font-semibold py-1 px-2 rounded hover:bg-slate-800 transition">Apple App Store</a>
                            </div>
                        </div>

                        <!-- Microsoft Authenticator -->
                        <div class="bg-white p-4 rounded-xl border border-slate-200 text-center shadow-sm">
                            <div class="w-10 h-10 mx-auto mb-2 flex items-center justify-center text-2xl">
                                🔷
                            </div>
                            <h4 class="font-bold text-sm text-slate-800">Microsoft Authenticator</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 mb-3">Cloud backup support</p>
                            <div class="flex flex-col gap-1.5">
                                <a href="https://play.google.com/store/apps/details?id=com.azure.authenticator" target="_blank"
                                   class="text-[11px] bg-slate-900 text-white font-semibold py-1 px-2 rounded hover:bg-slate-800 transition">Android Play Store</a>
                                <a href="https://apps.apple.com/us/app/microsoft-authenticator/id983156458" target="_blank"
                                   class="text-[11px] bg-slate-900 text-white font-semibold py-1 px-2 rounded hover:bg-slate-800 transition">Apple App Store</a>
                            </div>
                        </div>

                        <!-- Twilio Authy -->
                        <div class="bg-white p-4 rounded-xl border border-slate-200 text-center shadow-sm">
                            <div class="w-10 h-10 mx-auto mb-2 flex items-center justify-center text-2xl">
                                🔴
                            </div>
                            <h4 class="font-bold text-sm text-slate-800">Twilio Authy</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 mb-3">Multi-device sync</p>
                            <div class="flex flex-col gap-1.5">
                                <a href="https://authy.com/download/" target="_blank"
                                   class="text-[11px] bg-slate-900 text-white font-semibold py-1 px-2 rounded hover:bg-slate-800 transition">Visit Official Website</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <a href="{{ route('dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        ← Cancel & Return
                    </a>
                    <button type="button" onclick="goToStep(2)" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-md transition flex items-center gap-1.5">
                        <span>Next: Scan QR Code</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- STEP 2: SCAN QR CODE OR MANUAL KEY             -->
            <!-- ============================================== -->
            <div id="step-content-2" class="step-content hidden">
                <div class="text-center mb-6">
                    <h3 class="font-bold text-slate-800 text-lg mb-1 flex items-center justify-center gap-2">
                        <span>📷</span> Step 2: Scan the QR Code
                    </h3>
                    <p class="text-sm text-slate-500">
                        Open your Authenticator app, tap <strong class="text-slate-700">"+"</strong> and scan the QR code below.
                    </p>
                </div>

                <!-- QR Code Box -->
                <div class="flex justify-center mb-6">
                    <div class="p-4 bg-white border-2 border-slate-200 rounded-2xl shadow-sm inline-block">
                        <div class="w-48 h-48 flex items-center justify-center">
                            {!! $qrCode !!}
                        </div>
                    </div>
                </div>

                <!-- Manual Secret Key Card -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Cannot scan? Enter code manually:</span>
                        <button type="button" onclick="copySecretKey()" id="copy-secret-btn" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 bg-white px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs transition">
                            <span id="copy-btn-text">📋 Copy Key</span>
                        </button>
                    </div>
                    <div class="font-mono-code font-bold text-center text-lg sm:text-xl tracking-wider text-slate-800 bg-white py-2.5 px-3 rounded-xl border border-slate-200 select-all" id="secret-key-display">
                        {{ $secret }}
                    </div>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <button type="button" onclick="goToStep(1)" class="text-xs font-bold text-slate-600 hover:text-slate-800 px-4 py-2 border rounded-xl hover:bg-slate-50 transition">
                        ← Back
                    </button>
                    <button type="button" onclick="goToStep(3)" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-md transition flex items-center gap-1.5">
                        <span>Next: Enter 6-Digit Code</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- STEP 3: TEST 6-DIGIT VERIFICATION CODE         -->
            <!-- ============================================== -->
            <div id="step-content-3" class="step-content hidden">
                <div class="text-center mb-6">
                    <h3 class="font-bold text-slate-800 text-lg mb-1 flex items-center justify-center gap-2">
                        <span>🔐</span> Step 3: Verify & Activate 2FA
                    </h3>
                    <p class="text-sm text-slate-500">
                        Enter the 6-digit code currently shown in your authenticator app to confirm setup.
                    </p>
                </div>

                <!-- 30-Second TOTP Pulse Ring -->
                <div class="mb-6 bg-slate-50 border border-slate-200 rounded-xl p-3.5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative w-9 h-9 flex items-center justify-center">
                            <svg class="w-9 h-9 transform -rotate-90" viewBox="0 0 44 44">
                                <circle cx="22" cy="22" r="18" stroke="#e2e8f0" stroke-width="3" fill="none" />
                                <circle id="setup-timer-ring" cx="22" cy="22" r="18" stroke="#4f46e5" stroke-width="3.5" stroke-linecap="round" fill="none"
                                        stroke-dasharray="113.1" stroke-dashoffset="0" />
                            </svg>
                            <span id="setup-timer-seconds" class="absolute font-mono-code font-bold text-xs text-indigo-700">30</span>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-700" id="setup-timer-label">TOTP Code Active</div>
                            <div class="text-[11px] text-slate-400">Time remaining in current cycle</div>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-indigo-100/70 text-indigo-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-ping"></span> Live
                    </span>
                </div>

                <form id="setup-verify-form" method="POST" action="{{ route('2fa.enable') }}">
                    @csrf
                    <input type="hidden" name="code" id="setup-hidden-code" value="">

                    <!-- Discrete 6-Box OTP Input -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 text-center">
                            Enter 6-Digit Authenticator Code
                        </label>

                        <div class="flex justify-center gap-2 sm:gap-3" id="setup-otp-container">
                            @for($i = 0; $i < 6; $i++)
                                <input type="text"
                                       inputmode="numeric"
                                       pattern="[0-9]*"
                                       maxlength="1"
                                       data-setup-index="{{ $i }}"
                                       autocomplete="off"
                                       class="setup-otp-box w-12 h-14 sm:w-13 sm:h-14 text-center font-mono-code text-2xl font-bold text-slate-800 bg-slate-50 border-2 border-slate-200 rounded-xl outline-none transition duration-150 otp-input">
                            @endfor
                        </div>

                        @error('code')
                            <p class="text-red-500 text-xs font-semibold mt-2 text-center flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror

                        <p class="text-[11px] text-slate-400 text-center mt-2">
                            Type or paste your 6-digit code. It will automatically submit.
                        </p>
                    </div>

                    <button type="submit"
                            id="setup-submit-btn"
                            class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-500/25 transition duration-200 flex items-center justify-center gap-2">
                        <span id="setup-btn-text">Verify & Activate 2FA</span>
                        <svg id="setup-btn-spinner" class="hidden w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </button>
                </form>

                <div class="flex justify-between items-center pt-6 border-t border-slate-100 mt-6">
                    <button type="button" onclick="goToStep(2)" class="text-xs font-bold text-slate-600 hover:text-slate-800 px-4 py-2 border rounded-xl hover:bg-slate-50 transition">
                        ← Back to QR Code
                    </button>
                    <button type="button" onclick="goToStep(4)" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                        View Recovery Codes ➜
                    </button>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- STEP 4: RECOVERY KIT & DOWNLOAD/PRINT (PDF)    -->
            <!-- ============================================== -->
            <div id="step-content-4" class="step-content hidden">
                <div class="text-center mb-6">
                    <h3 class="font-bold text-slate-800 text-lg mb-1 flex items-center justify-center gap-2">
                        <span>📄</span> Step 4: Emergency Recovery Codes Kit
                    </h3>
                    <p class="text-sm text-slate-500 max-w-md mx-auto">
                        If you ever lose access to your phone, these recovery codes are your <strong>only</strong> way back into your account.
                    </p>
                </div>

                <!-- Printable Area -->
                <div id="printable-recovery-area" class="bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl p-6 mb-6">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-4">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Account: {{ auth()->user()->email }}</span>
                            <div class="text-[11px] text-slate-400">Generated on {{ now()->format('d M Y, h:i A') }}</div>
                        </div>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-full">8 Single-Use Codes</span>
                    </div>

                    <!-- Codes Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 font-mono-code font-bold text-sm text-slate-800 text-center">
                        @foreach($recoveryCodes as $code)
                            <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-2xs select-all">
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>

                    <p class="text-xs text-red-600 mt-4 text-center font-medium">
                        ⚠️ Treat these codes like your password. Do not share them with anyone.
                    </p>
                </div>

                <!-- Download & Print Action Buttons -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6 no-print">
                    <!-- Download TXT -->
                    <button type="button" onclick="downloadRecoveryTxt()" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold py-2.5 px-3 rounded-xl transition shadow-sm flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download (.txt)</span>
                    </button>

                    <!-- Print / Save as PDF -->
                    <button type="button" onclick="printRecoveryCodes()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 px-3 rounded-xl transition shadow-sm flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Print / Save as PDF</span>
                    </button>

                    <!-- Copy All -->
                    <button type="button" onclick="copyAllCodes()" id="copy-all-btn" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-bold py-2.5 px-3 rounded-xl transition shadow-sm flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span id="copy-all-text">Copy All Codes</span>
                    </button>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-slate-100 no-print">
                    <button type="button" onclick="goToStep(3)" class="text-xs font-bold text-slate-600 hover:text-slate-800 px-4 py-2 border rounded-xl hover:bg-slate-50 transition">
                        ← Back to Verify
                    </button>
                    <a href="{{ route('dashboard') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-md transition flex items-center gap-1.5">
                        <span>Done & Finish Setup ✓</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Wizard & OTP Logic -->
    <script>
        const recoveryCodesArray = @json($recoveryCodes);
        const userEmail = "{{ auth()->user()->email }}";
        const secretKey = "{{ $secret }}";

        let currentStep = 1;

        // If validation errors exist on the test code, automatically show Step 3
        @if($errors->has('code'))
            currentStep = 3;
        @endif

        function goToStep(step) {
            currentStep = step;

            // Hide all steps
            for (let i = 1; i <= 4; i++) {
                const el = document.getElementById('step-content-' + i);
                if (el) el.classList.add('hidden');

                const btn = document.getElementById('step-btn-' + i);
                const txt = document.getElementById('step-text-' + i);

                if (i < step) {
                    // Completed step
                    btn.className = "step-btn relative z-10 w-9 h-9 rounded-full font-bold text-xs flex items-center justify-center transition border-2 border-green-600 bg-green-600 text-white shadow-sm";
                    btn.innerHTML = '✓';
                    txt.className = "text-green-600";
                } else if (i === step) {
                    // Active step
                    btn.className = "step-btn relative z-10 w-9 h-9 rounded-full font-bold text-xs flex items-center justify-center transition border-2 border-indigo-600 bg-indigo-600 text-white shadow-md";
                    btn.innerHTML = i;
                    txt.className = "text-indigo-600";
                } else {
                    // Upcoming step
                    btn.className = "step-btn relative z-10 w-9 h-9 rounded-full font-bold text-xs flex items-center justify-center transition border-2 border-slate-300 bg-white text-slate-500";
                    btn.innerHTML = i;
                    txt.className = "text-slate-400";
                }
            }

            // Show active step
            const activeEl = document.getElementById('step-content-' + step);
            if (activeEl) activeEl.classList.remove('hidden');

            // Update Progress Bar
            const progressFill = document.getElementById('progress-bar-fill');
            const percent = ((step - 1) / 3) * 100;
            progressFill.style.width = percent + '%';

            if (step === 3) {
                const firstBox = document.querySelector('.setup-otp-box');
                if (firstBox) setTimeout(() => firstBox.focus(), 150);
            }
        }

        // Copy Secret Key
        function copySecretKey() {
            navigator.clipboard.writeText(secretKey).then(() => {
                const btnText = document.getElementById('copy-btn-text');
                btnText.textContent = 'Copied! ✓';
                setTimeout(() => { btnText.textContent = '📋 Copy Key'; }, 2000);
            });
        }

        // Copy All Recovery Codes
        function copyAllCodes() {
            const textToCopy = recoveryCodesArray.join('\n');
            navigator.clipboard.writeText(textToCopy).then(() => {
                const btnText = document.getElementById('copy-all-text');
                btnText.textContent = 'All Codes Copied! ✓';
                setTimeout(() => { btnText.textContent = 'Copy All Codes'; }, 2000);
            });
        }

        // Download TXT
        function downloadRecoveryTxt() {
            const dateStr = new Date().toLocaleString();
            let content = "=====================================================\n";
            content += "TWO-FACTOR AUTHENTICATION RECOVERY CODES\n";
            content += "=====================================================\n\n";
            content += "Account: " + userEmail + "\n";
            content += "Generated: " + dateStr + "\n\n";
            content += "IMPORTANT: Each recovery code can only be used once.\n";
            content += "Store this document in a safe password manager or location.\n\n";
            content += "Codes:\n";
            recoveryCodesArray.forEach((c, idx) => {
                content += (idx + 1) + ". " + c + "\n";
            });
            content += "\n=====================================================\n";

            const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = '2fa-recovery-codes-' + userEmail.replace(/[^a-zA-Z0-9]/g, '_') + '.txt';
            link.click();
            URL.revokeObjectURL(link.href);
        }

        // Print / Save as PDF
        function printRecoveryCodes() {
            window.print();
        }

        // Initialize 6-Box OTP for Setup Step 3
        document.addEventListener('DOMContentLoaded', function () {
            goToStep(currentStep);

            const boxes = Array.from(document.querySelectorAll('.setup-otp-box'));
            const hiddenInput = document.getElementById('setup-hidden-code');
            const form = document.getElementById('setup-verify-form');
            const submitBtn = document.getElementById('setup-submit-btn');
            const btnText = document.getElementById('setup-btn-text');
            const btnSpinner = document.getElementById('setup-btn-spinner');

            function syncHidden() {
                const code = boxes.map(b => b.value).join('');
                hiddenInput.value = code;
                return code;
            }

            function triggerSubmit() {
                const code = syncHidden();
                if (code.length === 6) {
                    btnText.textContent = 'Verifying Code...';
                    btnSpinner.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                    form.submit();
                }
            }

            boxes.forEach((box, idx) => {
                box.addEventListener('input', function () {
                    const val = this.value.replace(/[^0-9]/g, '');
                    this.value = val ? val[val.length - 1] : '';

                    if (this.value && idx < boxes.length - 1) {
                        boxes[idx + 1].focus();
                        boxes[idx + 1].select();
                    }

                    const code = syncHidden();
                    if (code.length === 6) {
                        setTimeout(triggerSubmit, 100);
                    }
                });

                box.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace') {
                        if (!this.value && idx > 0) {
                            boxes[idx - 1].focus();
                            boxes[idx - 1].value = '';
                            syncHidden();
                        }
                    } else if (e.key === 'ArrowLeft' && idx > 0) {
                        boxes[idx - 1].focus();
                    } else if (e.key === 'ArrowRight' && idx < boxes.length - 1) {
                        boxes[idx + 1].focus();
                    }
                });

                box.addEventListener('paste', function (e) {
                    e.preventDefault();
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                    const digits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);

                    if (digits.length > 0) {
                        digits.split('').forEach((d, i) => {
                            if (boxes[i]) boxes[i].value = d;
                        });

                        const nextIdx = Math.min(digits.length, boxes.length - 1);
                        boxes[nextIdx].focus();

                        const code = syncHidden();
                        if (code.length === 6) {
                            setTimeout(triggerSubmit, 150);
                        }
                    }
                });
            });

            form.addEventListener('submit', function (e) {
                const code = syncHidden();
                if (code.length < 6) {
                    e.preventDefault();
                    const emptyBox = boxes.find(b => !b.value);
                    if (emptyBox) emptyBox.focus();
                }
            });

            // Setup TOTP Clock
            const timerRing = document.getElementById('setup-timer-ring');
            const timerSeconds = document.getElementById('setup-timer-seconds');
            const timerLabel = document.getElementById('setup-timer-label');
            const circumference = 113.1;

            function updateSetupClock() {
                const now = Math.floor(Date.now() / 1000);
                const secondsRemaining = 30 - (now % 30);

                if (timerSeconds) timerSeconds.textContent = secondsRemaining;
                if (timerRing) {
                    const offset = circumference - (secondsRemaining / 30) * circumference;
                    timerRing.style.strokeDashoffset = offset;

                    if (secondsRemaining <= 5) {
                        timerRing.style.stroke = '#ef4444';
                        if (timerLabel) timerLabel.className = 'text-xs font-bold text-red-600';
                    } else if (secondsRemaining <= 10) {
                        timerRing.style.stroke = '#f59e0b';
                        if (timerLabel) timerLabel.className = 'text-xs font-bold text-amber-600';
                    } else {
                        timerRing.style.stroke = '#4f46e5';
                        if (timerLabel) timerLabel.className = 'text-xs font-bold text-slate-700';
                    }
                }
            }

            updateSetupClock();
            setInterval(updateSetupClock, 1000);
        });
    </script>
</body>
</html>