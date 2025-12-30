<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Recovery Codes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Recovery Codes</h2>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <div class="bg-red-50 p-4 rounded border border-red-200 mb-4">
                    <p class="text-sm text-red-700">
                        <strong>Important:</strong> Save these recovery codes in a secure place. 
                        Each code can only be used once.
                    </p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded">
                    <div class="font-mono text-sm space-y-2">
                        @foreach($recoveryCodes as $code)
                            <div class="flex justify-between items-center">
                                <span>{{ $code }}</span>
                                <span class="text-xs bg-gray-200 px-2 py-1 rounded">Unused</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('dashboard') }}" 
                   class="flex-1 bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600 transition text-center">
                    Back to Dashboard
                </a>
                
                <form method="POST" action="{{ route('2fa.recovery.generate') }}" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition"
                            onclick="return confirm('Generating new recovery codes will invalidate all previous codes. Continue?')">
                        Generate New Codes
                    </button>
                </form>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('2fa.setup') }}" class="text-sm text-blue-500 hover:text-blue-700">
                    Two-Factor Settings
                </a>
            </div>
        </div>
    </div>
</body>
</html>