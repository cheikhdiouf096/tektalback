<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255),
                TextInput::make('password')
                    ->required()
                    ->password() // Use password() instead of email() to mask the input
                    ->minLength(6)
                    ->maxLength(255),
                TextInput::make('confirmPassword')
                    ->required()
                    ->password() // Use password() for confirmation field as well
                    ->minLength(6)
                    ->maxLength(255)
                    ->label('Confirm Password'), // Optionally change the label
                // Add other form fields as needed
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                // Exclude password from being displayed
                TextColumn::make('created_at')->dateTime()->sortable(),
                // Add other table columns as needed
            ])
            ->filters([
                // Add table filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Add other table actions if needed
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Add other bulk actions if needed
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any relationships, e.g., posts, comments
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
