<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'token';
    public $timestamps = false;

    protected $fillable = [
        'client_id',
    ];

    public static function getClientId(){
        $token = self::first();
        if ($token) {
            return $token->client_id;
        }
        return null;
    }

    public static function saveClientId($clientId)
    {
        $token = self::first();
        if ($token) {
            $token->client_id = $clientId;
            $token->save();
        } else {
            self::create(['client_id' => $clientId]);
        }
    }
}
