<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Consts\PlanConst;
use App\Http\Controllers\UserController;
use Illuminate\Contracts\View\View;

/**
 *
 */
class DashboardController extends UserController
{

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return View
     */
    public function __invoke(): View
    {
        $can_create_form = $this->canCreateForm();

        return view('user.dashboard', compact('can_create_form'));
    }

    /**
     * @return bool
     */
    private function canCreateForm(): bool
    {
        // 現在作成しているフォーム数
        $my_form_count = $this->my_user->myForm->count();

        // 上限値
        $limit = PlanConst::UPPER_FORM_COUNT[$this->my_plan];

        // 上限なし ※ FULLプランの場合
        if ($limit === null) {
            return true;
        }

        // 上限値よりも少なければ、新しいフォームを作成可能なのでtrueを返却
        return $my_form_count < $limit;
    }

}
