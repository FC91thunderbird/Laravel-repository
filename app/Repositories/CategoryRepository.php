<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository 
{
    protected $model;

    public function __construct()
    {
        $this->model = new Category();
    }

    public function getList($search = null, $perPage = null)
    {
        $query = $this->model->query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
       
        // if (!empty($orderBy)) {
        //     foreach ($orderBy as $key => $value) {
        //         $query = $query->orderBy($key, $value);
        //     }
        // }
        
        $query->orderBy('id', 'desc');

        if ($perPage == -1) {
            $query = $query->get();
        } else {
            if ($perPage) {
                $query = $query->paginate($perPage);
            } else {
                $query = $query->paginate(10);
            }
        }
        return $query;
    }

}