<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug()
    {

        static::creating(function (Model $model) {
            $model->slug = $model->slug ?? self::checkSlug(str($model->{self::slugFrom()})->slug());
        });
    }

    protected static function checkSlug(string $slug, int $i = 0): string
    {
        $endingSlug = '';
        if ($i > 0) {
            $endingSlug = '-' . $i;
        }
        $el = static::where('slug', $slug . $endingSlug)->first();
        if ($el) {
            return self::checkSlug($slug, ++$i);
        }
        return $slug . $endingSlug;
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}
