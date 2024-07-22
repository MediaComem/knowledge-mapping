@extends('layouts.admin')
@section('pageTitle', 'Author')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Dashboard') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="px-6 md:px-12 py-8 bg-white border-b border-gray-200 pb-10">
        <div class="justify-between">
            <a href="{{route('users_list')}}" tabindex="-1"><button tabindex="7" type="button" class="mb-6 mr-2 bg-none hover:bg-indigo-50 text-indigo-400 border border-indigo-400 flex focus:ring-4 focus:ring-indigo-300 rounded-lg px-5 py-2.5 focus:outline-none">Back</button></a>
            <h1 class="text-2xl mb-2">New user</h1>
            <!-- form to insert a new user with error handling -->
            <form method="POST" action="{{route("store_user")}}" class="mt-6">
                    @csrf
                    <div class="mb-5 flex flex-col">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" tabindex="1" class="border border-gray-300 focus:border-none p-2 rounded mt-1 focus:outline-none focus:ring-indigo-400 focus:ring-opacity-50" value="{{old('name')}}">
                        @error('name')
                            <div class="text-red-500 mt-2 text-sm">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-5 flex flex-col">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" tabindex="2" class="border border-gray-300 focus:border-none p-2 rounded mt-1 focus:outline-none focus:ring-indigo-400 focus:ring-opacity-50" value="{{old('email')}}">
                        @error('email')
                            <div class="text-red-500 mt-2 text-sm">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-5 flex flex-col">
                        <label for="password">Password <span class="text-sm text-gray-600">(minimum 8 characters, mixed case)</span></label>
                        <input type="password" name="password" id="password" autocomplete="new-password" tabindex="3" class="border border-gray-300 focus:border-none p-2 rounded mt-1 focus:outline-none focus:ring-indigo-400 focus:ring-opacity-50">
                        @error('password')
                            <div class="text-red-500 mt-2 text-sm">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-5 flex flex-col">
                        <label for="password_confirmation">Confirm password</label>
                        <input type="password" name="password_confirmation" autocomplete="new-password" tabindex="4" id="password_confirmation" class="border border-gray-300 focus:border-none p-2 rounded mt-1 focus:outline-none focus:ring-indigo-400 focus:ring-opacity-50">
                        @error('password_confirmation')
                            <div class="text-red-500 mt-2 text-sm">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="items-start mb-5">
                        <div class="flex items-center h-5">
                            <input id="is_admin" name="is_admin" tabindex="5"  type="checkbox" value="1" class="w-4 h-4 borde text-indigo-400 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-indigo-300" @if(old('is_admin')) checked @endif>
                            <label for="is_admin" class="ml-2 text-gray-900 dark:text-gray-300">Administrateur·trice</label>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">ⓘ Administrators are able to manage users</p>
                        @error('is_admin')
                            <span class="invalid-feedback mt-2 text-sm text-red-600 dark:text-red-500" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                <div class="flex">
                    <button type="submit" tabindex="6" class="bg-indigo-400 hover:bg-indigo-500 text-white p-3 flex focus:ring-4 focus:ring-indigo-300 rounded-lg px-5 py-2.5 focus:outline-none">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

    </div>
</div>
@endsection
