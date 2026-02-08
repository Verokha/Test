<?php

namespace App\Orchid\Screens\Site;

use App\Shared\Domain\Types\Enum\Role;
use App\Shared\Domain\Types\String\CustomString;
use App\Shared\Domain\Types\String\Domain;
use App\Shared\Domain\Types\String\TelegramChannel;
use App\Site\Application\Commands\CreateSiteCommand;
use App\Site\Application\Commands\CreateSiteHandler;
use App\Site\Application\Commands\UpdateSiteCommand;
use App\Site\Application\Commands\UpdateSiteHandler;
use App\Site\Application\Requests\SiteScreenRequest;
use App\Site\Domain\Models\Site;
use App\User\Domain\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class SiteEditScreen extends Screen
{
    public function __invoke(Request $request, ...$arguments)
    {
        /** @var User $curretUser */
        $curretUser = Auth::user();
        $this->isSuperAdmin = $curretUser->inRole(Role::SaAdmin->value);

        return parent::__invoke($request, ...$arguments);
    }

    public ?Site $site = null;

    private bool $isSuperAdmin = false;

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
        $rows = [
            Input::make('site.domain')
                ->maxlength(Domain::MAX_LENGTH)
                ->required()
                ->title('Поддомен')
                ->help('Домен по которому будет доступен ваш сайт. Пример: durov-channel'),
            Input::make('site.telegram_channel')
                ->maxlength(TelegramChannel::MAX_LENGTH)
                ->required()
                ->title('Ссылка на телеграм канал')
                ->help('Пример корректной ссылки: https://t.me/durov'),
            Input::make('site.name')
                ->maxlength(CustomString::MAX_LENGTH)
                ->required()
                ->title('Название сайта')
                ->help('Название сайта лучше давать по названию телеграм канала'),

            CheckBox::make('site.enabled')
                ->title('Вкл/Выкл')
                ->sendTrueOrFalse(),
        ];
        if ($this->isSuperAdmin) {
            $rows[] = Relation::make('site.user_id')
                ->required()
                ->fromModel(User::class, 'name')
                ->title('Пользователь');

            $rows[] = CheckBox::make('site.blocked')
                ->title('Заблокировать сайт')
                ->sendTrueOrFalse();

            $rows[] = Input::make('site.block_reason')
                ->maxlength(CustomString::MAX_LENGTH)
                ->title('Причина блокировки');
        }

        return [
            Layout::tabs([
                'Главные настройки' => [
                    Layout::rows($rows),
                ],
                'SEO' => [
                    Layout::rows([
                        Input::make('site.seo.title')
                            ->title('Мета-тэг title')
                            ->help('Если не заполнять, будет использовано название сайта'),
                        Input::make('site.seo.description')
                            ->title('Мета-тэг description')
                            ->help('Если не заполнять, не будет выведен'),
                        Input::make('site.seo.h1')
                            ->title('Тэг для H1.')
                            ->help('Если не заполнять, не будет выведен'),
                    ]),
                ],
            ]),
        ];
    }

    public function create(SiteScreenRequest $request): RedirectResponse
    {
        $command = CreateSiteCommand::fromArray(
            data: $request->validated(),
            userId: $request->resolvedUserId()
        );
        app(CreateSiteHandler::class)($command);

        Alert::info('Сохранено');

        return redirect()->route('platform.site.list');
    }

    public function update(SiteScreenRequest $request): RedirectResponse
    {
        $command = UpdateSiteCommand::fromArray(
            data: $request->validated(),
            siteId: $this->site->id,
            userId: $request->resolvedUserId()
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
