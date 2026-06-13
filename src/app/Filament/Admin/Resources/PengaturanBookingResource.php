<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengaturanBookingResource\Pages;
use App\Models\PengaturanBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengaturanBookingResource extends Resource
{
    protected static ?string $model = PengaturanBooking::class;

    protected static ?string $navigationIcon = 'heroicon-s-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Aturan Booking';

    protected static ?string $navigationLabel = 'Pengaturan Booking';

    protected static ?string $modelLabel = 'Pengaturan Booking';

    protected static ?string $pluralModelLabel = 'Pengaturan Booking';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pengaturan')
                    ->description('Pengaturan utama yang mengatur aturan operasional Tukang Print Dadakan.')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pengaturan')
                            ->label('Nama Pengaturan')
                            ->required()
                            ->maxLength(255)
                            ->default('Pengaturan Booking Utama'),

                        Forms\Components\Textarea::make('catatan_booking')
                            ->label('Catatan Booking')
                            ->placeholder('Contoh: Pesanan wajib H-1. Sabtu, Minggu, dan tanggal merah tidak melayani pesanan.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Aturan Jadwal')
                    ->description('Mengatur kapan user boleh melakukan booking.')
                    ->schema([
                        Forms\Components\Toggle::make('wajib_h_minus_satu')
                            ->label('Wajib H-1')
                            ->helperText('Jika aktif, user tidak bisa pesan untuk hari yang sama.')
                            ->default(true)
                            ->required(),

                        Forms\Components\TimePicker::make('batas_jam_booking')
                            ->label('Batas Jam Booking')
                            ->helperText('Jika lewat jam ini, pengambilan besok tidak bisa dipilih.')
                            ->seconds(false)
                            ->default('22:00')
                            ->required(),

                        Forms\Components\Toggle::make('tutup_sabtu')
                            ->label('Tutup Sabtu')
                            ->default(true)
                            ->required(),

                        Forms\Components\Toggle::make('tutup_minggu')
                            ->label('Tutup Minggu')
                            ->default(true)
                            ->required(),

                        Forms\Components\Toggle::make('tutup_tanggal_merah')
                            ->label('Tutup Tanggal Merah')
                            ->helperText('Mengikuti data dari menu Hari Libur.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Batas Kapasitas Print')
                    ->description('Membatasi jumlah halaman agar order tetap realistis.')
                    ->schema([
                        Forms\Components\TextInput::make('maksimal_lembar_per_hari')
                            ->label('Maksimal Lembar Per Hari')
                            ->suffix('lembar')
                            ->numeric()
                            ->default(500)
                            ->required(),

                        Forms\Components\TextInput::make('maksimal_lembar_per_order')
                            ->label('Maksimal Lembar Per Order')
                            ->suffix('lembar')
                            ->numeric()
                            ->default(150)
                            ->required(),

                        Forms\Components\TextInput::make('maksimal_jadwal_belajar_per_jam')
                            ->label('Maksimal Jadwal Belajar Per Jam')
                            ->suffix('booking')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        Forms\Components\TextInput::make('minimal_hari_rapihin_tugas')
                            ->label('Minimal Hari Rapihin Tugas')
                            ->suffix('hari sebelum deadline')
                            ->numeric()
                            ->default(2)
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Biaya Tambahan')
                    ->description('Biaya add-on yang akan dihitung otomatis di halaman booking.')
                    ->schema([
                        Forms\Components\TextInput::make('biaya_jilid')
                            ->label('Biaya Jilid')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(5000)
                            ->required(),

                        Forms\Components\TextInput::make('biaya_laminating')
                            ->label('Biaya Laminating')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(3000)
                            ->required(),

                        Forms\Components\TextInput::make('biaya_prioritas')
                            ->label('Biaya Prioritas')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(5000)
                            ->required(),

                        Forms\Components\Toggle::make('aktifkan_order_prioritas')
                            ->label('Aktifkan Order Prioritas')
                            ->helperText('Jika aktif, user bisa memilih layanan prioritas dengan biaya tambahan.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pembayaran & Pengiriman')
                    ->description('Mengatur aturan pembayaran online dan pengiriman.')
                    ->schema([
                        Forms\Components\Toggle::make('wajib_upload_bukti_online')
                            ->label('Wajib Upload Bukti Online')
                            ->helperText('Jika aktif, pembayaran online wajib menyertakan bukti transfer.')
                            ->default(true)
                            ->required(),

                        Forms\Components\TextInput::make('ongkir_kampus')
                            ->label('Ongkir Kampus')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\Toggle::make('lokasi_luar_kampus_perlu_konfirmasi')
                            ->label('Lokasi Luar Kampus Perlu Konfirmasi')
                            ->default(true)
                            ->required(),

                        Forms\Components\Toggle::make('ojek_online_perlu_konfirmasi')
                            ->label('Ojek Online Perlu Konfirmasi')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nama_pengaturan')
                    ->label('Nama Pengaturan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(PengaturanBooking $record): ?string => $record->catatan_booking),

                Tables\Columns\IconColumn::make('wajib_h_minus_satu')
                    ->label('H-1')
                    ->boolean(),

                Tables\Columns\TextColumn::make('batas_jam_booking')
                    ->label('Batas Jam')
                    ->badge(),

                Tables\Columns\TextColumn::make('maksimal_lembar_per_hari')
                    ->label('Kuota/Hari')
                    ->suffix(' lembar')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('maksimal_lembar_per_order')
                    ->label('Max/Order')
                    ->suffix(' lembar')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('maksimal_jadwal_belajar_per_jam')
                    ->label('Belajar/Jam')
                    ->suffix(' booking')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('minimal_hari_rapihin_tugas')
                    ->label('Rapihin Min')
                    ->suffix(' hari')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('biaya_jilid')
                    ->label('Jilid')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('biaya_laminating')
                    ->label('Laminating')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('biaya_prioritas')
                    ->label('Prioritas')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('tutup_sabtu')
                    ->label('Sabtu Tutup')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('tutup_minggu')
                    ->label('Minggu Tutup')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('tutup_tanggal_merah')
                    ->label('Tgl Merah Tutup')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('aktifkan_order_prioritas')
                    ->label('Prioritas Aktif')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('wajib_upload_bukti_online')
                    ->label('Bukti Online')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('lokasi_luar_kampus_perlu_konfirmasi')
                    ->label('Luar Kampus')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('ojek_online_perlu_konfirmasi')
                    ->label('Ojek Online')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
            'index' => Pages\ListPengaturanBookings::route('/'),
            'create' => Pages\CreatePengaturanBooking::route('/create'),
            'view' => Pages\ViewPengaturanBooking::route('/{record}'),
            'edit' => Pages\EditPengaturanBooking::route('/{record}/edit'),
        ];
    }
}
