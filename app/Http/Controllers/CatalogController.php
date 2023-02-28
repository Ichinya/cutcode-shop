<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): Factory|View|Application
    {
        $categories = Category::query()
            ->select(['id', 'title', 'slug'])
            ->homePage()
            ->has('products')
            ->get();;

        $products = Product::query()
            ->select(['id', 'title', 'slug', 'price', 'thumbnail'])
            ->when(request('s'), function (Builder $q) {
                $q->whereFullText(['title', 'text'], request('s'));
            })
            ->when($category->exists, function (Builder $q) use ($category) {
                $q->whereRelation('categories', 'categories.id', '=', $category->id);
            })
            ->filtered()
            ->sorted()
            ->paginate(6);


//        $products->setRelation('brands', $brands);

        return view('catalog.index', compact('categories', 'products', 'category'));
    }
}
