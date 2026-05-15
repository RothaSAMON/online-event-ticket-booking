<?php

namespace App\Filament\Resources;

use UnitEnum;
use BackedEnum;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\TicketResource\Pages;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static string|UnitEnum|null $navigationGroup = 'Ticket Management';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Ticket Details')->schema([
                Forms\Components\Select::make('booking_item_id')
                    ->label('Booking Item')
                    ->relationship('bookingItem', 'id')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('ticket_code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->default(fn () => strtoupper('TKT-' . Str::random(8)))
                    ->maxLength(100),

                Forms\Components\TextInput::make('qr_code')
                    ->label('QR Code Path / Value')
                    ->maxLength(500),

                Forms\Components\TextInput::make('pdf_path')
                    ->label('PDF Path')
                    ->maxLength(500),
            ])->columns(2),

            Section::make('Scan Status')->schema([
                Forms\Components\Toggle::make('is_scanned')
                    ->label('Scanned?')
                    ->reactive(),

                Forms\Components\DateTimePicker::make('scanned_at')
                    ->label('Scanned At')
                    ->nullable()
                    ->visible(fn ($get) => $get('is_scanned')),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_code')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('bookingItem.booking.booking_code')
                    ->label('Booking Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bookingItem.seat.seat_number')
                    ->label('Seat'),
                Tables\Columns\IconColumn::make('is_scanned')
                    ->label('Scanned')
                    ->boolean(),
                Tables\Columns\TextColumn::make('scanned_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_scanned')
                    ->label('Scanned'),
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
            'index'  => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit'   => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
