<?php
include '_init_.php';

$id = authorization();

$method = $_SERVER['REQUEST_METHOD'];

$title = $description = $amount = $type = $create_at = '';

switch ($method) {
    case 'GET':
    {
        $page = $_GET['page'] ?? 1;
        $perPage = $_GET['per-page'] ?? 10;

        $offset = (intval($page) - 1) * intval($perPage);

        $transaction = Database::select(
            table: 'transaction',
            where: "user_id = ? LIMIT $perPage OFFSET $offset",
            value: [$id]
        );

        $data = [];
        if (!empty($transaction)) {
            foreach ($transaction as $t) {
                $data[] = [
                    'id' => $t['id'],
                    'user_id' => $t['user_id'],
                    'title' => $t['title'],
                    'description' => $t['description'],
                    'amount' => $t['amount'],
                    'type' => $t['type'],
                    'create_at' => $t['create_at'],
                ];
            }
        }
        exit(json_encode($data));
    }
    case 'POST':
    {
        setParam();
        Database::insert(table: 'transaction', data: [
            'user_id' => $id,
            'title' => $title,
            'description' => $description,
            'amount' => $amount,
            'type' => $type,
            'create_at' => $create_at,
        ]);
        exit(json_encode(['message' => 'تراکنش شما با موفقیت ذخیره شد']));
    }
    case 'PUT':
    {
        $transactionID = $_GET['id'] ?? setError(400, 'id Empty');
        setParam();
        Database::update(table: 'transaction', set: [
            'title' => $title,
            'description' => $description,
            'amount' => $amount,
            'type' => $type,
            'create_at' => $create_at,
        ], where: ['id' => $transactionID]);
        exit(json_encode(['message' => 'تراکنش شما با موفقیت بروزرسانی شد']));
    }
    case 'DELETE':
    {
        $transactionID = $_GET['id'] ?? setError(400, 'id Empty');
        Database::delete(table: 'transaction', where: ['id' => $transactionID]);
        exit(json_encode(['message' => 'تراکنش شما با موفقیت حذف شد']));
    }
    default:
        setError(405, 'Method Not Allowed');
        break;
}

function setParam(): void
{
    global $title, $description, $amount, $type, $create_at;
    $title = param('title');
    $description = param('description', false);
    $amount = param('amount');
    $type = param('type');
    if ($type !== 'COST' && $type !== 'INCOME') setError(400, 'Wrong Type | COST or INCOME');
    $create_at = param('create_at');
}