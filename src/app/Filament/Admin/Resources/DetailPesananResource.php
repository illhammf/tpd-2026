<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DetailPesananResource\Pages;
use App\Models\DetailPesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DetailPesananResource extends Resource
{
    protected static ?string $model = DetailPesanan::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-text';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Detail Pesanan';

    protected static ?string $modelLabel = 'Detail Pesanan';

    protected static ?string $pluralModelLabel = 'Detail Pesanan';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pesanan & Layanan')
                    ->description('Pilih kode pesanan dan layanan yang dipesan pelanggan.')
                    ->schema([
                        Forms\Components\Select::make('pesanan_id')
                            ->label('Kode Pesanan')
                            ->relationship('pesanan', 'kode_pesanan')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('layanan_id')
                            ->label('Nama Layanan')
                            ->relationship('layanan', 'nama_layanan')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('File Dokumen')
                    ->description('File yang dikirim oleh pelanggan untuk diproses.')
                    ->schema([
                        Forms\Components\TextInput::make('nama_file')
                            ->label('Nama File')
                            ->placeholder('Contoh: tugas-pemrograman-web.pdf')
                            ->maxLength(255)
                            ->nullable(),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('Upload File')
                            ->directory('pesanan')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->downloadable()
                            ->openable()
                            ->nullable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Cetak')
                    ->description('Atur jenis print, ukuran kertas, jumlah halaman, dan copy.')
                    ->schema([
                        Forms\Components\Select::make('jenis_print')
                            ->label('Jenis Print')
                            ->options([
                                'Hitam Putih' => 'Hitam Putih',
                                'Warna' => 'Warna',
                            ])
                            ->nullable(),

                        Forms\Components\Select::make('ukuran_kertas')
                            ->label('Ukuran Kertas')
                            ->options([
                                'A4' => 'A4',
                                'F4' => 'F4',
                            ])
                            ->default('A4')
                            ->required(),

                        Forms\Components\TextInput::make('jumlah_halaman')
                            ->label('Jumlah Halaman')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('jumlah_copy')
                            ->label('Jumlah Copy')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        Forms\Components\TextInput::make('harga_satuan')
                            ->label('Harga Satuan')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Tambahan Layanan')
                    ->description('Opsi tambahan untuk kebutuhan print pelanggan.')
                    ->schema([
                        Forms\Components\Toggle::make('pakai_jilid')
                            ->label('Pakai Jilid Biasa')
                            ->helperText('Aktifkan jika pelanggan ingin hasil print dijilid.')
                            ->default(false)
                            ->required(),

                        Forms\Components\Toggle::make('pakai_laminating')
                            ->label('Pakai Laminating')
                            ->helperText('Aktifkan jika pelanggan ingin dokumen dilaminating.')
                            ->default(false)
                            ->required(),

                        Forms\Components\Textarea::make('catatan_detail')
                            ->label('Catatan Detail')
                            ->placeholder('Contoh: jangan bolak-balik, halaman cover warna, isi hitam putih.')
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
                    ->description(fn(DetailPesanan $record): string => 'WA: ' . ($record->pesanan?->nomor_whatsapp ?? '-')),

                Tables\Columns\TextColumn::make('layanan.nama_layanan')
                    ->label('Layanan')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_file')
                    ->label('Nama File')
                    ->placeholder('Tidak ada file')
                    ->searchable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('jenis_print')
                    ->label('Jenis Print')
                    ->placeholder('-')
                    ->badge(),

                Tables\Columns\TextColumn::make('ukuran_kertas')
                    ->label('Kertas')
                    ->badge(),

                Tables\Columns\TextColumn::make('jumlah_halaman')
                    ->label('Halaman')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_copy')
                    ->label('Copy')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\IconColumn::make('pakai_jilid')
                    ->label('Jilid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-minus-circle'),

                Tables\Columns\IconColumn::make('pakai_laminating')
                    ->label('Laminating')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-minus-circle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('layanan_id')
                    ->label('Filter Layanan')
                    ->relationship('layanan', 'nama_layanan')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('pakai_jilid')
                    ->label('Pakai Jilid')
                    ->trueLabel('Dengan Jilid')
                    ->falseLabel('Tanpa Jilid')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('pakai_laminating')
                    ->label('Pakai Laminating')
                    ->trueLabel('Dengan Laminating')
                    ->falseLabel('Tanpa Laminating')
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDetailPesanans::route('/'),
            'create' => Pages\CreateDetailPesanan::route('/create'),
            'view' => Pages\ViewDetailPesanan::route('/{record}'),
            'edit' => Pages\EditDetailPesanan::route('/{record}/edit'),
        ];
    }
}
