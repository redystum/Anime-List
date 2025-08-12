@extends('layout')

@section('content')
    <div class="min-h-screen bg-neutral-50 dark:bg-neutral-900 transition-colors duration-200">
        <div class="container mx-auto px-4 py-8 grid gap-y-8">

            <livewire:anime-list list_name="{{$Anime::LIST_WATCHING}}" icon="eye" title="Watching now"/>
            <livewire:anime-list list_name="{{$Anime::LIST_WATCH}}" icon="tv" title="To Watch"
                                 :haveAdd="true"/>
            <livewire:anime-list list_name="{{$Anime::LIST_WATCHED}}" icon="check" title="Already Watched"/>
            <livewire:anime-list list_name="{{$Anime::LIST_FAVORITE}}" icon="heart" title="Favorites"/>

        </div>
    </div>

    <livewire:delete-dialog/>
    <livewire:info-modal/>
    <livewire:search-new-anime/>
    <livewire:no-m-a-l-client-id-found />
@endsection

@section('scripts')
    <script>
        function focusOnSearch() {
            const searchInput = document.querySelector('nav input[type="text"]');
            if (searchInput) {
                searchInput.focus();
            }
        }
    </script>
@endsection