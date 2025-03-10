<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryResource\Pages;
use App\Filament\Resources\DeliveryResource\RelationManagers;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\States;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;


class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('product_id')
                ->label('Product')
                ->relationship('product', 'name')
                ->required()
                ->preload()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, callable $get) => self::updatePriceAndGST($set, $get)),

            TextInput::make('price')
                ->label('Base Price')

                ->formatStateUsing(fn ($record) => $record?->product?->price ?? null)
                ->disabled(),

            TextInput::make('GST')
                ->label('GST (%)')
                ->formatStateUsing(fn ($record) => $record?->product?->GST ?? null)

                ->disabled(),

            Select::make('state_id')
                ->label('State')
                ->relationship('state', 'name')
                ->required()
                ->preload()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, callable $get) => self::updateStateCharges($set, $get)),

            TextInput::make('state_charges')
                ->label('State Charges')
                //  ->formatStateUsing(function($record){
                //     dd($record->state->charges);

                // })
                ->formatStateUsing(fn ($record) => $record?->state->charges ?? null)

                ->disabled(),

            TextInput::make('delivery_charges')
                ->label('Delivery Charges')
                ->formatStateUsing(fn ($record) => $record?->state->delivery_charges ?? null)

                ->disabled(),

            TextInput::make('final_price')
                ->label('Final Price')
                ->disabled()
                ->dehydrated(true), // Store in DB
        ]);
    }

    public static function updatePriceAndGST(callable $set, callable $get)
    {
        $product = Product::find($get('product_id'));
        $set('price', $product?->price ?? 0);
        $set('GST', $product?->GST ?? 0);
        self::updateFinalPrice($set, $get);
    }

    public static function updateStateCharges(callable $set, callable $get)
    {
        $state = States::find($get('state_id'));
        $set('state_charges', $state?->charges ?? 0);
        $set('delivery_charges', $state?->delivery_charges ?? 0);
        self::updateFinalPrice($set, $get);
    }

    public static function updateFinalPrice(callable $set, callable $get)
    {
        $price = floatval($get('price') ?? 0);
        $gst = floatval($get('GST') ?? 0);
        $stateCharge = floatval($get('state_charges') ?? 0);
        $deliveryCharge = floatval($get('delivery_charges') ?? 0);

        $gstAmount = ($gst / 100) * $price;
        $finalPrice = $price + $gstAmount + $stateCharge + $deliveryCharge;

        $set('final_price', $finalPrice);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('product.name')->label('Product'),
            Tables\Columns\TextColumn::make('state.name')->label('State'),
            Tables\Columns\TextColumn::make('state.delivery_charges')->label('Delivery Charges'),
            Tables\Columns\TextColumn::make('final_price')->label('Final Price'),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
