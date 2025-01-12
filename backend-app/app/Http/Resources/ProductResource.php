<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'categories' => $this->categories->map(function (Category $category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'bigCommeceId' => $category->bigcommerce_id,
                ];
            }),
            'variants' => $this->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'price' => $variant->price,
                    'sku' => $variant->sku,
                    'option_values' => $variant->option_values,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
