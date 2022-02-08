<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Wallet;

class WalletController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api');
    }


    public function transferMoney(Request $request) {

        $rules = [
            'user_wallet_id' => 'required',
            'recipient_wallet_id' => 'required',
            'amount' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
      if ($validator->fails()) {
            return response()->json([
            'status' => false,
            'message' => 'invalid detail(s) supplied'
        ], 400);
      }
        $user_wallet = Wallet::where('wallet_id', $request->user_wallet_id)->first();

        throw_if(!$user_wallet,
            new \InvalidArgumentException('User wallet not found')
        );

        throw_if(!$user_wallet->total_balance < $request->amount,
            new \InvalidArgumentException('Insufficient balance')
        );

        $recipient_wallet = Wallet::where('wallet_id', $request->recipient_wallet_id)->first();

        throw_if(!$recipient_wallet,
            new \InvalidArgumentException('Wallet does not exist')
        );

         throw_if($request->amount < 1,
            new \InvalidArgumentException('Negative amount can not be transferred to another user')
        );

        $user_wallet->total_balance -= $request->amount;
        $user_wallet->save();

        $recipient_wallet->total_balance += $request->amount;
        $recipient_wallet->save();


        return response()->json([
            'status'    => true,
            'data' => $user_wallet
        ], 200);
    }

    public function getUserInfo($id) {
        $user_wallet = Wallet::where('user_id', $id)->first();
        $user_info = UserInfo::with('user')->where('user_id', $id)->first();

        return response()->json([
            'status'    => true,
            'data' => [
                'user_info' => $user_info,
                'user_wallet' => $user_wallet
            ]
        ], 200);
    }
 
}