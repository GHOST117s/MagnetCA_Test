<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('description')->required(),
                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->reactive(),
                    // ->afterStateUpdated(fn($state, callable $set, callable $get) => self::updateFinalPrice($set, $get)),

                TextInput::make('GST')
                    ->numeric()
                    ->suffix('%')  // Show as percentage
                    ->reactive(),
                    // ->afterStateUpdated(fn($state, callable $set, callable $get) => self::updateFinalPrice($set, $get)),

                // Select::make('state_id')
                //     ->relationship('state', 'name')
                //     ->required()
                //     ->preload()
                //     ->reactive()
                //     ->afterStateUpdated(fn($state, callable $set, callable $get) => self::updateStateCharges($set, $get)),

                // TextInput::make('delivery_charges')
                //     ->label('Delivery Charges')
                //     ->disabled()
                //     ->dehydrated(false), // Do not save in DB

                // Section::make('Final Price')
                //     ->schema([
                //         TextInput::make('final_price')
                //             ->label('Final Price')
                //             ->disabled()
                //             ->dehydrated(true) // Save final price in DB
                //     ])
            ]);
    }

    /**
     * Function to update Delivery Charges when State changes
     */
    // public static function updateStateCharges(callable $set, callable $get)
    // {
    //     $state = \App\Models\States::find($get('state_id'));
    //     $set('delivery_charges', $state?->delivery_charges ?? 0);
    //     self::updateFinalPrice($set, $get); // Recalculate final price
    // }

    // /**
    //  * Function to update Final Price dynamically
    //  */
    // public static function updateFinalPrice(callable $set, callable $get)
    // {
    //     $price = floatval($get('price') ?? 0);
    //     $gst = floatval($get('GST') ?? 0);
    //     $state = \App\Models\States::find($get('state_id'));

    //     $gstAmount = ($gst / 100) * $price; // GST as percentage
    //     $stateCharge = $state?->charges ?? 0;
    //     $deliveryCharge = $state?->delivery_charges ?? 0;

    //     $finalPrice = $price + $gstAmount + $stateCharge + $deliveryCharge;

    //     $set('final_price', $finalPrice);
    // }



    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('description'),
                TextColumn::make('price')->sortable(),
                TextColumn::make('GST')
                // TextColumn::make('state.name')->label('State'),
                // TextColumn::make('final_price')->label('Final Price')
                //     ->getStateUsing(fn($record) => $record->final_price)
                    ->sortable(),
            ])
            ->filters([]);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
