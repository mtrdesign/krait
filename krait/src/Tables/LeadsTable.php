<?php

namespace MtrDesign\Krait\Tables;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LeadsTable extends BaseTable
{
    protected function shouldRefresh(): bool
    {
        return true;
    }

    function name(): string
    {
        return 'leads-table';
    }

    function initColumns(): void
    {
        $this->column(
            name: 'first_name',
            label: 'First name',
            process: fn(User $user) => $user->name
        );
        $this->column(
            name: 'last_name',
            label: 'Last name',
            process: fn(User $user) => $user->name . 'l'
        );
        $this->column(
            name: 'third_name',
            label: 'Third name',
            process: fn(User $user) => $user->name . 'th'
        );
    }

    function additionalData(Model $resource): array
    {
        return [
            'other_prop' => 'test',
        ];
    }
}
