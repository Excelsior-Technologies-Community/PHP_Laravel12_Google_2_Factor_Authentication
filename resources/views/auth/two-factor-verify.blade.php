<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-num { font-family: 'JetBrains Mono', monospace; }
        .otp-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
        }
        .timer-circle {
            transition: stroke-dashoffset 0.8s linear, stroke 0.3s ease;
        }
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.08); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
        .pulse-active {
            animation: pulse-ring 1s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/40 to-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-md overflow-hidden transition-all duration-300">

        <!-- Top Accent Gradient Bar -->
        <div class="h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

        <div class="p-8 sm:p-10">
            <!-- Header Icon & Title -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center justify-center text-3xl shadow-sm text-indigo-600">
                    🔐
                </div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Two-Factor Authentication</h2>
                <p class="text-sm text-slate-500 mt-2">
                    Enter the 6-digit verification code from your <strong class="text-slate-700">Google Authenticator</strong> app.
                </p>
            </div>

            <!-- 30-Second TOTP Pulse Ring (Feature 1) -->
            <div class="mb-6 bg-slate-50 border border-slate-100 rounded-xl p-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative w-10 h-10 flex items-center justify-center">
                        <svg class="w-10 h-10 transform -rotate-90" viewBox="0 0 44 44">
                            <circle cx="22" cy="22" r="18" stroke="#e2e8f0" stroke-width="3" fill="none" />
                            <circle id="timer-ring" cx="22" cy="22" r="18" stroke="#4f46e5" stroke-width="3.5" stroke-linecap="round" fill="none"
                                    stroke-dasharray="113.1" stroke-dashoffset="0" class="timer-circle" />
                        </svg>
                        <span id="timer-seconds" class="absolute font-mono-num font-bold text-xs text-indigo-700">30</span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-700" id="timer-label">TOTP Code Active</div>
                        <div class="text-[11px] text-slate-400">Refreshes every 30 seconds</div>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-indigo-100/70 text-indigo-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-ping"></span> Live
                </span>
            </div>

            <!-- Verification Form -->
            <form id="two-factor-form" method="POST" action="{{ route('2fa.verify') }}">
                @csrf
                <input type="hidden" name="code" id="hidden-code-input" value="">

                <!-- Discrete 6-Box OTP Input -->
                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 text-center">
                        6-Digit Security Code
                    </label>

                    <div class="flex justify-between gap-2 sm:gap-2.5" id="otp-boxes-container">
                        @for($i = 0; $i < 6; $i++)
                            <input type="text"
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   maxlength="1"
                                   data-index="{{ $i }}"
                                   autocomplete="off"
                                   class="otp-box w-12 h-14 sm:w-12 sm:h-14 text-center font-mono-num text-2xl font-bold text-slate-800 bg-slate-50 border-2 border-slate-200 rounded-xl outline-none transition duration-150 otp-input"
                                   {{ $i === 0 ? 'autofocus' : '' }}>
                        @endfor
                    </div>

                    @error('code')
                        <p class="text-red-500 text-xs font-semibold mt-2 text-center flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="text-[11px] text-slate-400 text-center mt-2">
                        Tip: You can paste your 6-digit code directly with <kbd class="px-1.5 py-0.5 bg-slate-100 rounded text-slate-600 font-mono text-[10px] border">Ctrl+V</kbd>
                    </p>
                </div>

                <!-- Trusted Device Card -->
                <div class="mb-6 bg-slate-50 border border-slate-200/80 rounded-xl p-4 transition hover:bg-slate-100/60">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox"
                               name="remember_device"
                               value="1"
                               class="w-4 h-4 mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 transition">
                        <div>
                            <span class="text-sm font-semibold text-slate-800 block">Remember this device for 30 days</span>
                            <span class="text-xs text-slate-500 block mt-0.5">
                                Skip 2FA verification on this browser for the next month. You can revoke it anytime.
                            </span>
                        </div>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        id="submit-btn"
                        class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-500/25 transition duration-200 flex items-center justify-center gap-2">
                    <span id="btn-text">Verify & Continue</span>
                    <svg id="btn-spinner" class="hidden w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </button>
            </form>

            <!-- Recovery Fallback -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500 mb-2">Lost access to your Authenticator app?</p>
                <a href="{{ route('2fa.recovery') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                    <span>🔑 Use an Emergency Recovery Code</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Smart OTP Input & Live TOTP Timer JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const boxes = Array.from(document.querySelectorAll('.otp-box'));
            const hiddenInput = document.getElementById('hidden-code-input');
            const form = document.getElementById('two-factor-form');
            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');

            function updateHiddenInput() {
                const code = boxes.map(b => b.value).join('');
                hiddenInput.value = code;
                return code;
            }

            function triggerAutoSubmit() {
                const code = updateHiddenInput();
                if (code.length === 6) {
                    btnText.textContent = 'Verifying...';
                    btnSpinner.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                    form.submit();
                }
            }

            // Keyboard events on each box
            boxes.forEach((box, idx) => {
                box.addEventListener('input', function (e) {
                    const val = this.value.replace(/[^0-9]/g, '');
                    this.value = val ? val[val.length - 1] : '';

                    if (this.value && idx < boxes.length - 1) {
                        boxes[idx + 1].focus();
                        boxes[idx + 1].select();
                    }

                    const fullCode = updateHiddenInput();
                    if (fullCode.length === 6) {
                        setTimeout(triggerAutoSubmit, 100);
                    }
                });

                box.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace') {
                        if (!this.value && idx > 0) {
                            boxes[idx - 1].focus();
                            boxes[idx - 1].value = '';
                            updateHiddenInput();
                        }
                    } else if (e.key === 'ArrowLeft' && idx > 0) {
                        boxes[idx - 1].focus();
                    } else if (e.key === 'ArrowRight' && idx < boxes.length - 1) {
                        boxes[idx + 1].focus();
                    }
                });

                // 1-Click Paste Support on ANY box
                box.addEventListener('paste', function (e) {
                    e.preventDefault();
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                    const digits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);

                    if (digits.length > 0) {
                        digits.split('').forEach((digit, i) => {
                            if (boxes[i]) {
                                boxes[i].value = digit;
                            }
                        });

                        const nextIdx = Math.min(digits.length, boxes.length - 1);
                        boxes[nextIdx].focus();

                        const fullCode = updateHiddenInput();
                        if (fullCode.length === 6) {
                            setTimeout(triggerAutoSubmit, 150);
                        }
                    }
                });
            });

            form.addEventListener('submit', function (e) {
                const code = updateHiddenInput();
                if (code.length < 6) {
                    e.preventDefault();
                    const firstEmpty = boxes.find(b => !b.value);
                    if (firstEmpty) firstEmpty.focus();
                }
            });

            // ==========================================
            // 30-Second TOTP Pulse Ring (Feature 1)
            // ==========================================
            const timerRing = document.getElementById('timer-ring');
            const timerSeconds = document.getElementById('timer-seconds');
            const timerLabel = document.getElementById('timer-label');
            const circumference = 113.1; // 2 * Math.PI * 18

            function updateTotpClock() {
                const now = Math.floor(Date.now() / 1000);
                const secondsRemaining = 30 - (now % 30);

                timerSeconds.textContent = secondsRemaining;
                const offset = circumference - (secondsRemaining / 30) * circumference;
                timerRing.style.strokeDashoffset = offset;

                if (secondsRemaining <= 5) {
                    timerRing.style.stroke = '#ef4444'; // Red
                    timerLabel.textContent = 'Expiring Soon!';
                    timerLabel.className = 'text-xs font-bold text-red-600';
                } else if (secondsRemaining <= 10) {
                    timerRing.style.stroke = '#f59e0b'; // Amber
                    timerLabel.textContent = 'Expiring in ' + secondsRemaining + 's';
                    timerLabel.className = 'text-xs font-bold text-amber-600';
                } else {
                    timerRing.style.stroke = '#4f46e5'; // Indigo
                    timerLabel.textContent = 'TOTP Code Active';
                    timerLabel.className = 'text-xs font-bold text-slate-700';
                }
            }

            updateTotpClock();
            setInterval(updateTotpClock, 1000);
        });
    </script>
</body>
</html>