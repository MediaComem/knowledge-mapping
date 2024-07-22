<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="px-6 md:px-12 py-8 bg-white border-b border-gray-200 pb-10">
        <div class="flex justify-between">
            <h1 class="text-2xl mb-2">Users</h1>
        </div>
        <div class="mt-6 overflow-auto">
            <table class="table-fixed min-w-full">
                <thead>
                    <tr class="text-left h-12 bg-gray-100">
                        <th class="w-1/4 px-4">Name</th>
                        <th class="w-1/3 px-4">Email</th>
                        <th class="w-1/4 px-4">Role</th>
                        <th class="w-1/4"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="h-12 border-b">
                        <td class="px-4">
                            {{$user->name}}
                            @if($user->id === auth()->user()->id)
                            <span class="font-bold text-gray-400">(You)</span>
                            @endif
                        </td>
                        <td class="px-4">{{$user->email}}</td>
                        <td class="px-4 text-sm">
                            @if($user->is_admin)
                            <span class="px-2 py-1 bg-red-200 rounded">Administrator</span>
                            @else
                            <span class="px-2 py-1 bg-blue-200 rounded">Standard</span>
                            @endif
                        </td>
                        <td class="px-4 text-right">
                            <div class="flex justify-end">
                                @if($user->id !== auth()->user()->id)
                                <form class="mr-2" method="post" action="{{route('delete-user', ['user_id' => $user->id])}}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete the user \n{{$user->name}} ?')" class="text-red-700">Delete</button>
                                </form>
                                |
                                @endif
                                <a href="{{route('edit_user', ['user_id' => $user->id])}}" class="text-indigo-700 ml-2">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-right">
                <a href="{{route('add_user')}}"><button type="button" class="mt-6 mb-2 ml-1 bg-indigo-400 hover:bg-indigo-500 text-white p-3 flex focus:ring-4 focus:ring-indigo-300 rounded-lg px-5 py-2.5 focus:outline-none">Add</button></a>
            </div>
        </div>
    </div>
</div>
