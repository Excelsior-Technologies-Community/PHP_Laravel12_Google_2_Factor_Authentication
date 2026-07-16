<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Two-Factor Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Two-Factor Authentication</h2>

            <p class="text-gray-600 mb-6 text-center">
                Please enter the 6-digit code from your authenticator app
            </p>

            <form method="POST" action="{{ route('2fa.verify') }}">
                @csrf

                <div class="mb-6">
                    <label for="code" class="block text-gray-700 text-sm font-bold mb-2">
                        6-digit code
                    </label>

                    <input
                        type="text"
                        id="code"
                        name="code"
                        maxlength="6"
                        pattern="\d{6}"
                        class="w-full px-3 py-3 border rounded-lg focus:outline-none focus:border-blue-500 text-center text-xl tracking-widest"
                        required
                        autofocus
                        placeholder="000000">

                    @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trusted Device -->
                <div class="mb-6">
                    <label class="flex items-center">

                        <input
                            type="checkbox"
                            name="remember_device"
                            value="1"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                        <span class="ml-2 text-sm text-gray-700">
                            Remember this device for 30 days
                        </span>

                    </label>

                    <p class="text-xs text-gray-500 mt-2">
                        This browser will not ask for a verification code again for the next 30 days.
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition font-bold">

                    Verify & Continue

                </button>

            </form>

            <div class="mt-6 pt-6 border-t">
                <p class="text-gray-600 text-sm mb-2">Lost access to your authenticator app?</p>
                <a href="{{ route('2fa.recovery') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                    Use a recovery code instead
                </a>
            </div>
        </div>
    </div>
</body>

</html>