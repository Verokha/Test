<?php

namespace App\Orchid\Screens\Site;

use App\Orchid\Layouts\Site\SiteListLayout;
use App\Site\Domain\Repositories\SiteRepositoryInterface;
use App\User\Domain\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class SiteListScreen extends Screen
{
    /**
     * @return array<string, mixed>
     */
    public function query(): iterable
    {
        /** @var User $curretUser */
        $curretUser = Auth::user();

        return [
            'sites' => app(SiteRepositoryInterface::class)
                ->orchidListForUser($curretUser),
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
