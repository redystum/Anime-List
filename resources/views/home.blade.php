@extends('layout')

@section('content')
    <div class="min-h-screen bg-neutral-50 dark:bg-neutral-900 transition-colors duration-200">
        <div class="container mx-auto px-4 py-8 grid gap-y-8">

            <livewire:anime-list list_name="watching" icon="eye" title="Watching now"/>
            <livewire:anime-list list_name="watch" icon="tv" title="To Watch"/>
            <livewire:anime-list list_name="watched" icon="check" title="Already Watched"/>
            <livewire:anime-list list_name="favorites" icon="heart" title="Favorites"/>

        </div>
    </div>
@endsection