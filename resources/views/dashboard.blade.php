<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4">

            {{-- Flash Messages --}}
            @foreach (['success','warning','error'] as $msg)
                @if(session($msg))
                    <div class="mb-4 p-3 rounded 
                        {{ $msg == 'success' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $msg == 'warning' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $msg == 'error' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ session($msg) }}
                    </div>
                @endif
            @endforeach

            {{-- 2FA Box --}}
            <div class="bg-white border rounded p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">
                    Two-Factor Authentication
                </h3>

                @if(auth()->user()->google2fa_enabled)
                    <p class="text-green-600 mb-4">
                        2FA is enabled on your account.
                    </p>

                    <a href="{{ route('2fa.recovery') }}"
                       class="block w-full text-center mb-3 px-4 py-2 border rounded text-blue-600 hover:bg-blue-50">
                        View Recovery Codes
                    </a>

                    <form method="POST" action="{{ route('2fa.disable') }}">
                        @csrf
                        <input type="password"
                               name="password"
                               placeholder="Confirm Password"
                               required
                               class="w-full mb-3 px-3 py-2 border rounded">

                        <button class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Disable 2FA
                        </button>
                    </form>
                @else
                    <p class="text-gray-600 mb-4">
                        Two-factor authentication is not enabled.
                    </p>

                    <a href="{{ route('2fa.setup') }}">                       
                        Enable 2FA
                    </a>
                @endif
            </div>

            {{-- Account Info --}}
            <div class="bg-white border rounded p-6">
                <h3 class="text-lg font-semibold mb-4">
                    Account Information
                </h3>

                <div class="space-y-2 text-sm">
                    <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Member Since:</strong> {{ auth()->user()->created_at->format('d M Y') }}</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
