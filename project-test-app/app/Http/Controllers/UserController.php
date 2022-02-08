<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Wallet;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api');
    }


    public function createUserInfo(Request $request) {

        $rules = [
            'user_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required',
            'state' => 'required',
            "gender" => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
      if ($validator->fails()) {
            return response()->json([
            'status' => false,
            'message' => 'invalid detail(s) supplied'
        ], 400);
      }
        $user = User::where('id', $request->user_id)->first();

        throw_if(!$user,
            new \InvalidArgumentException('User not found')
        );

        $user_info = new UserInfo;
        $user_info->user_id = $request->user_id;
        $user_info->name = $request->name;
        $user_info->address = $request->address;
        $user_info->dob = $request->date_of_birth;
        $user_info->state =$request->state;
        $user_info->gender =$request->gender;
        $user_info->save();

        $user->is_registered = TRUE;
        $user->save();

        return response()->json([
            'status'    => true
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