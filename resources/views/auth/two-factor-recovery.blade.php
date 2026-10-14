<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Emergency Recovery Codes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-code { font-family: 'JetBrains Mono', monospace; }
        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .print-card { box-shadow: none !important; border: 1px solid #ccc !important; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/40 to-slate-100 min-h-screen py-10 px-4 flex items-center justify-center">

    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden print-card">

        <!-- Top Accent Gradient -->
        <div class="h-2.5 bg-gradient-to-r from-red-500 via-amber-500 to-indigo-500 no-print"></div>

        <div class="p-6 sm:p-10">

            <div class="text-center mb-6">
                <div class="w-14 h-14 mx-auto mb-3 bg-amber-50 border border-amber-200 text-amber-600 rounded-2xl flex items-center justify-center text-3xl shadow-sm">
                    🔑
                </div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Emergency Recovery Codes</h1>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Use these backup codes if you ever lose your phone or cannot receive TOTP codes.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-5 bg-green-50 border-l-4 border-green-500 text-green-800 p-3.5 rounded-r-xl text-xs font-semibold shadow-sm flex items-center gap-2 no-print">
                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Warning Callout -->
            <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <span class="text-xl">⚠️</span>
                    <div class="text-xs text-amber-800">
                        <strong>Crucial Security Notice:</strong> Each code can be used only once. Once entered, that code is invalidated. Keep them stored safely.
                    </div>
                </div>
            </div>

            <!-- Codes Box -->
            <div class="bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl p-5 mb-6">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2 mb-3">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Available Single-Use Codes</span>
                    <span class="text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200">{{ count($recoveryCodes) }} Codes</span>
                </div>

                <div class="grid grid-cols-2 gap-2.5 font-mono-code font-bold text-sm text-slate-800">
                    @foreach($recoveryCodes as $index => $code)
                        <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-2xs flex justify-between items-center select-all">
                            <span>{{ $code }}</span>
                            <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">#{{ $index + 1 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Toolbar (Download TXT, Print PDF, Copy All) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 mb-6 no-print">
                <button type="button" onclick="downloadTxt()" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold py-2.5 px-3 rounded-xl transition shadow-sm flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Download (.txt)</span>
                </button>

                <button type="button" onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 px-3 rounded-xl transition shadow-sm flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Print / PDF</span>
                </button>

                <button type="button" onclick="copyCodes()" id="copy-codes-btn" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-bold py-2.5 px-3 rounded-xl transition shadow-sm flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <span id="copy-codes-text">Copy All</span>
                </button>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-5 border-t border-slate-100 no-print">
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto text-center px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border rounded-xl hover:bg-slate-50 transition">
                    ← Back to Dashboard
                </a>

                <form method="POST" action="{{ route('2fa.recovery.generate') }}" class="w-full sm:w-auto" onsubmit="return confirm('Generating new recovery codes will permanently invalidate your old codes. Are you sure?');">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-300 rounded-xl transition">
                        🔄 Regenerate Codes
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        const codesList = @json($recoveryCodes);
        const userEmail = "{{ auth()->user()->email }}";

        function copyCodes() {
            navigator.clipboard.writeText(codesList.join('\n')).then(() => {
                const txt = document.getElementById('copy-codes-text');
                txt.textContent = 'Copied! ✓';
                setTimeout(() => { txt.textContent = 'Copy All'; }, 2000);
            });
        }

        function downloadTxt() {
            let content = "=====================================================\n";
            content += "TWO-FACTOR RECOVERY CODES\n";
            content += "=====================================================\n\n";
            content += "Account: " + userEmail + "\n";
            content += "Generated on: " + new Date().toLocaleString() + "\n\n";
            content += "Codes:\n";
            codesList.forEach((c, idx) => {
                content += (idx + 1) + ". " + c + "\n";
            });
            content += "\nEach code is valid for one-time emergency login.\n";
            content += "=====================================================\n";

            const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = '2fa-recovery-codes.txt';
            link.click();
            URL.revokeObjectURL(link.href);
        }
    </script>
</body>
</html>