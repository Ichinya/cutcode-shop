<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug()
    {

        static::creating(function (Model $model) {
            $model->slug = $model->slug ?? self::checkSlug(str($model->{self::slugFrom()})->append(time())->slug());
        });
    }

    protected static function checkSlug(string $slug, int $i = 0): string
    {
        if ($i > 0) {
            $slug .= '-' . $i;
        }
        $el = static::where('slug', $slug)->first();
        if ($el) {
            return self::checkSlug($slug, ++$i);
        }
        return $slug;
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}
