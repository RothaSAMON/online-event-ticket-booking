<?php

namespace App\Filament\Resources;

use UnitEnum;
use BackedEnum;
use App\Models\BookingItem;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\BookingItemResource\Pages;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Section;

class BookingItemResource extends Resource
{
    protected static ?string $model = BookingItem::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static string|UnitEnum|null $navigationGroup = 'Booking Management';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Booking Item')->schema([
                Forms\Components\Select::make('booking_id')
                    ->label('Booking')
                    ->relationship('booking', 'booking_code')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('seat_id')
                    ->label('Seat')
                    ->relationship('seat', 'seat_number')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->prefix('$')
                    ->minValue(0),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking.booking_code')->label('Booking Code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('seat.seat_number')->label('Seat')->searchable(),
                Tables\Columns\TextColumn::make('seat.section.name')->label('Section'),
                Tables\Columns\TextColumn::make('seat.section.event.title')->label('Event'),
                Tables\Columns\TextColumn::make('price')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('booking_id')
                    ->label('Booking')
                    ->relationship('booking', 'booking_code'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBookingItems::route('/'),
            'create' => Pages\CreateBookingItem::route('/create'),
            'edit'   => Pages\EditBookingItem::route('/{record}/edit'),
        ];
    }
}
