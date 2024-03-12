<?php 

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository {

    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function getList($request) // $request
    {
        $user  = $this->model->query();

        if (!empty($request->search)) {
            $user->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $user = $user->latest();
        $user = $user->paginate(config('constants.paginate'));
   
        return $user;
        // return $this->model
        // ->when($request->search_text, function($q) use ($request) {
        //     return $q->where(function($q) use ($request) {
        //         $q->where('name', 'like', '%' . $request->search_text . '%')
        //         ->orWhere('email', 'like', '%' . $request->search_text . '%');
        //     });
        // })   
        // ->when($request->start_date, function($q) use ($request) {
        //     return $q->where('created_at', '>=', $request->start_date);
        // })
        // ->when($request->end_date, function($q) use ($request) {
        //     return $q->where('created_at', '<=', $request->end_date);
        // })
        // ->latest()
        // ->paginate(config('constants.paginate'));        
    }

}