<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KontakMasukResource\Pages;
use App\Models\KontakMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KontakMasukResource extends Resource
{
    protected static ?string $model = KontakMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-s-inbox-arrow-down';

    protected static ?string $navigationGroup = 'Interaksi Pengguna';

    protected static ?string $navigationLabel = 'Kontak Masuk';

    protected static ?string $modelLabel = 'Kontak Masuk';

    protected static ?string $pluralModelLabel = 'Kontak Masuk';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pengirim')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nomor_whatsapp')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('status_pesan')
                            ->label('Status Pesan')
                            ->options([
                                'Baru' => 'Baru',
                                'Dibaca' => 'Dibaca',
                                'Dibalas' => 'Dibalas',
                            ])
                            ->default('Baru')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Isi Pesan')
                    ->schema([
                        Forms\Components\TextInput::make('subjek')
                            ->label('Subjek')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('pesan')
                            ->label('Pesan')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(KontakMasuk $record): string => 'WA: ' . $record->nomor_whatsapp),

                Tables\Columns\TextColumn::make('subjek')
                    ->label('Subjek')
                    ->searchable()
                    ->placeholder('Tanpa subjek')
                    ->limit(30),

                Tables\Columns\TextColumn::make('pesan')
                    ->label('Pesan')
                    ->limit(45)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status_pesan')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Baru' => 'warning',
                        'Dibaca' => 'info',
                        'Dibalas' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pesan')
                    ->label('Filter Status')
                    ->options([
                        'Baru' => 'Baru',
                        'Dibaca' => 'Dibaca',
                        'Dibalas' => 'Dibalas',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label('Balas WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(function (KontakMasuk $record): string {
                        $nomor = preg_replace('/[^0-9]/', '', $record->nomor_whatsapp);

                        if (str_starts_with($nomor, '0')) {
                            $nomor = '62' . substr($nomor, 1);
                        }

                        $pesan = "Halo {$record->nama}, terima kasih sudah menghubungi Tukang Print Dadakan. Ada yang bisa kami bantu?";

                        return 'https://wa.me/' . $nomor . '?text=' . urlencode($pesan);
                    })
                    ->openUrlInNewTab(),

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
            'index' => Pages\ListKontakMasuks::route('/'),
            'create' => Pages\CreateKontakMasuk::route('/create'),
            'view' => Pages\ViewKontakMasuk::route('/{record}'),
            'edit' => Pages\EditKontakMasuk::route('/{record}/edit'),
        ];
    }
}
