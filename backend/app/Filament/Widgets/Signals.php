<?php

namespace App\Filament\Widgets;

use App\Models\Signal;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Artisan;

class Signals extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn()=>Signal::query()->latest()
            )
            ->headerActions([
                Tables\Actions\Action::make('On')->color('success')->action(function (){
                    Artisan::call('mqtt:publish',['msg'=>'on']);
                }),
                Tables\Actions\Action::make('Off')->color('danger')->action(function (){
                    Artisan::call('mqtt:publish',['msg'=>'off']);
                })
            ])
            ->columns([
                Tables\Columns\TextColumn::make('topic'),
                Tables\Columns\TextColumn::make('message'),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])->paginated(false)
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->poll();
    }
}
