<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengirimanResource\Pages;
use App\Models\Pengiriman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengirimanResource extends Resource
{
    protected static ?string $model = Pengiriman::class;

    protected static ?string $navigationIcon = 'heroicon-s-truck';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Pengiriman';

    protected static ?string $modelLabel = 'Pengiriman';

    protected static ?string $pluralModelLabel = 'Pengiriman';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pengiriman')
                    ->schema([

                        Forms\Components\Select::make('pesanan_id')
                            ->label('Kode Pesanan')
                            ->relationship('pesanan', 'kode_pesanan')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('metode_pengiriman')
                            ->label('Metode Pengiriman')
                            ->options([
                                'COD Kampus' => 'COD Kampus',
                                'Diantar Ilham' => 'Diantar Ilham',
                                'Ojek Online' => 'Ojek Online',
                                'Ambil Sendiri' => 'Ambil Sendiri',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('biaya_pengiriman')
                            ->label('Biaya Pengiriman')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\Select::make('status_pengiriman')
                            ->label('Status Pengiriman')
                            ->options([
                                'Belum Siap' => 'Belum Siap',
                                'Siap Diantar' => 'Siap Diantar',
                                'Dalam Perjalanan' => 'Dalam Perjalanan',
                                'Terkirim' => 'Terkirim',
                                'Dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('Belum Siap')
                            ->required(),

                        Forms\Components\Textarea::make('alamat_pengiriman')
                            ->label('Alamat Pengiriman')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('catatan_pengiriman')
                            ->label('Catatan Pengiriman')
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
                    ->sortable(),

                Tables\Columns\TextColumn::make('metode_pengiriman')
                    ->label('Metode')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('biaya_pengiriman')
                    ->label('Biaya')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_pengiriman')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Belum Siap' => 'gray',
                        'Siap Diantar' => 'warning',
                        'Dalam Perjalanan' => 'info',
                        'Terkirim' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('metode_pengiriman')
                    ->options([
                        'COD Kampus' => 'COD Kampus',
                        'Diantar Ilham' => 'Diantar Ilham',
                        'Ojek Online' => 'Ojek Online',
                        'Ambil Sendiri' => 'Ambil Sendiri',
                    ]),

                Tables\Filters\SelectFilter::make('status_pengiriman')
                    ->options([
                        'Belum Siap' => 'Belum Siap',
                        'Siap Diantar' => 'Siap Diantar',
                        'Dalam Perjalanan' => 'Dalam Perjalanan',
                        'Terkirim' => 'Terkirim',
                        'Dibatalkan' => 'Dibatalkan',
                    ]),

            ])
            ->actions([

                Tables\Actions\Action::make('whatsapp')
                    ->label('WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(function (Pengiriman $record): string {

                        $nomor = preg_replace(
                            '/[^0-9]/',
                            '',
                            $record->pesanan?->nomor_whatsapp ?? ''
                        );

                        if (str_starts_with($nomor, '0')) {
                            $nomor = '62' . substr($nomor, 1);
                        }

                        $pesan = "Halo {$record->pesanan?->nama_pelanggan}, pesanan {$record->pesanan?->kode_pesanan} sedang dalam proses pengiriman.";

                        return 'https://wa.me/' . $nomor . '?text=' . urlencode($pesan);
                    })
                    ->openUrlInNewTab(),

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
            'index' => Pages\ListPengirimen::route('/'),
            'create' => Pages\CreatePengiriman::route('/create'),
            'view' => Pages\ViewPengiriman::route('/{record}'),
            'edit' => Pages\EditPengiriman::route('/{record}/edit'),
        ];
    }
}
