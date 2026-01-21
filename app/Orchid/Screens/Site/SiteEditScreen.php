<?php

namespace App\Orchid\Screens\Site;

use App\User\Domain\Models\User;
use App\Site\Application\Commands\CreateSiteCommand;
use App\Site\Application\Commands\CreateSiteHandler;
use App\Site\Application\Commands\UpdateSiteCommand;
use App\Site\Application\Commands\UpdateSiteHandler;
use App\Site\Domain\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

/**
 * @phpstan-type SiteScreenRequest array{
 *      user_id: numeric-string,
 *      domain: string,
 *      is_active: numeric-string
 * }
 */
class SiteEditScreen extends Screen
{
    public ?Site $site = null;

    /**
     * @return array<string, mixed>
     */
    public function query(Site $site): iterable
    {
        return [
            'site' => $site,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->site?->exists ? 'Редактировать сайт' : 'Создать новый сайт';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Создать')
                ->method('create')
                ->canSee(! (bool) $this->site?->exists),

            Button::make('Обновить')
                ->method('update')
                ->canSee((bool) $this->site?->exists),

            Button::make('Удалить')
                ->method('remove')
                ->canSee((bool) $this->site?->exists),
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
            Layout::rows([
                Input::make('site.domain')
                    ->required()
                    ->title('Домен')
                    ->help('Домен по которому будет доступен ваш сайт'),

                Relation::make('site.user_id')
                    ->required()
                    ->fromModel(User::class, 'name')
                    ->title('Пользователь'),

                CheckBox::make('site.is_active')
                    ->title('Вкл/Выкл')
                    ->sendTrueOrFalse(),
            ]),
        ];
    }

    public function create(Request $request): RedirectResponse
    {
        /** @var SiteScreenRequest $requestData */
        $requestData = $request->get('site');
        $command = new CreateSiteCommand(
            userId: (int) $requestData['user_id'],
            domain: $requestData['domain'],
            isActive: (bool) $requestData['is_active']

        );
        app(CreateSiteHandler::class)($command);

        Alert::info('Сохранено');

        return redirect()->route('platform.site.list');
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var Site $curretSite */
        $curretSite = $this->site;
        /** @var SiteScreenRequest $requestData */
        $requestData = $request->get('site');
        $command = new UpdateSiteCommand(
            siteId: $curretSite->id,
            userId: (int) $requestData['user_id'],
            domain: $requestData['domain'],
            isActive: (bool) $requestData['is_active']

        );
        app(UpdateSiteHandler::class)($command);

        Alert::info('Сохранено');

        return redirect()->route('platform.site.list');
    }

    public function remove(): RedirectResponse
    {
        /** @var Site $currentSite */
        $currentSite = $this->site;
        $currentSite->delete();

        Alert::info('Запись удалена.');

        return redirect()->route('platform.site.list');
    }
}
