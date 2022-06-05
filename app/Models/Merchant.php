<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Merchant extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;
  const contactTypes = [
    ['value' => 'phone', 'verbose' => 'Phone'],
    ['value' => 'email', 'verbose' => 'Email'],
    ['value' => 'address', 'verbose' => 'Address']
  ];

  protected $fillable = ['name', 'slug', 'site', 'xml_url', 'published', 'contacts'];
  protected $casts = [
    'contacts' => 'array',
    'published' => 'boolean',
  ];

  public function products()
  {
    return $this->hasMany(Product::class);
  }

  public function reviews()
  {
    return $this->hasMany(MerchantReview::class);
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('logo')->singleFile();
  }

  public function getContacts()
  {
    return $this->contacts->map(fn (MerchantContact $contact) => [
      'id' => $contact->id,
      'value' => $contact->value,
      'type' => $contact->type->slug,
      'verbose_type' => $contact->type->name
    ]);
  }

  static function search($s)
  {
    return static::query()->where('name', 'LIKE', "%$s%");
  }


  public function recalculate_rate()
  {
    return $this->update(['rate' => $this->reviews->avg('stars')]);
  }

  public function incr_rate()
  {
    $this->increment('reviews_count');
    $this->recalculate_rate();
    return $this->reviews_count;
  }

  public function decr_rate()
  {
    $this->decrement('reviews_count');
    $this->recalculate_rate();
    return $this->reviews_count;
  }

  public function registerMediaConversions(Media $media = null): void
  {
    $this->addMediaConversion('small')->fit(Manipulations::FIT_FILL, 80, 50);
    $this->addMediaConversion('medium')->fit(Manipulations::FIT_FILL, 100, 70);
  }
}
