<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $schoolId = app()->bound('current_school_id')
            ? app()->make('current_school_id')
            : null;

        if ($schoolId !== null) {
            $builder->where($model->getTable() . '.school_id', $schoolId);
        }
        // Super Admin: school_id null → tidak ada filter → lihat semua data
    }
}
