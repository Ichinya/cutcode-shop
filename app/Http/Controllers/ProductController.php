<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    public function __invoke(Product $product): Factory|View|Application
    {
        $product->load(['optionValues.option']);

        $also = Product::query()
            ->where(function (Builder $query) use ($product) {
                $query->whereIn('id', session('also'))
                    ->where('id', '!=', $product->id);
            })
            ->get();

        $options = $product->optionValues->mapToGrooups(function ($item) {
            return [$item->option->title => $item];
        });

        session()->put('also.' . $product->id, $product->id);

        return view('products.show', [
            'product' => $product,
            'options' => $options,
            'also' => $also,
        ]);
    }
}
