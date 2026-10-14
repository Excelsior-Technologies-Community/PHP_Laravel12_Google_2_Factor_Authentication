<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                    <span>🛡️</span> Admin Dashboard
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    User Management, 2FA Security Enforcement & Authentication Controls
                </p>
            </div>

            <!-- Mandatory 2FA Policy Toggle (Feature 4) -->
            <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full {{ $policyEnforced ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                    <span class="text-xs font-bold text-gray-700">
                        Mandatory 2FA Policy:
                        <span class="{{ $policyEnforced ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $policyEnforced ? 'ACTIVE (All Users)' : 'DISABLED' }}
                        </span>
                    </span>
                </div>
                <form method="POST" action="{{ route('admin.toggle-2fa-policy') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg transition {{ $policyEnforced ? 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm' }}">
                        {{ $policyEnforced ? 'Turn OFF' : 'Turn ON' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">

            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-5 py-3 rounded-lg shadow">
                {{session('success')}}
            </div>
            @endif

            <!-- 2FA Vulnerability Security Banner (Feature 3) -->
            @if(!auth()->user()->google2fa_enabled)
            <div class="mb-8 bg-gradient-to-r from-amber-500 via-rose-500 to-red-600 rounded-2xl p-5 text-white shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-2xl shrink-0 shadow-inner">
                        ⚠️
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="bg-white/20 px-2 py-0.5 rounded text-[11px] font-extrabold uppercase tracking-wider">High Risk</span>
                            <h3 class="font-extrabold text-lg tracking-tight">Your Account is at Risk! (Security Alert)</h3>
                        </div>
                        <p class="text-sm text-white/90 mt-1">
                            Two-Factor Authentication is not enabled on your account. Protect your account from unauthorized access and credential theft by enabling 2FA now.
                        </p>
                    </div>
                </div>
                <a href="{{ route('2fa.setup') }}" class="bg-white text-slate-900 hover:bg-slate-100 font-extrabold text-sm px-5 py-2.5 rounded-xl shadow-md transition shrink-0 flex items-center gap-1.5">
                    <span>🛡️ Enable 2FA Protection</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
            @endif



            <!-- Dashboard Cards -->

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">


                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">

                    <div class="flex justify-between">

                        <div>

                            <p class="text-gray-500">
                                Total Users
                            </p>

                            <h2 class="text-4xl font-bold text-gray-800 mt-2">
                                {{$totalUsers}}
                            </h2>

                        </div>

                        <div class="bg-blue-100 text-blue-600 p-4 rounded-full text-2xl">
                            👥
                        </div>

                    </div>

                </div>




                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">

                    <div class="flex justify-between">

                        <div>

                            <p class="text-gray-500">
                                Active Users
                            </p>

                            <h2 class="text-4xl font-bold text-green-600 mt-2">
                                {{$activeUsers}}
                            </h2>

                        </div>


                        <div class="bg-green-100 text-green-600 p-4 rounded-full text-2xl">
                            ✓
                        </div>


                    </div>

                </div>





                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">

                    <div class="flex justify-between">

                        <div>

                            <p class="text-gray-500">
                                Inactive Users
                            </p>

                            <h2 class="text-4xl font-bold text-red-600 mt-2">
                                {{$inactiveUsers}}
                            </h2>

                        </div>


                        <div class="bg-red-100 text-red-600 p-4 rounded-full text-2xl">
                            !
                        </div>


                    </div>

                </div>





                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">

                    <div class="flex justify-between">

                        <div>

                            <p class="text-gray-500">
                                2FA Enabled
                            </p>

                            <h2 class="text-4xl font-bold text-purple-600 mt-2">
                                {{$twoFactorUsers}}
                            </h2>

                        </div>


                        <div class="bg-purple-100 text-purple-600 p-4 rounded-full text-2xl">
                            🔐
                        </div>


                    </div>

                </div>


            </div>





            <!-- Search + Export -->

            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">


                <div class="flex flex-col md:flex-row justify-between gap-4">


                    <form method="GET"
                        action="{{route('dashboard')}}"
                        class="flex gap-3 w-full">


                        <input

                            type="text"

                            name="search"

                            value="{{request('search')}}"

                            placeholder="Search name or email..."

                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">


                        <button

                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg">

                            Search

                        </button>


                    </form>



                    <a href="{{route('users.export')}}"

                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-center">

                        ⬇ Export CSV

                    </a>


                </div>


            </div>





            <!-- Users Table -->


            <div class="bg-white shadow-xl rounded-xl overflow-hidden">


                <div class="px-6 py-4 border-b">

                    <h3 class="font-bold text-lg">
                        User Management
                    </h3>

                </div>



                <div class="overflow-x-auto">


                    <table class="w-full">


                        <thead class="bg-gray-50">


                            <tr>


                                <th class="px-6 py-4 text-left text-gray-600">
                                    Name
                                </th>


                                <th class="px-6 py-4 text-left text-gray-600">
                                    Email
                                </th>


                                <th class="px-6 py-4 text-left text-gray-600">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-left text-gray-600">
                                    2FA Security
                                </th>

                                <th class="px-6 py-4 text-left text-gray-600">
                                    Last Login
                                </th>


                                <th class="px-6 py-4 text-left text-gray-600">
                                    Action
                                </th>


                            </tr>


                        </thead>




                        <tbody>


                            @foreach($users as $user)


                            <tr class="border-b hover:bg-gray-50 transition">


                                <td class="px-6 py-4 font-semibold">

                                    {{$user->name}}

                                </td>



                                <td class="px-6 py-4 text-gray-600">

                                    {{$user->email}}

                                </td>




                                <td class="px-6 py-4">


                                    @if($user->status=='active')


                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                                        Active

                                    </span>


                                    @else


                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">

                                        Inactive

                                    </span>


                                    @endif


                                </td>

                                <td class="px-6 py-4">
                                    @if($user->google2fa_enabled)
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-xs font-bold border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> 2FA Active
                                            </span>
                                            <form method="POST" action="{{ route('admin.users.reset-2fa', $user->id) }}" onsubmit="return confirm('Emergency 2FA Reset: Are you sure you want to reset Two-Factor Authentication for {{ $user->name }}? Their secret and recovery codes will be erased, and they will be able to log in directly with their password.');">
                                                @csrf
                                                <button type="submit" class="text-[11px] font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 px-2 py-0.5 rounded-md border border-amber-300 transition shadow-2xs flex items-center gap-1" title="Emergency 2FA Reset">
                                                    <span>🔄 Reset</span>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full text-xs font-medium">
                                            Disabled
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-gray-600">


                                    @if($user->last_login_at)

                                    {{$user->last_login_at->format('d M Y h:i A')}}

                                    @else

                                    Never

                                    @endif


                                </td>




                                <td class="px-6 py-4">


                                    <div class="flex gap-3 items-center">


                                        <form method="POST"
                                            action="{{route('user.status',$user->id)}}">


                                            @csrf


                                            <select name="status"

                                                onchange="this.form.submit()"

                                                class="border rounded-lg px-3 py-2">


                                                <option value="active"
                                                    {{$user->status=='active'?'selected':''}}>

                                                    Active

                                                </option>


                                                <option value="inactive"
                                                    {{$user->status=='inactive'?'selected':''}}>

                                                    Inactive

                                                </option>


                                            </select>


                                        </form>





                                        <form method="POST"

                                            action="{{route('users.delete',$user->id)}}">


                                            @csrf

                                            @method('DELETE')


                                            <button

                                                onclick="return confirm('Delete this user?')"

                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">

                                                Delete

                                            </button>


                                        </form>


                                    </div>


                                </td>


                            </tr>


                            @endforeach


                        </tbody>


                    </table>


                </div>



                <div class="p-5">

                    {{$users->links()}}

                </div>


            </div>



        </div>

    </div>


</x-app-layout>