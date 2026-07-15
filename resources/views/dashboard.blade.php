<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <h2 class="font-bold text-2xl text-gray-800">
                Admin Dashboard
            </h2>

        </div>

    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-4">


            @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-5 py-3 rounded-lg shadow">

                {{session('success')}}

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