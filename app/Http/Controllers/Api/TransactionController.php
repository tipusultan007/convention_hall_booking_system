<?php
// app/Http/Controllers/Api/TransactionController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource; // We will create this
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest('transaction_date')->latest('id')->paginate(30);
        return TransactionResource::collection($transactions);
    }
}