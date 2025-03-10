<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;


    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $product = new Product($data);
    //     $data['final_price'] = $product->calculateFinalPrice();  // Calculate before saving

    //     return $data;
    // }
}
