<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
       $clients = User::query()->where('type', UserTypeEnum::Client)->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'Done exercise created or updated successfully',
            'clients' => $clients,
        ]);
    }
}
