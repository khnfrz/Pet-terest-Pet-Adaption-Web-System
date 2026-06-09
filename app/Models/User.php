<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'age',
        'status',
        'image'
    ];

    protected $casts = [
        'age' => 'integer',
    ];

    // Scopes for filtering
    public function scopeDogs($query)
    {
        return $query->where('species', 'Dog');
    }

    public function scopeCats($query)
    {
        return $query->where('species', 'Cat');
    }

    public function scopePokemon($query)
    {
        return $query->where('species', 'Pokemon');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('species', 'LIKE', "%{$term}%");
    }
}