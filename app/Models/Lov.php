<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Lov extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'remarks',
        'lov_category_id',
        'parent_id',
        'created_by',
        'updated_by',
    ];

    public function categotyPermission()
    {
        return $this->hasMany(Permission::class, 'category_id', 'id');
    }

    public function socialMediaName($social_media_id)
    {
        return $this->where('id', $social_media_id)->first()?->name;
    }

    public function socialMediaImage($social_media_id)
    {
        return $this->where('id', $social_media_id)->first()?->remarks;
    }
}
