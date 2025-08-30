<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'copies_total',
        'copies_available',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'date',
        'copies_total' => 'integer',
        'copies_available' => 'integer',
    ];

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
