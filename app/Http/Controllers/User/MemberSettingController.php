<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Consts\MailConst;
use App\Http\Controllers\UserController;
use App\Http\Requests\User\Member\InviteMemberRequest;
use App\Models\FormSetting;
use App\Models\User;
use App\Service\MailService;
use App\Service\MemberService;
use App\Service\UserService;
use App\Traits\PasswordTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class MemberSettingController extends UserController
{
    use PasswordTrait;

    private MemberService $member_service;
    private UserService $user_service;

    /**
     * コンストラクタ
     */
    public function __construct(
        MemberService $member_service,
        UserService   $user_service,
    ) {
        parent::__construct();

        $this->member_service = $member_service;
        $this->user_service = $user_service;
    }

    /**
     * メンバー管理画面を表示
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        // このフォームのプランを取得
        $form_plan = $this->plan_service->getPlanByUser($form_setting->owner_user);

        // このフォームに何人がメンバーとして招待されている取得
        $member_count = $this->member_service->getMemberCount($form_setting->id);

        // 新たにメンバーを招待できるか確認
        $can_invite_member = $this->member_service->canInviteMember($form_plan, $member_count);

        return view('user.form.member.list', [
            'form_setting' => $form_setting,
            'form_plan' => $form_plan,
            'can_invite_member' => $can_invite_member,
        ]);
    }

    /**
     * メンバー一覧を取得
     * @param FormSetting $form_setting
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getMember(FormSetting $form_setting, Request $request): JsonResponse
    {
        $query = $this->member_service->makeGetListByFormQuery($form_setting->id);

        return DataTables::of($query)
            ->make();
    }

    /**
     * 招待を行う
     * @param FormSetting $form_setting
     * @param InviteMemberRequest $request
     * @return RedirectResponse
     */
    public function inviteMember(FormSetting $form_setting, InviteMemberRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($form_setting, $request) {

                $user = $this->user_service->getByEmail($request->email);
                $first_register = false;
                $password = '';

                if (is_null($user)) {
                    $password = $this->makeFirstPassword();

                    // ユーザーの登録
                    $user = $this->user_service->create([
                        'email' => $request->email,
                        'password' => $password,
                    ]);
                    $first_register = true;
                }

                // リレーション作成
                $this->member_service->createRelation($user->id, $form_setting->id);

                // メール送信
                $param = [
                    'to_mail_address' => $request->email,
                    'form_name' => $form_setting->form_name,
                    'owner_name' => $this->my_user->name,
                    'password' => $password,
                ];
                if ($first_register) {
                    app(MailService::class)->sendMailPattern(MailConst::INVITE_MEMBER_FIRST_REGISTER, $param);
                } else {
                    app(MailService::class)->sendMailPattern(MailConst::INVITE_MEMBER, $param);
                }

            });
            return redirect()->back()->with('success', ['メンバーに招待しました']);
        } catch (Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['メンバーの招待に失敗しました']);
        }
    }

    /**
     * 招待の削除を行う
     * @param FormSetting $form_setting
     * @param User $user
     * @return RedirectResponse
     */
    public function deleteMember(FormSetting $form_setting, User $user): RedirectResponse
    {
        try {
            DB::transaction(function () use ($form_setting, $user) {
                $this->member_service->delete($form_setting->id, $user->id);
            });
            return redirect()->back()->with('success', ['メンバーを削除しました']);
        } catch (Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['メンバーの削除に失敗しました']);
        }
    }

}
