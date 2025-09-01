<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{

    public function __construct(
        private readonly Model $model
    ) {}


    public function paginate(int|null $page = null, int|null $limit = null, string | array $relations = [])
    {
        $limit ??= request()->input('limit', 15);
        $page ??= request()->input('page', 1);

        return $this->model->with($relations)->paginate(perPage: $limit, page: $page);
    }
}
