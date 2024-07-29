<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;


use Faker\Core\Color;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\MoonShine\Pages\Post\PostIndexPage;
use App\MoonShine\Pages\Post\PostFormPage;
use App\MoonShine\Pages\Post\PostDetailPage;

use Monolog\Handler\FleepHookHandler;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Collapse;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\ID;
use MoonShine\Fields\Slug;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Resources\ModelResource;

class PostResource extends ModelResource
{
    protected string $model = Post::class;

    protected string $title = 'Posts';


    public function fields(): array
    {
        return [
            ID::make()->sortable(),

            Grid::make([

                        Column::make([
                            Collapse::make([

                                    Text::make('Title', 'title'),
                            ]),
                        ])->columnSpan(6),
                        Column::make([
                            Collapse::make([
                                Text::make('Title', 'title'),
                            ]),
                        ])->columnSpan(6),
            ]),


            Textarea::make('description','description')->hint('bu description qismi')
        ];
    }

    public function pages(): array
    {
        return [
            PostIndexPage::make($this->title()),
            PostFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            PostDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
