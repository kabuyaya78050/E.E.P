<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'name';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    private static ?array $cache = null;

    public static function get(string $name, mixed $default = null): mixed
    {
        if (self::$cache === null) {
            try {
                self::$cache = self::query()->pluck('value', 'name')->all();
            } catch (\Throwable) {
                self::$cache = []; // table absente (avant migration)
            }
        }

        $value = self::$cache[$name] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public static function put(array $values): void
    {
        foreach ($values as $name => $value) {
            self::updateOrCreate(['name' => $name], ['value' => $value]);
        }

        self::$cache = null;
    }
}
