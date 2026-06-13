<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LayananResource\Pages;
use App\Models\Layanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;

    protected static ?string $navigationIcon = 'heroicon-s-printer';

    protected static ?string $navigationGroup = 'Master Layanan';

    protected static ?string $navigationLabel = 'Daftar Layanan';

    protected static ?string $modelLabel = 'Layanan';

    protected static ?string $pluralModelLabel = 'Daftar Layanan';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Layanan')
                    ->description('Atur layanan yang akan tampil di website Tukang Print Dadakan.')
                    ->schema([
                        Forms\Components\Select::make('kategori_layanan_id')
                            ->label('Kategori Layanan')
                            ->relationship('kategoriLayanan', 'nama_kategori')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('nama_layanan')
                            ->label('Nama Layanan')
                            ->placeholder('Contoh: Print Dokumen')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $set('slug', Str::slug($state));
                            })
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->placeholder('print-dokumen')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('harga_dasar')
                            ->label('Harga Dasar')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('satuan')
                            ->label('Satuan')
                            ->placeholder('lembar / paket / sesi')
                            ->required()
                            ->maxLength(255)
                            ->default('layanan'),

                        Forms\Components\FileUpload::make('gambar')
                            ->label('Gambar Layanan')
                            ->image()
                            ->directory('layanan')
                            ->imageEditor()
                            ->nullable(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->placeholder('Jelaskan layanan ini secara singkat.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pengaturan Layanan')
                    ->description('Atur kebutuhan file, pembayaran online, dan status tampil layanan.')
                    ->schema([
                        Forms\Components\Toggle::make('butuh_upload_file')
                            ->label('Butuh Upload File')
                            ->helperText('Aktifkan untuk layanan seperti print dokumen atau rapihin tugas.')
                            ->default(false)
                            ->required(),

                        Forms\Components\Toggle::make('bisa_online')
                            ->label('Bisa Dipesan Online')
                            ->helperText('Jika aktif, layanan bisa dipilih oleh user di website.')
                            ->default(true)
                            ->required(),

                        Forms\Components\Toggle::make('status')
                            ->label('Status Aktif')
                            ->helperText('Jika nonaktif, layanan tidak tampil di website.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->square()
                    ->defaultImageUrl(url('/images/no-image.png')),

                Tables\Columns\TextColumn::make('nama_layanan')
                    ->label('Nama Layanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Layanan $record): ?string => $record->deskripsi),

                Tables\Columns\TextColumn::make('kategoriLayanan.nama_kategori')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_dasar')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->badge()
                    ->searchable(),

                Tables\Columns\IconColumn::make('butuh_upload_file')
                    ->label('Upload File')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-arrow-up')
                    ->falseIcon('heroicon-o-minus-circle'),

                Tables\Columns\IconColumn::make('bisa_online')
                    ->label('Online')
                    ->boolean()
                    ->trueIcon('heroicon-o-globe-alt')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\IconColumn::make('status')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

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
                Tables\Filters\SelectFilter::make('kategori_layanan_id')
                    ->label('Filter Kategori')
                    ->relationship('kategoriLayanan', 'nama_kategori')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('butuh_upload_file')
                    ->label('Butuh Upload File')
                    ->trueLabel('Butuh File')
                    ->falseLabel('Tidak Butuh File')
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
            'index' => Pages\ListLayanans::route('/'),
            'create' => Pages\CreateLayanan::route('/create'),
            'view' => Pages\ViewLayanan::route('/{record}'),
            'edit' => Pages\EditLayanan::route('/{record}/edit'),
        ];
    }
}
