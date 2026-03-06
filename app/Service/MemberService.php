<?php

namespace App\Service;

use App\Consts\PlanConst;
use App\Models\FormSettingUser;
use App\Repositories\FormSettingUserRepository;
use Illuminate\Database\Eloquent\Builder;

class MemberService
{
    private FormSettingUserRepository $form_setting_member_repository;

    /**
     * コンストラクタ
     */
    public function __construct(FormSettingUserRepository $form_setting_member_repository)
    {
        $this->form_setting_member_repository = $form_setting_member_repository;
    }

    /**
     * @param int $form_setting_id
     * @return Builder
     */
    public function makeGetListByFormQuery(int $form_setting_id): Builder
    {
        return $this->form_setting_member_repository->makeGetListByFormQuery($form_setting_id);
    }

    /**
     * @param int $form_setting_id
     * @return int
     */
    public function getMemberCount(int $form_setting_id): int
    {
        return $this->form_setting_member_repository->getMemberCount($form_setting_id);
    }

    /**
     * @param int $user_id
     * @param int $form_setting_id
     * @return FormSettingUser
     */
    public function createRelation(int $user_id, int $form_setting_id): FormSettingUser
    {
        return  $this->form_setting_member_repository->createRelation($user_id, $form_setting_id);
    }

    /**
     * メンバーを招待できるか否かの判定
     * @param string $form_plan
     * @param int $member_count
     * @return bool
     */
    public function canInviteMember(string $form_plan, int $member_count): bool
    {
        // 上限値
        $limit = PlanConst::UPPER_COUNT[$form_plan];

        // 上限なし ※ FULLプランの場合
        if ($limit === null) {
            return true;
        }

        // 上限値よりも少なければ招待可能なのでtrueを返却。
        return $member_count < $limit;
    }

    /**
     * @param int $form_setting_id
     * @param int $user_id
     * @return int
     */
    public function delete(int $form_setting_id, int $user_id): int
    {
        return $this->form_setting_member_repository->delete($form_setting_id, $user_id);
    }

}
