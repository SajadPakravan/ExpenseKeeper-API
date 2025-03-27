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

    public function update($id, Request $request)
    {
        $data = $request->validate([
            'title'     => 'required|string',
            'amount' => 'required|decimal:0',
            'type' => 'required|in:income,cost',
            'description' => 'nullable|string',
            'created_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $transaction = Transactions::find($id);

        $transaction->update($data);

        return response()->json([
            'message' => 'تراکنش با موفقیت تغییر کرد',
        ], 200);
    }

    public function getAll(Request $request)
    {
        $userAuth = $request->user();

        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $transactions = Transactions::where('user_id', $userAuth->user_id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($transactions, 200);
    }

    public function getOne($id, Request $request)
    {
        $transactions = Transactions::where('id', $id)->first();

        return response()->json($transactions, 200);
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'integer|exists:transactions,id'
        ]);
    
        Transactions::whereIn('id', $data['transaction_ids'])->delete();
    
        return response()->json([
            'message' => 'تراکنش‌های مورد نظر با موفقیت حذف شدند',
        ], 200);
    }
}
