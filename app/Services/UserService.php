<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\Uids\Uid;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Request as UserRequest;

class UserService
{
    public function addUserByRequest(Request $request, string $requestType) {
        try {
            $user = new User();
            $user->status = User::REGISTERED_STATUS;
            if ($requestType == "telegram") {
                $user->first_name = $request->message->from->first_name;
                $user->last_name = $request->message->from->last_name;
            }
            $user->save();
            $uid = UidService::createUidByRequest($request, $user, $requestType);


            $request = Request::where(['type' => 'registry', 'hash' => $hash])->first();

            if (@!$request->id) {
                throw new \Exception(
                    LanguageContent::getDataByTagAndLang("invalid_command_from_site_error", 0),
                    7
                );
            }

            @$checkUser = User::where('tg_id', $userData['id'])->orWhere('reg_hash', $hash)->first();
            if (@$checkUser->id) {
                $checkUserArray = $checkUser->toArray();
                $delayDate = time() - (self::REQUEST_DELAY_DAYS * 3600 * 24);

                if (strtotime($checkUserArray['updated_at']) > $delayDate) {
                    throw new \Exception(
                        LanguageContent::getDataByTagAndLang("too_often_registration_user_error", 0),
                        7
                    );
                } else {
                    if (@$userData['first_name']) {
                        $checkUser->first_name = $userData['first_name'];
                    }

                    if (@$userData['last_name']) {
                        $checkUser->last_name = $userData['last_name'];
                    }

                    if (@$userData['username']) {
                        $checkUser->tg_name = $userData['username'];
                    }
                    $checkUser->test_score = $request->test_score;
                    $checkUser->reg_hash = $request->hash;
                    $checkUser->user_data = json_encode($userData, JSON_UNESCAPED_UNICODE);
                    $checkUser->test_data = $request->data;
                    $checkUser->save();
                    $newId = $checkUser->getKey();
                    return [
                        'id' => $newId,
                        'text' => LanguageContent::getDataByTagAndLang("previously_applied_warning_message", 0),
                        "repeater" => true
                    ];
                }
            }

            if (@!$userData['username']) {
                throw new \Exception(
                    LanguageContent::getDataByTagAndLang("telegram_undefined_username_error", 0),
                    7
                );
            }

            $this->tg_id = $userData['id'];

            if (@$userData['first_name']) {
                $this->first_name = $userData['first_name'];
            }

            if (@$userData['last_name']) {
                $this->last_name = $userData['last_name'];
            }

            if (@$userData['username']) {
                $this->tg_name = $userData['username'];
            }

            $this->test_score = $request->test_score;
            $this->reg_hash = $request->hash;
            $this->user_data = json_encode($userData, JSON_UNESCAPED_UNICODE);
            $this->test_data = $request->data;
            $this->save();
            $newId = $this->getKey();

            return [
                'id' => $newId,
                'text' => LanguageContent::getDataByTagAndLang("thanks_for_registration_message", 0, [
                    'new_id' => [
                        LanguageController::EN_LANGUAGE => $newId,
                        LanguageController::RU_LANGUAGE => $newId,
                        'timeStamp' => 0,
                    ],
                ])
            ];






            if (@$userInfo["repeater"]) {
                $userMessage = "{$userInfo['text']}.\n\n" . LanguageContent::getDataByTagAndLang("telegram_question_to_repeater", 0);
                $bot->echoMessage($userMessage, self::KEY_FOR_REPEATER_REQUEST);
            } else {
                $this->sendAdminNotes($bot, $userInfo['id']);
                $userMessage = "{$userInfo['text']}.\n\n" . LanguageContent::getDataByTagAndLang("telegram_success_answer_to_new_user", 0);
                $bot->echoMessage($userMessage);
            }
        } catch (\Exception $e) {
            if($e->getCode() !== 7) {
                $bot->echoMessage(
                    LanguageContent::getDataByTagAndLang("telegram_unexpected_user_error", 0)
                );
                $bot->setChat(self::ADMIN_CHAT_ID);
                $bot->sendMessage("Error! During the user registration! \n\n" . " | file: " . $e->getFile() . " | string: " . $e->getLine() . "\n\n" . $e->getMessage());
            } else {
                $bot->echoMessage("Error: " . $e->getMessage());
            }
        }
    }
}
