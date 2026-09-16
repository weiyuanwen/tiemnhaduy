<?php

namespace App\Filament\Resources\ServiceOrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class BankTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'bankTransactions';

    protected static ?string $title = 'Lịch sử giao dịch';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bank_txn_id')
                    ->label('Mã CK ngân hàng')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Số tiền')
                    ->money('VND')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Nội dung CK')
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\TextColumn::make('matched_at')
                    ->label('Khớp lúc')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('matched_at', 'desc');
    }
}
