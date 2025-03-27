<?php

namespace App\Http\Controllers\API\transaction;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
        $userAuth = $request->user();

        $data = $request->validate([
            'title'     => 'required|string',
            'amount' => 'required|decimal:0',
            'type' => 'required|in:income,cost',
            'description' => 'nullable|string',
            'created_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        Transactions::create([
            'user_id' => $userAuth->user_id,
            'title' => $data['title'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'description' => $data['description'],
            'created_at' => $data['created_at'],
        ]);

        return response()->json([
            'message' => 'تراکنش با موفقیت ثبت شد',
        ], 200);
    }

    public function update(Request $request)
    {
        $userAuth = $request->user();

        $data = $request->validate([
            'title'     => 'required|string',
            'amount' => 'required|decimal:0',
            'type' => 'required|in:income,cost',
            'description' => 'nullable|string',
            'created_at' => 'required|date_format:Y-m-d H:i:s',
        ]);
    }

    public function getAll(Request $request) {}

    public function getOne(Request $request) {}

    public function delete(Request $request) {}
}
