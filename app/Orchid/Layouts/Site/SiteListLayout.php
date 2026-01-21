<?php

namespace App\Orchid\Layouts\Site;

use App\Site\Domain\Models\Site;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SiteListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'sites';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Title')
                ->render(fn(Site $site) => Link::make($site->domain)
                    ->route('platform.site.edit', [$site->id])),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
