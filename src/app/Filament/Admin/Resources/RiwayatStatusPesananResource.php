<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RiwayatStatusPesananResource\Pages;
use App\Models\RiwayatStatusPesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RiwayatStatusPesananResource extends Resource
{
    protected static ?string $model = RiwayatStatusPesanan::class;

    protected static ?string $navigationIcon = 'heroicon-s-clock';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Riwayat Status';

    protected static ?string $modelLabel = 'Riwayat Status';

    protected static ?string $pluralModelLabel = 'Riwayat Status Pesanan';

    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pesanan')
                    ->description('Pilih pesanan yang ingin diberi riwayat status.')
                    ->schema([
                        Forms\Components\Select::make('pesanan_id')
                            ->label('Kode Pesanan')
                            ->relationship('pesanan', 'kode_pesanan')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'Menunggu Pembayaran' => 'Menunggu Pembayaran',
                                'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
                                'Diproses' => 'Diproses',
                                'File Bermasalah' => 'File Bermasalah',
                                'Sedang Dicetak' => 'Sedang Dicetak',
                                'Sudah Dicetak' => 'Sudah Dicetak',
                                'Siap Diambil' => 'Siap Diambil',
                                'Siap Diantar' => 'Siap Diantar',
                                'Dalam Pengiriman' => 'Dalam Pengiriman',
                                'Selesai' => 'Selesai',
                                'Dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('Menunggu Konfirmasi')
                            ->required(),

                        Forms\Components\DateTimePicker::make('waktu_status')
                            ->label('Waktu Status')
                            ->native(false)
                            ->seconds(false)
                            ->default(now()),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Status')
                            ->placeholder('Contoh: File sudah diterima, sedang dicek admin, hasil print siap diambil.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('waktu_status', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('pesanan.kode_pesanan')
                    ->label('Kode Pesanan')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pesanan.nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->description(fn(RiwayatStatusPesanan $record): string => 'WA: ' . ($record->pesanan?->nomor_whatsapp ?? '-')),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'Menunggu Pembayaran' => 'warning',
                        'Menunggu Konfirmasi' => 'gray',
                        'Diproses' => 'info',
                        'File Bermasalah' => 'danger',
                        'Sedang Dicetak' => 'primary',
                        'Sudah Dicetak' => 'primary',
                        'Siap Diambil' => 'success',
                        'Siap Diantar' => 'success',
                        'Dalam Pengiriman' => 'info',
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(40)
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('waktu_status')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'Menunggu Pembayaran' => 'Menunggu Pembayaran',
                        'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
                        'Diproses' => 'Diproses',
                        'File Bermasalah' => 'File Bermasalah',
                        'Sedang Dicetak' => 'Sedang Dicetak',
                        'Sudah Dicetak' => 'Sudah Dicetak',
                        'Siap Diambil' => 'Siap Diambil',
                        'Siap Diantar' => 'Siap Diantar',
                        'Dalam Pengiriman' => 'Dalam Pengiriman',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ]),
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
            'index' => Pages\ListRiwayatStatusPesanans::route('/'),
            'create' => Pages\CreateRiwayatStatusPesanan::route('/create'),
            'view' => Pages\ViewRiwayatStatusPesanan::route('/{record}'),
            'edit' => Pages\EditRiwayatStatusPesanan::route('/{record}/edit'),
        ];
    }
}
