<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;

class BaseRepository{
    protected $model;

    public function getAll($relations = [])
    {
        try {
            return $this->model->with($relations)->get();
        } catch (\Exception $exception) {
            Log::error(($exception));
            return false;
        }
    }

    public function getById($id, $relations = [])
    {
        try {
            return $this->model->with($relations)->find($id);
        } catch (\Exception $exception) {
            Log::error(($exception));
            return false;
        }
    }

    public function store($data)
    {
        try {
            if($data->file('image')){
                $data['image'] = $this->uploadImage($data->file('image'));
            }

            return $this->model->create($data);
        } catch (\Exception $exception) {
            Log::error(($exception));
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $model = $this->model->find($id);
            if (!$model) {
                return false;
            }

            if($data->file('image')){
                $data['image'] = $this->uploadImage($data->file('image'));
            }
            
            $model->update($data);
            return $model;
        } catch (\Exception $exception) {
            Log::error(($exception));
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $model = $this->model->find($id);
            if ($model) {
                $model->delete();
            }
            return $model;
        } catch (\Exception $exception) {
            Log::error(($exception));
            return false;
        }
    }

    public function uploadImage($data){
        $image = $data->file('image');
        $imageName = $image->getClientOriginalName();
        $imagePath = $data->file('image')->storeAs($this->model, $imageName, 'public');
        return $imagePath;
    }
}