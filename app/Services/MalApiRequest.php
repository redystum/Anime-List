<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\Genre;
use App\Models\Image;
use App\Models\RelatedAnime;
use App\Models\Studio;
use App\Models\Token;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MalApiRequest
{

    private static ?string $client_id = null;
    private static string $get_anime_list_url = "https://api.myanimelist.net/v2/anime";
    private static string $get_anime_url = "https://api.myanimelist.net/v2/anime/%d?fields=id,title,main_picture,alternative_titles,start_date,end_date,synopsis,mean,num_scoring_users,nsfw,media_type,status,genres,num_episodes,broadcast,average_episode_duration,pictures,background,related_anime,studios,rating&nsfw=true";

    private static function getToken(): ?string
    {
        if (self::$client_id === null || empty(self::$client_id)) {
            self::$client_id = config('app.api_keys.mal_client_id');

            if (empty(self::$client_id)) {
                self::$client_id = Token::getClientId();
                if (empty(self::$client_id)) {
                    return null;
                }
            }
        }
        return self::$client_id;
    }

    public static function testToken($token = null): bool
    {
        $token = $token ?? self::getToken();

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'X-MAL-CLIENT-ID' => $token,
            ])->get(self::$get_anime_list_url, [
                'q' => 'evangelion',
                'limit' => 1,
            ]);
        } catch (ConnectionException $e) {
            return false;
        }

        return $response->successful();
    }

    public static function searchAnime(string $animeName): array
    {
        if (empty($animeName)) {
            return [];
        }

        $token = self::getToken();

        if (empty($token)) {
            return ['error' => 'No MAL Client ID found', 'message' => 'Please set your MAL Client ID in the application configuration.', 'need_token' => true];
        }

        try {
            $response = Http::withHeaders([
                'X-MAL-CLIENT-ID' => $token,
            ])->get(self::$get_anime_list_url, [
                'q' => $animeName,
                'limit' => 15,
            ]);
        } catch (ConnectionException $e) {
            return ['error' => 'Connection error', 'message' => $e->getMessage()];
        }

        if ($response->successful()) {
            return $response->json();
        }

        if ($response->status() == 400 || $response->status() == 403) {
            if (self::testToken()) {
                return ['error' => 'Invalid search query', 'message' => 'Please provide a valid anime name to search for.'];
            } else {
                self::$client_id = null;
                return ['error' => 'Invalid MAL Client ID', 'message' => 'Please check your MAL Client ID in the application configuration.', 'need_token' => true];
            }
        }

        return [
            'error' => $response->status(),
            'message' => $response->json()['message'] ?? $response->json()['error'] ?? "An error occurred while searching for the anime.",
        ];
    }

    public static function getAnime($id): array
    {
        $token = self::getToken();

        if (empty($token)) {
            return ['error' => 'No MAL Client ID found', 'message' => 'Please set your MAL Client ID in the application configuration.', 'need_token' => true];
        }

        try {
            $response = Http::withHeaders([
                'X-MAL-CLIENT-ID' => $token,
            ])->get(sprintf(self::$get_anime_url, $id));
        } catch (ConnectionException $e) {
            return ['error' => 'Connection error', 'message' => $e->getMessage()];
        }

        if ($response->successful()) {
            return $response->json();
        }

        if ($response->status() == 400) {
            if (self::testToken()) {
                return ['error' => 'Invalid anime ID', 'message' => 'Please provide a valid anime ID to retrieve information.'];
            } else {
                self::$client_id = null;
                return ['error' => 'Invalid MAL Client ID', 'message' => 'Please check your MAL Client ID in the application configuration.', 'need_token' => true];
            }
        }

        return [
            'error' => $response->status(),
            'message' => $response->json()['message'] ?? $response->json()['error'] ?? "An error occurred while retrieving the anime information.",
        ];
    }

    public static function responseToAnime($response): Anime|null
    {
        if (isset($response['error'])) {
            return null;
        }

        $r = $response;

        try {
            $anime = DB::transaction(function () use ($r) {
                if (Anime::where('id', $r['id'])->exists()) {
                    Anime::where('id', $r['id'])->get()->first()->images()->delete();
                    Anime::where('id', $r['id'])->get()->first()->genres()->detach();
                    Anime::where('id', $r['id'])->get()->first()->studios()->detach();
                    Anime::where('id', $r['id'])->get()->first()->relatedAnimes()->detach();
                    RelatedAnime::where('id', $r['id'])->delete();
                    Image::where('anime_id', $r['id'])->delete();
                }

                try {
                    $anime = Anime::updateOrCreate(
                        ['id' => $r['id']], [
                        'id' => $r['id'],
                        'title' => $r['title'],
                        'title_jp' => $r['alternative_titles']['ja'] ?? null,
                        'title_en' => $r['alternative_titles']['en'] ?? null,
                        'start_date' => $r['start_date'] ?? null,
                        'end_date' => $r['end_date'] ?? null,
                        'synopsis' => $r['synopsis'] ?? null,
                        'score' => $r['mean'] ?? null,
                        'num_scoring_usr' => $r['num_scoring_users'] ?? null,
                        'nsfw' => in_array($r['nsfw'], ['black', 'gray', 'white']) ? $r['nsfw'] : null,
                            'media_type' => $r['media_type'] ?? null,
                        'status' => $r['status'] ?? null,
                        'num_episodes' => $r['num_episodes'] ?? null,
                        'broadcast_weekday' => $r['broadcast']['day_of_the_week'] ?? null,
                        'broadcast_time' => $r['broadcast']['start_time'] ?? null,
                        'average_ep_duration' => round(($r['average_episode_duration'] ?? 0) / 60),
                        'background' => $r['background'] ?? null,
                        'lastFetch' => now(),
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Error creating or updating anime: ' . $e->getMessage(), [
                        'anime_id' => $r['id'] ?? 'unknown',
                        'exception' => $e
                    ]);
                    return null;
                }

                collect($r['pictures'] ?? [])
                    ->each(function ($item) use ($r) {
                        if (isset($item['large'])) {
                            Image::create([
                                'anime_id' => $r['id'],
                                'url' => $item['large'],
                                'type' => "picture",
                            ]);
                        }
                    });

                collect($r['genres'] ?? [])
                    ->each(function ($item) use ($anime) {
                        if (isset($item['id']) && isset($item['name'])) {
                            $genre = Genre::firstOrCreate(['id' => $item['id']], [
                                'id' => $item['id'],
                                'name' => $item['name']
                            ]);
                            $anime->genres()->syncWithoutDetaching($genre->id);
                        }
                    });

                collect($r['studios'] ?? [])
                    ->each(function ($item) use ($anime) {
                        if (isset($item['id']) && isset($item['name'])) {
                            $studio = Studio::firstOrCreate(['id' => $item['id']], [
                                'id' => $item['id'],
                                'name' => $item['name']
                            ]);
                            $anime->studios()->syncWithoutDetaching($studio->id);
                        }
                    });

                collect($r['related_anime'] ?? [])
                    ->each(function ($item) use ($anime) {
                        if (isset($item['node']['id']) && isset($item['node']['title'])) {
                            $relatedAnimeId = $item['node']['id'];

                            RelatedAnime::updateOrInsert(
                                ['id' => $relatedAnimeId],
                                [
                                    'id' => $relatedAnimeId,
                                    'title' => $item['node']['title'],
                                    'image' => $item['node']['main_picture']['large'] ?? $item['node']['main_picture']['medium'] ?? null,
                                    'relation_type' => $item['relation_type_formatted'] ?? $item['relation_type'] ?? null,
                                ]
                            );

                            $exists = DB::table('anime_related_animes')
                                ->where('anime_id', $anime->id)
                                ->where('related_anime_id', $relatedAnimeId)
                                ->exists();

                            if (!$exists) {
                                DB::table('anime_related_animes')->insert([
                                    'anime_id' => $anime->id,
                                    'related_anime_id' => $relatedAnimeId,
                                ]);
                            }

                        }
                    });

                Image::create([
                    'anime_id' => $anime->id,
                    'url' => $r['main_picture']['large'] ?? $r['main_picture']['medium'] ?? null,
                    'type' => 'cover',
                ]);

                return $anime;
            });
        } catch (\Throwable $e) {
            Log::error('Error creating anime from response: ' . $e->getMessage(), [
                'anime_id' => $r['id'] ?? 'unknown',
                'exception' => $e
            ]);
            return null;
        }

        return $anime;
    }
}