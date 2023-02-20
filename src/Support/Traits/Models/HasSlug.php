<?php

namespace Support\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {

        static::creating(function (Model $model) {
            $model->makeSlug();
        });
    }

    protected function makeSlug(): void
    {
        $slug = $this->slugUnique(Str::slug($this->{$this->slugFrom()}));

        $this->{$this->slugColumn()} = $this->{$this->slugFrom()} ?? $slug;
    }

    protected static function checkSlug(string $slug, int $i = 0): string
    {
        $endingSlug = '';
        if ($i > 0) {
            $endingSlug = '-' . $i;
        }
        $el = static::query()->where('slug', $slug . $endingSlug)->first();
        if ($el) {
            return self::checkSlug($slug, ++$i);
        }
        return $slug . $endingSlug;
    }

    private function slugUnique(string $slug): string
    {
        $originalSlug = $slug;
        $i = 0;
        while ($this->isSlugExist($slug)) {
            $i++;
            $slug = $originalSlug . '-' . $i;
        }
        return $slug;
    }

    private function isSlugExist(string $slug): bool
    {
        $query = $this->newQuery()
            ->where(self::slugColumn(), $slug)
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->withoutGlobalScopes();
        return $query->exists();
    }

    protected function slugFrom(): string
    {
        return 'title';
    }

    protected function slugColumn(): string
    {
        return 'slug';
    }
}
