<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HariLiburResource\Pages;
use App\Models\HariLibur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HariLiburResource extends Resource
{
    protected static ?string $model = HariLibur::class;

    protected static ?string $navigationIcon = 'heroicon-s-calendar-days';

    protected static ?string $navigationGroup = 'Aturan Booking';

    protected static ?string $navigationLabel = 'Hari Libur';

    protected static ?string $modelLabel = 'Hari Libur';

    protected static ?string $pluralModelLabel = 'Hari Libur';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Hari Libur')
                    ->description('Tanggal ini akan diblokir agar user tidak bisa memilih jadwal booking.')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Libur')
                            ->native(false)
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('nama_libur')
                            ->label('Nama Libur')
                            ->placeholder('Contoh: Hari Kemerdekaan RI')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Toggle::make('status')
                            ->label('Aktifkan Blokir Tanggal')
                            ->helperText('Jika aktif, user tidak bisa memilih tanggal ini saat booking.')
                            ->default(true)
                            ->required(),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('Contoh: Libur nasional / Kampus tutup / Tidak menerima order.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('nama_libur')
                    ->label('Nama Libur')
                    ->searchable()
                    ->sortable()
                    ->description(fn(HariLibur $record): ?string => $record->keterangan),

                Tables\Columns\IconColumn::make('status')
                    ->label('Blokir Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Blokir')
                    ->trueLabel('Tanggal Diblokir')
                    ->falseLabel('Tidak Diblokir')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\EditAction::make()
                    ->label('Edit'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
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
            'index' => Pages\ListHariLiburs::route('/'),
            'create' => Pages\CreateHariLibur::route('/create'),
            'view' => Pages\ViewHariLibur::route('/{record}'),
            'edit' => Pages\EditHariLibur::route('/{record}/edit'),
        ];
    }
}
