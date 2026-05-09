<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Section 1 - post details
                Section::make('Post Details')
                    ->description('Fill in the details of the post')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        // grouping fields into 2 columns
                        Group::make([
                            TextInput::make('title')
                                ->rules(['required', 'min:5'])
                                ->validationMessages([
                                    'required' => 'Title wajib diisi.',
                                    'min' => 'Title minimal 5 karakter.',
                                ]),
                            TextInput::make('slug')
                                ->rules(['required', 'min:3'])
                                ->unique()
                                ->validationMessages([
                                    'required' => 'Slug wajib diisi.',
                                    'min' => 'Slug minimal 3 karakter.',
                                    'unique' => 'Slug harus unik.',
                                ]),
                            Select::make('category_id')
                                ->relationship('category', 'name')
                                ->required()
                                ->validationMessages([
                                    'required' => 'Category wajib dipilih.',
                                ])
                                ->preload()
                                ->searchable(),
                            ColorPicker::make('color'),
                        ])->columns(2),
                        MarkdownEditor::make('content'),
                    ])->columnSpan(2),

                // Grouping fields into 2 columns
                Group::make([
                    // section 2 - image
                    Section::make('Image Upload')
                        ->schema([
                            FileUpload::make('image')
                                ->required()
                                ->validationMessages([
                                    'required' => 'Image wajib diupload.',
                                ])
                                ->disk('public')
                                ->directory('posts'),
                        ]),

                    // section 3 - meta
                    Section::make('Meta Information')
                        ->schema([
                            TagsInput::make('tags'),
                            Checkbox::make('published'),
                            DateTimePicker::make('published_at'),
                        ]),
                ])->columnSpan(1),
            ])
            ->columns(3);
    }
}
