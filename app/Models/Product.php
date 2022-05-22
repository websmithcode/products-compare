<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;

  public function categories()
  {
    return $this->belongsTo(Category::class);
  }

  public function merchant()
  {
    return $this->belongsTo(Merchant::class);
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('photo')->singleFile();
  }
}
