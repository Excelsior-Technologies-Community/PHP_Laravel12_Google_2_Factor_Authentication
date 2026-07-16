<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Authentication History</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

    <div class="container mx-auto px-6 py-8">

        <!-- Header -->

        <div class="flex items-center justify-between mb-8">

            <div>

                <h1 class="text-3xl font-bold text-gray-800">
                    Authentication History
                </h1>

                <p class="text-gray-500 mt-2">
                    Monitor all authentication activities in your application.
                </p>

            </div>

            <a
                href="{{ route('dashboard') }}"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">

                Back to Dashboard

            </a>

        </div>

        <!-- Statistics -->

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <div class="bg-white rounded-xl shadow p-6">

                <p class="text-sm text-gray-500">
                    Total Logins
                </p>

                <h2 class="text-4xl font-bold text-blue-600 mt-3">
                    {{ $totalLogins }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <p class="text-sm text-gray-500">
                    Trusted Device
                </p>

                <h2 class="text-4xl font-bold text-green-600 mt-3">
                    {{ $trustedLogins }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <p class="text-sm text-gray-500">
                    Recovery Code
                </p>

                <h2 class="text-4xl font-bold text-yellow-600 mt-3">
                    {{ $recoveryLogins }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <p class="text-sm text-gray-500">
                    Failed Attempts
                </p>

                <h2 class="text-4xl font-bold text-red-600 mt-3">
                    {{ $failedLogins }}
                </h2>

            </div>

        </div>

        <!-- Search & Filters -->

        <div class="bg-white rounded-xl shadow p-6 mb-8">

            <form
                method="GET"
                action="{{ route('login.history') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Search -->

                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">

                        Search User

                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Name or Email"
                        class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-200">

                </div>

                <!-- Login Method -->

                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">

                        Login Method

                    </label>

                    <select
                        name="method"
                        class="w-full border rounded-lg px-4 py-2">

                        <option value="">All</option>

                        <option
                            value="Password + 2FA"
                            @selected(request('method')=='Password + 2FA' )>

                            Password + 2FA

                        </option>

                        <option
                            value="Trusted Device"
                            @selected(request('method')=='Trusted Device' )>

                            Trusted Device

                        </option>

                        <option
                            value="Recovery Code"
                            @selected(request('method')=='Recovery Code' )>

                            Recovery Code

                        </option>

                    </select>

                </div>

                <!-- Status -->

                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">

                        Status

                    </label>

                    <select
                        name="status"
                        class="w-full border rounded-lg px-4 py-2">

                        <option value="">All</option>

                        <option
                            value="Success"
                            @selected(request('status')=='Success' )>

                            Success

                        </option>

                        <option
                            value="Failed"
                            @selected(request('status')=='Failed' )>

                            Failed

                        </option>

                    </select>

                </div>

                <!-- Buttons -->

                <div class="flex items-end gap-3">

                    <button
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

                        Search

                    </button>

                    <a
                        href="{{ route('login.history') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                        Reset

                    </a>

                </div>

            </form>

        </div>

        <!-- Login History Table -->

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                User
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                Login Method
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                Status
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                IP Address
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                Browser
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                Login Time
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">

                        @forelse($loginHistories as $history)

                        <tr class="hover:bg-gray-50">

                            <!-- User -->

                            <td class="px-6 py-4">

                                <div class="font-semibold text-gray-800">

                                    {{ $history->user?->name ?? 'Deleted User' }}

                                </div>

                                <div class="text-sm text-gray-500">

                                    {{ $history->user?->email ?? '-' }}

                                </div>

                            </td>

                            <!-- Login Method -->

                            <td class="px-6 py-4">

                                @if($history->login_method == 'Password + 2FA')

                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">

                                    Password + 2FA

                                </span>

                                @elseif($history->login_method == 'Trusted Device')

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">

                                    Trusted Device

                                </span>

                                @else

                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">

                                    Recovery Code

                                </span>

                                @endif

                            </td>

                            <!-- Status -->

                            <td class="px-6 py-4">

                                @if($history->status == 'Success')

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">

                                    Success

                                </span>

                                @else

                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">

                                    Failed

                                </span>

                                @endif

                            </td>

                            <!-- IP -->

                            <td class="px-6 py-4 text-gray-700">

                                {{ $history->ip_address }}

                            </td>

                            <!-- Browser -->

                            <td class="px-6 py-4 text-gray-700">

                                <div class="max-w-xs truncate">

                                    {{ $history->user_agent }}

                                </div>

                            </td>

                            <!-- Login Time -->

                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-800">

                                    {{ $history->logged_in_at->format('d M Y') }}

                                </div>

                                <div class="text-sm text-gray-500">

                                    {{ $history->logged_in_at->format('h:i A') }}

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="6" class="text-center py-12">

                                <div class="text-5xl mb-3">

                                    🔐

                                </div>

                                <h3 class="text-xl font-semibold text-gray-700">

                                    No Authentication History Found

                                </h3>

                                <p class="text-gray-500 mt-2">

                                    Login activities will appear here.

                                </p>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->

            <div class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 bg-gray-50 border-t">

                <div class="text-sm text-gray-600 mb-3 md:mb-0">

                    Showing

                    <span class="font-semibold">
                        {{ $loginHistories->firstItem() ?? 0 }}
                    </span>

                    to

                    <span class="font-semibold">
                        {{ $loginHistories->lastItem() ?? 0 }}
                    </span>

                    of

                    <span class="font-semibold">
                        {{ $loginHistories->total() }}
                    </span>

                    records

                </div>

                <div>

                    {{ $loginHistories->links() }}

                </div>

            </div>

        </div>

        <!-- Footer -->

        <div class="mt-8 text-center text-sm text-gray-500">

            Authentication History • Laravel 12 Google Two-Factor Authentication

        </div>

    </div>

</body>

</html>