<?php

namespace App\Orchid\Screens\Site;

use App\Orchid\Layouts\Site\SiteListLayout;
use App\Site\Domain\Models\Site;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class SiteListScreen extends Screen
{
    /**
     * @return array<string, mixed>
     */
    public function query(): iterable
    {
        return [
            'sites' => Site::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'SiteListScreen';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create new')
                ->icon('pencil')
                ->route('platform.site.edit'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            SiteListLayout::class,
        ];
    }
}
