<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Merchant extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;

  public function contacts()
  {
    return $this->hasMany(MerchantContact::class);
  }

  public function products()
  {
    return $this->hasMany(Product::class);
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('logo')->singleFile();
  }
}
