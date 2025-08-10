<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author_id', 'category_id', 'publisher_id', 'published_year', 'cover_image',];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'borrowings')
                    ->withPivot('id','borrowed_at', 'returned_at')
                    ->withTimestamps();
    }

     // URL da imagem ou imagem padrão
    public function getCoverImageUrl()
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('/storage/default/default-cover.jpg');
    }

    public function hasOpenBorrowing()
    {
        return $this->users()->wherePivotNull('returned_at')->exists();
    }

}
