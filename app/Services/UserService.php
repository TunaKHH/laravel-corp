<?php

namespace App\Services;

use App\Models\User;
use App\Utils\Result;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
    /**
     * 建立 UserService 實例
     */
    public function __construct()
    {
    }

    /**
     * 註冊新使用者
     *
     * @param array $data 用戶資料
     * @return User 創建的用戶實例
     */
    public function register($data)
    {
        // 密碼加密
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }

    /**
     * 獲取所有使用者按存款金額降序排序的列表
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsersSortedByDeposit()
    {
        return User::orderBy('deposit', 'desc')->get();
    }

    /**
     * 更新使用者個人資料
     * @param User $user 使用者實例
     * @param array $data 要更新的資料
     * @return Result 更新結果
     */
    public function updateUserProfile(User $user, array $data)
    {
        // 驗證使用者更新資料是否符合規則
        $validator = $this->validateUserProfile($user, $data);

        if ($validator->errors()->any()) {
            return Result::error($validator->errors());
        }
        // 更新使用者資料
        $this->updateUserAttributes($user, $data);

        return Result::success();
    }

    /**
     * 驗證使用者資料
     *
     * @param User $user 使用者實例
     * @param array $data 要驗證的資料
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateUserProfile(User $user, array $data): \Illuminate\Contracts\Validation\Validator
    {
        $messages = [
            'nickname.max' => '暱稱字數不得超過255',
            'email.max' => '信箱字數不得超過255',
            'email.email' => '信箱規則錯誤',
            'email.unique' => '信箱重複',
            'password.max' => '密碼字數不得超過255',
        ];
        $validator = Validator::make($data, [
            'nickname' => 'max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'password' => 'max:255',
        ], $messages);

        if (!empty($data['password']) &&
            !empty($data['old_password']) &&
            !empty($data['password2'])) {
            // 確認原本密碼是否正確
            if (!Hash::check($data['old_password'], $user->password)) {
                $validator->errors()->add('old_password', '舊密碼輸入錯誤');
            }
            if ($data['password'] != $data['password2']) {
                $validator->errors()->add('password', '兩次密碼不一致');
            }
        }
        return $validator;
    }

    /**
     * 更新使用者屬性
     *
     * @param User $user 使用者實例
     * @param array $data 要更新的資料
     */
    protected function updateUserAttributes(User $user, array $data)
    {
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (!empty($data['email'])) {
            $user->email = $data['email'];
        }
        $user->nickname = $data['nickname'];
        $user->line_id = $data['line_id'];
        $user->save();
    }
}
