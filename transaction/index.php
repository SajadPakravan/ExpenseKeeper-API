<?php
include '_init_.php';

$id = authorization();

$method = $_SERVER['REQUEST_METHOD'];

$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$t_id = $uri[count($uri) - 1];

$title = $description = $amount = $type = $create_at = '';

switch ($method) {
    case 'GET':
    {
        $page = intval(param('page', false)) ?? 1;
        $perPage = intval(param('per-page', false)) ?? 10;

        $offset = ($page - 1) * $perPage;

        $transaction = Database::select(
            table: 'transaction',
            where: 'user_id = ? LIMIT ? OFFSET ?',
            value: [$id, $perPage, $offset]
        );

        $data = [];
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
        exit(json_encode($data));
    }
    case 'POST':
    {
        setParam();
        Database::insert(table: 'transaction', data: [
            'user_id' => $t_id,
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
        setParam();
        Database::update(table: 'transaction', set: [
            'title' => $title,
            'description' => $description,
            'amount' => $amount,
            'type' => $type,
            'create_at' => $create_at,
        ], where: ['id' => $t_id]);
        exit(json_encode(['message' => 'تراکنش شما با موفقیت بروزرسانی شد']));
    }
    case 'DELETE':
    {
        Database::delete(table: 'transaction', where: ['id' => $t_id]);
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
    $create_at = param('create_at');
}