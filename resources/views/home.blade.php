@extends('layout')

@section('content')
    <div class="min-h-screen bg-neutral-50 dark:bg-neutral-900 transition-colors duration-200">
        <div class="container mx-auto px-4 py-8 grid gap-y-8">

            <x-list>
                <div class="flex items-center space-x-4 text-xl">
                    <i class="fas fa-eye"></i>
                    <p>Watching now</p>
                </div>
            </x-list>
            <x-list>
                <div class="flex items-center space-x-4 text-xl">
                    <i class="fas fa-tv"></i>
                    <p>To Watch</p>
                </div>
            </x-list>
            <x-list>
                <div class="flex items-center space-x-4 text-xl">
                    <i class="fas fa-check"></i>
                    <p>Already Watched</p>
                </div>
            </x-list>
            <x-list>
                <div class="flex items-center space-x-4 text-xl">
                    <i class="fas fa-heart"></i>
                    <p>Favorites</p>
                </div>
            </x-list>

        </div>
    </div>
@endsection