<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Two-Factor Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">
                Setup Two-Factor Authentication
            </h2>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-8">
                <p class="text-gray-600 mb-6">
                    <strong>Step 1:</strong> Install Google Authenticator on your phone:
                </p>
                <div class="flex justify-center space-x-4 mb-6">
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" 
                       target="_blank" 
                       class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">
                        Android
                    </a>
                    <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" 
                       target="_blank"
                       class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">
                        iOS
                    </a>
                </div>

                <p class="text-gray-600 mb-4">
                    <strong>Step 2:</strong> Scan this QR code with Google Authenticator:
                </p>
                
                <div class="flex justify-center mb-6 p-4 bg-white border rounded-lg">
                    {!! $qrCode !!}
                </div>

                <p class="text-gray-600 mb-2">
                    <strong>Or enter this code manually:</strong>
                </p>
                <div class="bg-gray-50 p-4 rounded-lg border mb-6">
                    <p class="font-mono text-center text-lg tracking-wider font-bold text-gray-800">
                        {{ $secret }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('2fa.enable') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        <strong>Step 3:</strong> Enter the 6-digit code from Google Authenticator
                    </label>
                    <input 
                        type="text" 
                        id="code" 
                        name="code" 
                        inputmode="numeric" 
                        pattern="[0-9]*" 
                        maxlength="6" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center text-xl tracking-widest"
                        placeholder="123456"
                        required
                        autofocus
                        autocomplete="off"
                    >
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium transition">
                    Enable Two-Factor Authentication
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="font-bold text-gray-900 mb-3">⚠️ Important: Save Your Recovery Codes</h3>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-red-700 mb-3">
                        Save these recovery codes in a secure place. You can use them to access your account if you lose your phone.
                    </p>
                    <div class="bg-white p-3 rounded border">
                        <div class="font-mono text-sm space-y-1">
                            @foreach($recoveryCodes as $code)
                                <div class="flex items-center">
                                    <span class="text-gray-800">{{ $code }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-xs text-red-600 mt-3">
                        <strong>Note:</strong> Each code can only be used once.
                    </p>
                </div>
                
                <div class="text-center">
                    <a href="{{ route('dashboard') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>