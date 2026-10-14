<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                    <span>💻</span> {{ __('Trusted Devices & Sessions') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Manage all browsers and devices that can bypass Two-Factor Authentication.
                </p>
            </div>

            @if($totalDevices > 0)
                <div class="flex gap-2">
                    @if($hasOtherDevices)
                        <form method="POST" action="{{ route('trusted.devices.revoke-others') }}" onsubmit="return confirm('Revoke access for all other devices except this current session?');">
                            @csrf
                            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Revoke Other Devices
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('trusted.devices.revoke-all') }}" onsubmit="return confirm('Revoke ALL trusted devices? You will be prompted for 2FA on every login.');">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Revoke All
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-r-lg shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Info Overview Banner -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-inner">
                        🛡️
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">30-Day Trusted Device Security</h3>
                        <p class="text-sm text-gray-500">
                            When you check "Remember this device for 30 days" during 2FA verification, a secure cryptographic token is stored on your device.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-6 text-sm">
                    <div class="text-center px-4 py-2 bg-gray-50 rounded-lg border border-gray-100">
                        <span class="block text-2xl font-bold text-blue-600">{{ $totalDevices }}</span>
                        <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Active Devices</span>
                    </div>
                </div>
            </div>

            <!-- Devices List -->
            @if($devices->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center text-3xl">
                        🔒
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">No Trusted Devices Active</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">
                        You have not remembered any devices yet. On your next login, check "Remember this device for 30 days" to list it here.
                    </p>
                    <a href="{{ route('2fa.setup') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition shadow-sm">
                        <span>Check 2FA Setup</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($devices as $device)
                        @php
                            $isMobile = str_contains(strtolower($device->device_name), 'mobile') || str_contains(strtolower($device->platform), 'android') || str_contains(strtolower($device->platform), 'ios');
                            $isTablet = str_contains(strtolower($device->device_name), 'tablet') || str_contains(strtolower($device->platform), 'ipad');
                            $deviceIcon = $isTablet ? '📟' : ($isMobile ? '📱' : '💻');
                        @endphp
                        <div class="bg-white rounded-xl shadow-sm border {{ $device->is_current ? 'border-green-400 ring-2 ring-green-100' : 'border-gray-200' }} p-6 flex flex-col justify-between hover:shadow-md transition">
                            <div>
                                <!-- Header & Current Badge -->
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg {{ $device->is_current ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center text-xl">
                                            {{ $deviceIcon }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-base leading-tight">
                                                {{ $device->device_name }}
                                            </h4>
                                            <span class="text-xs text-gray-500 font-medium">
                                                {{ $device->platform }} &bull; {{ $device->browser }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($device->is_current)
                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full border border-green-200 shrink-0">
                                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> This Device
                                        </span>
                                    @endif
                                </div>

                                <!-- Metadata -->
                                <div class="space-y-2 mt-4 pt-3 border-t border-gray-100 text-xs text-gray-600">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-400 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"></path></svg>
                                            IP Address:
                                        </span>
                                        <span class="font-mono bg-gray-50 px-2 py-0.5 rounded text-gray-700 border border-gray-100">{{ $device->ip_address }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-400 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Last Active:
                                        </span>
                                        <span class="font-semibold text-gray-700">{{ $device->last_used_at ? $device->last_used_at->diffForHumans() : 'Just now' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-400 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Expires On:
                                        </span>
                                        <span class="text-gray-500">{{ $device->expires_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Revoke Button -->
                            <div class="mt-5 pt-3 border-t border-gray-100 flex justify-end">
                                <form method="POST" action="{{ route('trusted.devices.revoke', $device->id) }}" onsubmit="return confirm('Revoke this device? Two-factor verification will be required on its next login.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-semibold hover:bg-red-50 px-3 py-1.5 rounded-lg transition flex items-center gap-1 border border-transparent hover:border-red-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Revoke Access
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
