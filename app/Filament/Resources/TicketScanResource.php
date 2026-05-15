<?php

namespace App\Filament\Resources;

use UnitEnum;
use BackedEnum;
use App\Models\TicketScan;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\TicketScanResource\Pages;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Section;

class TicketScanResource extends Resource
{
    protected static ?string $model = TicketScan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected static string|UnitEnum|null $navigationGroup = 'Ticket Management';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Scan Record')->schema([
                Forms\Components\Select::make('ticket_id')
                    ->label('Ticket')
                    ->relationship('ticket', 'ticket_code')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('scanned_by')
                    ->label('Scanned By (Staff/Admin)')
                    ->relationship('scanner', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\DateTimePicker::make('scanned_at')
                    ->required()
                    ->default(now()),

                Forms\Components\TextInput::make('device_info')
                    ->maxLength(255)
                    ->placeholder('e.g. iPhone 14, Android Scanner'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket.ticket_code')->label('Ticket Code')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('scanner.name')->label('Scanned By')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('scanned_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('device_info')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('scanned_by')
                    ->label('Scanned By')
                    ->relationship('scanner', 'name'),
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
            'index'  => Pages\ListTicketScans::route('/'),
            'create' => Pages\CreateTicketScan::route('/create'),
            'edit'   => Pages\EditTicketScan::route('/{record}/edit'),
        ];
    }
}
