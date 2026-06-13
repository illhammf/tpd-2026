<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StokBarangResource\Pages;
use App\Models\StokBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StokBarangResource extends Resource
{
    protected static ?string $model = StokBarang::class;

    protected static ?string $navigationIcon = 'heroicon-s-archive-box';

    protected static ?string $navigationGroup = 'Manajemen Inventaris';

    protected static ?string $navigationLabel = 'Stok Barang';

    protected static ?string $modelLabel = 'Stok Barang';

    protected static ?string $pluralModelLabel = 'Stok Barang';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Barang')
                    ->schema([

                        Forms\Components\TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('kategori')
                            ->label('Kategori')
                            ->options([
                                'Kertas' => 'Kertas',
                                'Tinta' => 'Tinta',
                                'Jilid' => 'Jilid',
                                'Laminating' => 'Laminating',
                                'Peralatan' => 'Peralatan',
                            ])
                            ->searchable(),

                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('satuan')
                            ->label('Satuan')
                            ->default('pcs')
                            ->required(),

                        Forms\Components\TextInput::make('batas_menipis')
                            ->label('Batas Menipis')
                            ->numeric()
                            ->default(5)
                            ->required(),

                        Forms\Components\Select::make('status_stok')
                            ->label('Status Stok')
                            ->options([
                                'Ready' => 'Ready',
                                'Menipis' => 'Menipis',
                                'Kosong' => 'Kosong',
                            ])
                            ->default('Ready')
                            ->required(),

                    ])
                    ->columns(3),

                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([

                Tables\Columns\TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->badge(),

                Tables\Columns\TextColumn::make('batas_menipis')
                    ->label('Batas Menipis')
                    ->numeric(),

                Tables\Columns\TextColumn::make('status_stok')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Ready' => 'success',
                        'Menipis' => 'warning',
                        'Kosong' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('kategori')
                    ->options([
                        'Kertas' => 'Kertas',
                        'Tinta' => 'Tinta',
                        'Jilid' => 'Jilid',
                        'Laminating' => 'Laminating',
                        'Peralatan' => 'Peralatan',
                    ]),

                Tables\Filters\SelectFilter::make('status_stok')
                    ->options([
                        'Ready' => 'Ready',
                        'Menipis' => 'Menipis',
                        'Kosong' => 'Kosong',
                    ]),

            ])
            ->actions([

                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStokBarangs::route('/'),
            'create' => Pages\CreateStokBarang::route('/create'),
            'view' => Pages\ViewStokBarang::route('/{record}'),
            'edit' => Pages\EditStokBarang::route('/{record}/edit'),
        ];
    }
}
