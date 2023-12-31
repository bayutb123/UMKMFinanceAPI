<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookAccount;
use App\Http\Requests\BookAccountRequest;

class BookAccountController extends Controller
{
    public function addAccountRecord(BookAccountRequest $request) {
        $validated = $request->validated();

        $checkDuplicate = BookAccount::where('owner_id', $validated['owner_id'])
            ->where('name', $validated['name'])
            ->first();

        if ($validated && !$checkDuplicate) {
            $account = BookAccount::create($validated);
            return response()->json([
                'message' => 'Success',
                'account' => $account
            ], 200);
        }

        return response()->json([
            'message' => 'Failed',
            'error' => 'Account already exists'
        ], 400);
    }

    public function getAccounts($userId) {
        $accounts = BookAccount::where('owner_id', $userId)->get();
        return response()->json([
            'message' => 'Success',
            'accounts' => $accounts
        ], 200);
    }

    public function updateAccountBalance(BookAccountRequest $request) {
        $validated = $request->validated();
        
        if ($validated) {
            $account = BookAccount::where('id', $validated['id'])
                ->where('owner_id', $validated['owner_id']);

            if ($account) {
                $account->update(['balance' => $validated['balance']]);
                return response()->json([
                    'message' => 'Success',
                    'account' => $account
                ], 200);
            }
        }

        return response()->json([
            'message' => 'Failed',
            'error' => 'Account not found'
        ], 400);
    }

    public function deleteAccount($id) {
        $account = BookAccount::where('id', $id)->first();
    
        if ($account != null && $account->is_default == false) {
            if ($account->balance == 0) {
                $account->delete();
                return response()->json([
                    'message' => 'Success',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Failed',
                    'error' => 'Account balance is not 0'
                ], 400);
            }
        }

        return response()->json([
            'message' => 'Failed',
            'error' => 'Account not found or is default account'
        ], 400);

    }

}
