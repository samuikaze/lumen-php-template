<?php

namespace App\Repositories;

use App\Exceptions\EntityNotFoundException;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    /**
     * The model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * 是否自動以 Transaction 進行資料庫資料變更
     *
     * @var bool;
     */
    protected $auto_transaction = true;

    /**
     * 設定是否自動以 Transaction 進行資料庫資料變更，預設為 true
     *
     * @param bool $auto_transaction
     * @return void
     */
    public function setAutoTransaction(bool $auto_transaction): void
    {
        $this->auto_transaction = $auto_transaction;
    }

    /**
     * 自訂 Transaction 範圍
     *
     * @param \Closure $transactions
     * @return void
     */
    public function transactionClosure(Closure $transactions): void
    {
        $this->setAutoTransaction(false);

        DB::transaction($transactions);

        $this->setAutoTransaction(true);
    }

    /**
     * 以單一 ID 取得資料
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(int $id): Model
    {
        return $this->model->find($id);
    }

    /**
     * 以多筆 ID 取得資料
     *
     * @param array<int, int>|BaseCollection<int, int> $ids
     * @return \Illuminate\Database\Eloquent\Collection<int, Model>
     */
    public function getByIds(array|BaseCollection $ids): Collection
    {
        return $this->model
            ->whereIn($this->model->getKeyName(), $ids)
            ->get();
    }

    /**
     * 建立新資料
     *
     * @param array<string, mixed> $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * 建立多筆新資料
     *
     * @param array<int, array<string, mixed>>|BaseCollection<int, array<string, mixed>> $attributes
     * @return BaseCollection<int, \Illuminate\Database\Eloquent\Model>
     */
    public function bulkCreate(array|BaseCollection $attributes): BaseCollection
    {
        if ($this->auto_transaction) {
            DB::beginTransaction();
        }

        $model_class = get_class($this->model);

        try {
            $models = collect();
            foreach ($attributes as $attribute) {
                $model = new $model_class();

                foreach ($attribute as $column => $value) {
                    $model->{$column} = $value;
                }

                $model->save();
            }

            if ($this->auto_transaction) {
                DB::commit();
            }
        } catch (Exception $e) {
            report($e);

            if ($this->auto_transaction) {
                DB::rollBack();
            }

            throw $e;
        }

        return $models;
    }

    /**
     * 更新單筆資料
     *
     * @param int $id
     * @param array<string, mixed> $attributes
     * @param array<string, mixed> $options
     * @return bool
     */
    public function update(int $id, array $attributes = [], array $options = []): bool
    {
        $primary_key_name = $this->model->getKeyName();

        return $this->model
            ->where($primary_key_name, $id)
            ->update($attributes, $options);
    }

    /**
     * 透過 Transaction 更新單筆資料
     *
     * @param int $id
     * @param array<string, mixed> $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function safeUpdate(int $id, array $attributes): Model
    {
        if ($this->auto_transaction) {
            DB::beginTransaction();
        }

        $model = $this->model->find($id);
        if (is_null($model)) {
            throw new EntityNotFoundException('找不到該筆資料');
        }

        try {
            foreach ($attributes as $column => $value) {
                $model->{$column} = $value;
            }

            $model->save();

            if ($this->auto_transaction) {
                DB::commit();
            }
        } catch (Exception $e) {
            report($e);

            if ($this->auto_transaction) {
                DB::rollBack();
            }

            throw $e;
        }

        return $model;
    }

    /**
     * 以 Model 透過 Transaction 更新資料
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function modelSafeUpdate(Model $model): Model
    {
        if ($this->auto_transaction) {
            DB::beginTransaction();
        }

        try {
            $model->save();

            if ($this->auto_transaction) {
                DB::commit();
            }
        } catch (Exception $e) {
            report($e);

            if ($this->auto_transaction) {
                DB::rollBack();
            }

            throw $e;
        }

        return $model;
    }

    /**
     * 以 Model 透過 Transaction 更新多筆資料
     *
     * @param array<int, Model>|BaseCollection<int, Model> $models
     * @return BaseCollection<int, Model>
     */
    public function bulkModelSafeUpdate(array|BaseCollection $models): BaseCollection
    {
        if ($this->auto_transaction) {
            DB::beginTransaction();
        }

        try {
            foreach ($models as $model) {
                $model->save();
            }

            if ($this->auto_transaction) {
                DB::commit();
            }
        } catch (Exception $e) {
            report($e);

            if ($this->auto_transaction) {
                DB::rollBack();
            }

            throw $e;
        }

        return $models;
    }

    /**
     * 更新多筆資料
     *
     * @param array<int, int>|\Illuminate\Support\Collection<int, int> $ids
     * @param array<string, mixed> $attributes
     * @param array<string, mixed> $options
     * @return bool
     */
    public function bulkUpdate(array|BaseCollection $ids, array $attributes, array $options): bool
    {
        if ($ids instanceof BaseCollection) {
            $ids = $ids->toArray();
        }

        $result = true;

        $primary_key_name = $this->model->getKeyName();

        foreach ($ids as $id) {
            $this_result = $this->model
                ->where($primary_key_name, $id)
                ->update($attributes, $options);

            $result &= $this_result;
        }

        return $result;
    }

    /**
     * 透過 Transaction 更新多筆資料
     *
     * @param array<int, int>|\Illuminate\Support\Collection<int, int> $ids
     * @param array<string, mixed> $attributes
     * @return \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model>
     */
    public function bulkSafeUpdate(int $ids, array $attributes): Collection
    {
        if ($ids instanceof BaseCollection) {
            $ids = $ids->toArray();
        }

        if ($this->auto_transaction) {
            DB::beginTransaction();
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model> $models */
        $models = $this->model
            ->whereIn($this->model->getKeyName(), $ids)
            ->get();

        try {
            foreach ($models as $model) {
                foreach ($attributes as $column => $value) {
                    $model->{$column} = $value;
                }

                $model->save();
            }

            if ($this->auto_transaction) {
                DB::commit();
            }
        } catch (Exception $e) {
            report($e);

            if ($this->auto_transaction) {
                DB::rollBack();
            }

            throw $e;
        }

        return $models;
    }

    /**
     * 刪除指定資料
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model
            ->where($this->model->getKeyName(), $id)
            ->delete();
    }

    /**
     * 大量刪除資料
     *
     * @param array<int, int>|\Illuminate\Support\Collection<int, int> $ids
     * @return bool
     */
    public function bulkDelete(array|Collection $ids): bool
    {
        return $this->model
            ->whereIn($this->model->getKeyName(), $ids)
            ->delete();
    }
}
