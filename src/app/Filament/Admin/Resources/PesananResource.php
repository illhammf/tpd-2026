<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PesananResource\Pages;
use App\Models\Pesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Pesanan Masuk';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'Pesanan Masuk';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pesanan')
                    ->description('Data utama pelanggan dan kode pesanan.')
                    ->schema([
                        Forms\Components\TextInput::make('kode_pesanan')
                            ->label('Kode Pesanan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('user_id')
                            ->label('Akun User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Forms\Components\TextInput::make('nama_pelanggan')
                            ->label('Nama Pelanggan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nomor_whatsapp')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255)
                            ->nullable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Jadwal & Lokasi')
                    ->description('Tanggal pengambilan atau jadwal konsultasi pelanggan.')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_pesan')
                            ->label('Tanggal Pesan')
                            ->native(false)
                            ->default(now()),

                        Forms\Components\DatePicker::make('tanggal_pengambilan')
                            ->label('Tanggal Ambil / Konsultasi')
                            ->native(false)
                            ->required(),

                        Forms\Components\TimePicker::make('jam_pengambilan')
                            ->label('Jam Ambil / Konsultasi')
                            ->seconds(false)
                            ->native(false),

                        Forms\Components\Select::make('lokasi_pengambilan')
                            ->label('Lokasi')
                            ->options([
                                'Kampus Esa Unggul Tangerang' => 'Kampus Esa Unggul Tangerang',
                                'Lokasi Lain' => 'Lokasi Lain',
                                'Online' => 'Online',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('detail_lokasi')
                            ->label('Detail Lokasi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Pesanan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ringkasan Harga')
                    ->description('Admin bisa menyesuaikan harga final jika ada perubahan.')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('biaya_tambahan')
                            ->label('Biaya Tambahan')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('biaya_pengiriman')
                            ->label('Biaya Pengiriman')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Status Operasional')
                    ->description('Status ini akan menjadi tracking utama pesanan.')
                    ->schema([
                        Forms\Components\Select::make('status_pesanan')
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
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('kode_pesanan')
                    ->label('Kode')
                    ->badge()
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Pesanan $record): string => 'WA: ' . $record->nomor_whatsapp),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Akun')
                    ->placeholder('Guest / Manual')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tanggal_pengambilan')
                    ->label('Jadwal')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn (Pesanan $record): string => $record->jam_pengambilan ? 'Jam: ' . $record->jam_pengambilan : 'Jam belum diisi'),

                Tables\Columns\TextColumn::make('lokasi_pengambilan')
                    ->label('Lokasi')
                    ->badge()
                    ->searchable()
                    ->limit(24),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status_pesanan')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('tanggal_pesan')
                    ->label('Tanggal Pesan')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('biaya_tambahan')
                    ->label('Tambahan')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('biaya_pengiriman')
                    ->label('Ongkir')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pesanan')
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

                Tables\Filters\SelectFilter::make('lokasi_pengambilan')
                    ->label('Filter Lokasi')
                    ->options([
                        'Kampus Esa Unggul Tangerang' => 'Kampus Esa Unggul Tangerang',
                        'Lokasi Lain' => 'Lokasi Lain',
                        'Online' => 'Online',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label('WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(function (Pesanan $record): string {
                        $nomor = preg_replace('/[^0-9]/', '', $record->nomor_whatsapp);

                        if (str_starts_with($nomor, '0')) {
                            $nomor = '62' . substr($nomor, 1);
                        }

                        $pesan = "Halo {$record->nama_pelanggan}, pesanan {$record->kode_pesanan} di Tukang Print Dadakan statusnya: {$record->status_pesanan}. Terima kasih ya.";

                        return 'https://wa.me/' . $nomor . '?text=' . urlencode($pesan);
                    })
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('proses')
                    ->label('Proses')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Pesanan $record): bool => !in_array($record->status_pesanan, ['Selesai', 'Dibatalkan']))
                    ->action(function (Pesanan $record) {
                        $record->update([
                            'status_pesanan' => 'Diproses',
                        ]);

                        $record->riwayatStatus()->create([
                            'status' => 'Diproses',
                            'catatan' => 'Pesanan mulai diproses admin.',
                            'waktu_status' => now(),
                        ]);
                    }),

                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Pesanan $record): bool => $record->status_pesanan !== 'Selesai')
                    ->action(function (Pesanan $record) {
                        $record->update([
                            'status_pesanan' => 'Selesai',
                        ]);

                        $record->riwayatStatus()->create([
                            'status' => 'Selesai',
                            'catatan' => 'Pesanan sudah selesai.',
                            'waktu_status' => now(),
                        ]);
                    }),

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
            'index' => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'view' => Pages\ViewPesanan::route('/{record}'),
            'edit' => Pages\EditPesanan::route('/{record}/edit'),
        ];
    }
}