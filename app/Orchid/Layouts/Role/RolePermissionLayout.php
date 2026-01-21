<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Role;

use App\User\Domain\Models\User;
use Illuminate\Support\Collection;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Rows;
use Throwable;

class RolePermissionLayout extends Rows
{
    private ?User $user = null;

    /**
     * The screen's layout elements.
     *
     *
     * @return Field[]
     *
     * @throws Throwable
     */
    public function fields(): array
    {
        $this->user = $this->query->get('user');

        return $this->generatedPermissionFields(
            $this->query->getContent('permission')
        );
    }

    private function generatedPermissionFields(Collection $permissionsRaw): array
    {
        return $permissionsRaw
            ->map(fn(Collection $permissions, $title) => $this->makeCheckBoxGroup($permissions, $title))
            ->flatten()
            ->toArray();
    }

    /**
     * @param Collection<int, array{
     *     slug: string,
     *     description: string|null,
     *     active: numeric-string
     * }> $permissions
     * @return Collection<int, Group>
     */
    private function makeCheckBoxGroup(Collection $permissions, string $title): Collection
    {
        return $permissions
            ->map(fn(array $chunks) => $this->makeCheckBox(collect($chunks)))
            ->flatten()
            ->map(fn(CheckBox $checkbox, $key) => $key === 0
                ? $checkbox->title($title)
                : $checkbox)
            ->chunk(4)
            ->map(fn(Collection $checkboxes) => Group::make($checkboxes->toArray())
                ->alignEnd()
                ->autoWidth());
    }

    /**
     * @param  Collection<string, string>  $chunks
     */
    private function makeCheckBox(Collection $chunks): CheckBox
    {
        /** @var string $slug */
        $slug = $chunks->get('slug');
        /** @var string $active */
        $active = $chunks->get('active');
        /** @var ?string $description */
        $description = $chunks->get('description');

        return CheckBox::make('permissions.' . base64_encode($slug))
            ->placeholder($description)
            ->value($active)
            ->sendTrueOrFalse()
            ->indeterminate($this->getIndeterminateStatus(
                $slug,
                $active
            ));
    }

    private function getIndeterminateStatus(string $slug, string $value): bool
    {
        if ($this->user === null) {
            return false;
        }

        return $this->user->hasAccess($slug) && $value === '0';
    }
}
