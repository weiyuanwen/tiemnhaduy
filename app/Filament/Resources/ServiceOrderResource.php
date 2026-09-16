<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceOrderResource\Pages;
use App\Filament\Resources\ServiceOrderResource\RelationManagers;
use App\Models\ServiceOrder;
use App\Services\OrderService;
use Filament\Notifications\Notification;
use Filament\Forms\Components as Forms;
use Filament\Schemas\Components as Layout;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Actions\BulkAction;

/**
 * Service Order Resource
 *
 * Manages service orders in the admin panel
 */
class ServiceOrderResource extends Resource
{
    protected static ?string $model = ServiceOrder::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static int|null $navigationSort = 11;

    protected static ?string $recordTitleAttribute = 'order_code';

    public static function getNavigationGroup(): ?string
    {
        return 'Phí nhóm Facebook';
    }

    public static function getNavigationLabel(): string
    {
        return 'Người đã thanh toán';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Layout\Section::make('Order Information')
                    ->schema([
                        Forms\TextInput::make('order_code')
                            ->default(fn () => ServiceOrder::generateOrderCode())
                            ->disabled()
                            ->required()
                            ->maxLength(255),

                        Forms\Select::make('service_id')
                            ->relationship('service', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\TextInput::make('facebook_profile_link')
                            ->label('Facebook Profile Link')
                            ->required()
                            ->url()
                            ->maxLength(500)
                            ->helperText('The Facebook profile URL for this order'),

                        Forms\TextInput::make('facebook_name')
                            ->label('Tên Facebook')
                            ->maxLength(191),

                        Forms\TextInput::make('facebook_id')
                            ->label('ID Facebook')
                            ->maxLength(64),

                        Forms\TextInput::make('customer_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\TextInput::make('ip_address')
                            ->label('IP')
                            ->disabled(),
                    ])->columns(3),

                Layout\Section::make('Payment Information')
                    ->schema([
                        Forms\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('VND')
                            ->helperText('Số tiền đầy đủ, ví dụ 150000.'),

                        Forms\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'expired' => 'Expired',
                            ])
                            ->required()
                            ->default('pending')
                            ->helperText('Order status'),

                        Forms\DateTimePicker::make('expires_at')
                            ->label('Expires At')
                            ->helperText('When this order expires (default: 5 minutes from creation)'),

                        Forms\DateTimePicker::make('paid_at')
                            ->label('Paid At')
                            ->helperText('When the payment was confirmed'),

                        Forms\TextInput::make('bank_txn_id')
                            ->label('Bank Transaction ID')
                            ->maxLength(255)
                            ->helperText('Unique bank transaction identifier'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Order Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->width('180px'),

                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('facebook_name')
                    ->label('Facebook')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('VND')
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('facebook_approval_disabled_at')
                    ->label('Tắt duyệt bài')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Chưa')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('bank_txn_id')
                    ->label('Bank TXN')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'expired' => 'Expired',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('expires_soon')
                    ->label('Expires Soon')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'pending')
                        ->where('expires_at', '>', now())
                        ->where('expires_at', '<', now()->addMinutes(5))),
            ])
            ->actions([
                Action::make('mark_paid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Forms\TextInput::make('bank_txn_id')
                            ->label('Bank Transaction ID')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->visible(fn (ServiceOrder $record): bool => $record->status === 'pending')
                    ->action(function (ServiceOrder $record, array $data) {
                        $result = app(OrderService::class)->confirmBankMatch(
                            $record->order_code,
                            $data['bank_txn_id'],
                            true,
                            [
                                'amount' => (int) $record->amount,
                                'description' => 'Admin xác nhận thanh toán',
                            ]
                        );

                        if (! ($result['success'] ?? false)) {
                            Notification::make()
                                ->danger()
                                ->title($result['message'] ?? 'Không xác nhận được thanh toán')
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->success()
                            ->title('Đã lưu người thanh toán và giao dịch')
                            ->send();
                    }),

                Action::make('mark_expired')
                    ->label('Mark as Expired')
                    ->icon('heroicon-o-clock')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (ServiceOrder $record): bool => $record->status === 'pending')
                    ->action(fn (ServiceOrder $record) => $record->update(['status' => 'expired'])),

                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([

                    BulkAction::make('mark_all_paid')
                        ->label('Mark all as Paid')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $orders = app(OrderService::class);

                            foreach ($records as $record) {
                                if ($record->status !== ServiceOrder::STATUS_PENDING) {
                                    continue;
                                }

                                $orders->confirmBankMatch(
                                    $record->order_code,
                                    'ADMIN_'.strtoupper(\Illuminate\Support\Str::random(12)),
                                    true,
                                    [
                                        'amount' => (int) $record->amount,
                                        'description' => 'Admin xác nhận hàng loạt',
                                    ]
                                );
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BankTransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceOrders::route('/'),
            'create' => Pages\CreateServiceOrder::route('/create'),
            'edit' => Pages\EditServiceOrder::route('/{record}/edit'),
            'view' => Pages\ViewServiceOrder::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
