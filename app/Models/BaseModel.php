<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Shared behaviour for every model in the application.
 *
 * The most important piece here is tenant scoping. Every record belongs to an
 * `admin_id` (the organisation), and previously the filtering was done in the
 * views with `if ($row['admin_id'] == $_SESSION[...])`, which meant the rows of
 * other organisations were still fetched, and update/delete had no check at
 * all. `forTenant()` and `findOwned()` push that check down into the query.
 */
abstract class BaseModel extends Model
{
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $dateFormat     = 'datetime';
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $skipValidation = false;

    /**
     * Restrict the query to a single organisation.
     */
    public function forTenant(int $adminId): self
    {
        return $this->where($this->table . '.admin_id', $adminId);
    }

    /**
     * Fetch one record only if it belongs to the given organisation.
     *
     * @return array|null
     */
    public function findOwned(int $id, int $adminId)
    {
        return $this->where($this->table . '.' . $this->primaryKey, $id)
            ->where($this->table . '.admin_id', $adminId)
            ->first();
    }

    /**
     * Delete one record only if it belongs to the given organisation.
     */
    public function deleteOwned(int $id, int $adminId): bool
    {
        if ($this->findOwned($id, $adminId) === null) {
            return false;
        }

        return (bool) $this->delete($id);
    }

    /**
     * Narrow a query to what one class is allowed to see.
     *
     * A NULL `class_id` means "the whole organisation", so a class sees its own
     * rows plus those addressed to everyone. A student who has not been put in
     * a class yet ($classId === null) sees only the everyone rows — never the
     * whole organisation, which is what a plain "no filter" would have given
     * them.
     *
     * @param mixed $builder
     */
    protected function scopeToClass($builder, ?int $classId)
    {
        $column = $this->table . '.class_id';

        if ($classId === null) {
            return $builder->where($column, null);
        }

        return $builder->groupStart()
            ->where($column, $classId)
            ->orWhere($column, null)
            ->groupEnd();
    }

    /**
     * Update one record only if it belongs to the given organisation.
     */
    public function updateOwned(int $id, int $adminId, array $data): bool
    {
        if ($this->findOwned($id, $adminId) === null) {
            return false;
        }

        return (bool) $this->update($id, $data);
    }
}
