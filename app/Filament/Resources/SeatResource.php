<?php

namespace App\Filament\Resources;

use UnitEnum;
use BackedEnum;
use App\Models\Seat;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\SeatResource\Pages;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Section;

class SeatResource extends Resource
{
    protected static ?string $model = Seat::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static string|UnitEnum|null $navigationGroup = 'Event Management';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Seat Details')->schema([
                Forms\Components\Select::make('event_section_id')
                    ->label('Section')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('seat_number')
                    ->required()
                    ->maxLength(20)
                    ->placeholder('e.g. A1, B5'),

                Forms\Components\TextInput::make('row_label')
                    ->maxLength(10)
                    ->placeholder('e.g. A, B, C'),

                Forms\Components\Select::make('status')
                    ->options([
                        'available' => 'Available',
                        'reserved'  => 'Reserved',
                        'booked'    => 'Booked',
                    ])
                    ->required()
                    ->default('available'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section.event.title')->label('Event')->searchable(),
                Tables\Columns\TextColumn::make('section.name')->label('Section')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('seat_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('row_label')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'available',
                        'warning' => 'reserved',
                        'danger'  => 'booked',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'reserved'  => 'Reserved',
                        'booked'    => 'Booked',
                    ]),
                Tables\Filters\SelectFilter::make('event_section_id')
                    ->label('Section')
                    ->relationship('section', 'name'),
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
            'index'  => Pages\ListSeats::route('/'),
            'create' => Pages\CreateSeat::route('/create'),
            'edit'   => Pages\EditSeat::route('/{record}/edit'),
        ];
    }
}
