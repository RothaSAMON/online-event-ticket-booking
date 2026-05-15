<?php

namespace App\Filament\Resources;

use UnitEnum;
use BackedEnum;
use App\Models\AnalyticsCache;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\AnalyticsCacheResource\Pages;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Section;

class AnalyticsCacheResource extends Resource
{
    protected static ?string $model = AnalyticsCache::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static string|UnitEnum|null $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema  // ✅ v4 signature
    {
        return $form->schema([
            Section::make('Analytics Cache')->schema([
                Forms\Components\Select::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('total_sales')
                    ->numeric()
                    ->required()
                    ->prefix('$')
                    ->minValue(0)
                    ->default(0),

                Forms\Components\TextInput::make('total_tickets_sold')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->default(0),

                Forms\Components\TextInput::make('total_attendees')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->default(0),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table  // ✅ Table stays the same in v4
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')->label('Event')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('total_sales')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('total_tickets_sold')->sortable(),
                Tables\Columns\TextColumn::make('total_attendees')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Last Refreshed')->dateTime()->sortable(),
            ])
            ->filters([])
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
            'index' => Pages\ListAnalyticsCaches::route('/'),
            'create' => Pages\CreateAnalyticsCache::route('/create'),
            'edit' => Pages\EditAnalyticsCache::route('/{record}/edit'),
        ];
    }
}
