<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-s-banknotes';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Pembayaran';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pembayaran')
                    ->description('Kelola metode pembayaran dan validasi transaksi pelanggan.')
                    ->schema([
                        Forms\Components\Select::make('pesanan_id')
                            ->label('Kode Pesanan')
                            ->relationship('pesanan', 'kode_pesanan')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'Cash' => 'Cash',
                                'Online' => 'Online',
                            ])
                            ->default('Cash')
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('channel_pembayaran')
                            ->label('Channel Pembayaran')
                            ->options([
                                'Cash' => 'Cash',
                                'QRIS' => 'QRIS',
                                'DANA' => 'DANA',
                                'BRI' => 'BRI',
                            ])
                            ->default('Cash')
                            ->required(),

                        Forms\Components\TextInput::make('jumlah_bayar')
                            ->label('Jumlah Bayar')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\FileUpload::make('bukti_transfer')
                            ->label('Bukti Transfer')
                            ->image()
                            ->directory('bukti-transfer')
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->nullable(),

                        Forms\Components\Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->options([
                                'Belum Bayar' => 'Belum Bayar',
                                'Menunggu Validasi' => 'Menunggu Validasi',
                                'Lunas' => 'Lunas',
                                'Ditolak' => 'Ditolak',
                                'Cash Saat COD' => 'Cash Saat COD',
                            ])
                            ->default('Belum Bayar')
                            ->required(),

                        Forms\Components\DateTimePicker::make('tanggal_bayar')
                            ->label('Tanggal Bayar')
                            ->native(false)
                            ->seconds(false)
                            ->nullable(),

                        Forms\Components\Textarea::make('catatan_pembayaran')
                            ->label('Catatan Pembayaran')
                            ->placeholder('Contoh: Bukti transfer sudah sesuai, pembayaran cash saat COD, atau bukti kurang jelas.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
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
                    ->description(fn(Pembayaran $record): string => 'WA: ' . ($record->pesanan?->nomor_whatsapp ?? '-')),

                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('channel_pembayaran')
                    ->label('Channel')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('jumlah_bayar')
                    ->label('Jumlah Bayar')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('bukti_transfer')
                    ->label('Bukti')
                    ->square()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'Belum Bayar' => 'gray',
                        'Menunggu Validasi' => 'warning',
                        'Lunas' => 'success',
                        'Ditolak' => 'danger',
                        'Cash Saat COD' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('metode_pembayaran')
                    ->label('Filter Metode')
                    ->options([
                        'Cash' => 'Cash',
                        'Online' => 'Online',
                    ]),

                Tables\Filters\SelectFilter::make('channel_pembayaran')
                    ->label('Filter Channel')
                    ->options([
                        'Cash' => 'Cash',
                        'QRIS' => 'QRIS',
                        'DANA' => 'DANA',
                        'BRI' => 'BRI',
                    ]),

                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->label('Filter Status')
                    ->options([
                        'Belum Bayar' => 'Belum Bayar',
                        'Menunggu Validasi' => 'Menunggu Validasi',
                        'Lunas' => 'Lunas',
                        'Ditolak' => 'Ditolak',
                        'Cash Saat COD' => 'Cash Saat COD',
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'view' => Pages\ViewPembayaran::route('/{record}'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}
