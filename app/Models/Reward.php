<?php

namespace App\Models;

use App\Contracts\CacheServiceContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = ['details', 'uid'];
    protected ?string $details;
    protected ?string $uid;
    protected int $id;

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public static function cacheKey(int $id): string
    {
        return 'rewards:'.$id;
    }
}
