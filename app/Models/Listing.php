<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;
    // protected $fillable = ['title','company','location','tags','description','website','email','tags'];

        public function scopeFilter($query, array $filters) {
            //dd($filters['tag']); //OUTPUT 'laravel' from http://localhost/?tag=laravel

            // check if tag is FOUND
            if($filters['tag'] ?? false){
                // database query
                $query->where('tags', 'like', '%' . request('tag') . '%');

            }
            if($filters['search'] ?? false){
                // database query
                $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%')
                ->orWhere('tags', 'like', '%' . request('search') . '%');

            }

        }

        // RELATIONSHIP TO USER
            public function user() {
                // define the listing belongs to the user id
                return $this->belongsTo(User::class, 'user_id');
            }

}
