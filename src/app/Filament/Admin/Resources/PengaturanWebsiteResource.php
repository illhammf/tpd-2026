<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengaturanWebsiteResource\Pages;
use App\Models\PengaturanWebsite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengaturanWebsiteResource extends Resource
{
    protected static ?string $model = PengaturanWebsite::class;

    protected static ?string $navigationIcon = 'heroicon-s-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $modelLabel = 'Pengaturan Website';

    protected static ?string $pluralModelLabel = 'Pengaturan Website';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Website')
                    ->description('Atur identitas utama website Tukang Print Dadakan.')
                    ->schema([
                        Forms\Components\TextInput::make('nama_website')
                            ->label('Nama Website')
                            ->required()
                            ->maxLength(255)
                            ->default('Tukang Print Dadakan'),

                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('pengaturan/logo')
                            ->imageEditor()
                            ->nullable(),

                        Forms\Components\FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->directory('pengaturan/favicon')
                            ->imageEditor()
                            ->nullable(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Hero Section')
                    ->description('Konten utama yang tampil di bagian paling atas website.')
                    ->schema([
                        Forms\Components\TextInput::make('judul_hero')
                            ->label('Judul Hero')
                            ->placeholder('Contoh: Print Tugas Gak Perlu Ribet')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('gambar_hero')
                            ->label('Gambar Hero')
                            ->image()
                            ->directory('pengaturan/hero')
                            ->imageEditor()
                            ->nullable(),

                        Forms\Components\Textarea::make('deskripsi_hero')
                            ->label('Deskripsi Hero')
                            ->placeholder('Tulis deskripsi singkat tentang layanan Tukang Print Dadakan.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Kontak & Lokasi')
                    ->description('Informasi kontak yang akan tampil di website dan digunakan untuk integrasi WhatsApp.')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_whatsapp')
                            ->label('Nomor WhatsApp')
                            ->placeholder('0895336900466')
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('jam_operasional')
                            ->label('Jam Operasional')
                            ->placeholder('Senin - Jumat'),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat / Lokasi COD')
                            ->placeholder('Universitas Esa Unggul Tangerang')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Pembayaran Online')
                    ->description('Data pembayaran yang ditampilkan ketika user memilih metode online.')
                    ->schema([
                        Forms\Components\FileUpload::make('qris')
                            ->label('Gambar QRIS')
                            ->image()
                            ->directory('pengaturan/pembayaran')
                            ->imageEditor()
                            ->nullable(),

                        Forms\Components\TextInput::make('nomor_dana')
                            ->label('Nomor DANA')
                            ->placeholder('0895336900466')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nomor_bri')
                            ->label('Nomor Rekening BRI')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('atas_nama_bri')
                            ->label('Atas Nama BRI')
                            ->placeholder('Ilham Firmansyah')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Footer')
                    ->schema([
                        Forms\Components\Textarea::make('teks_footer')
                            ->label('Teks Footer')
                            ->placeholder('Copyright © 2026 Tukang Print Dadakan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->square(),

                Tables\Columns\TextColumn::make('nama_website')
                    ->label('Nama Website')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(PengaturanWebsite $record): string => $record->judul_hero ?? 'Belum ada judul hero'),

                Tables\Columns\TextColumn::make('nomor_whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('jam_operasional')
                    ->label('Operasional')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nomor_dana')
                    ->label('DANA')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('nomor_bri')
                    ->label('BRI')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
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
            'index' => Pages\ListPengaturanWebsites::route('/'),
            'create' => Pages\CreatePengaturanWebsite::route('/create'),
            'view' => Pages\ViewPengaturanWebsite::route('/{record}'),
            'edit' => Pages\EditPengaturanWebsite::route('/{record}/edit'),
        ];
    }
}
