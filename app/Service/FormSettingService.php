<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\FormSetting;
use App\Repositories\FormSettingRepository;
use Illuminate\Database\Eloquent\Builder;

class FormSettingService
{
    private FormSettingRepository $form_setting_repository;

    public function __construct(
        FormSettingRepository $form_setting_repository,
    ) {
        $this->form_setting_repository = $form_setting_repository;
    }

    /**
     * @param string $route_name
     * @return FormSetting|null
     */
    public function getSurveyByRouteName(string $route_name): ?FormSetting
    {
        return FormSetting::where('route_name', $route_name)
            ->where('host_name',  request()->getHost())
            ->with('formItems')->first();
    }

    /**
     * @param int $user_id
     * @param string|null $form_name 管理名（部分一致）
     * @param array<int, int>|null $publication_statuses 状態（公開/非公開）
     * @return Builder
     */
    public function getFormListQuery(
        int $user_id,
        ?string $form_name = null,
        array $publication_statuses
    ): Builder {
        $select = [
            'form_settings.id',
            'form_settings.form_name',
            'form_settings.title',
            'form_settings.start_date',
            'form_settings.end_date',
            'form_settings.publication_status',
            'form_settings.created_by_user',
            'users.name AS owner_name',
            'form_settings.created_at',
        ];

        $query = FormSetting::select($select)
            ->where('host_name', request()->getHost())
            ->leftJoin('form_setting_user', function ($join) {
                $join->on('form_settings.id', '=', 'form_setting_user.form_setting_id')
                    ->whereNull('form_setting_user.deleted_at');
            })
            ->join('users', 'users.id', '=', 'form_settings.owner_user')
            ->where(function ($q) use ($user_id) {
                $q->where('form_settings.owner_user', $user_id)
                    ->orWhere('form_setting_user.user_id', $user_id);
            })
            ->withCount('applications');

        if ($form_name !== null && $form_name !== '') {
            $query->where('form_settings.form_name', 'like', '%' . $form_name . '%');
        }

        if ($publication_statuses) {
            $query->whereIn('form_settings.publication_status', $publication_statuses);
        }

        return $query;
    }

    /**
     * @param array $param
     * @param string $host_name
     * @return FormSetting
     */
    public function create(array $param, string $host_name): FormSetting
    {
        return FormSetting::create([
            'host_name' => $host_name,
            'form_name' => $param['form_name'],
            'title' => $param['title'],
            'route_name' => $param['route_name'] ?? bin2hex(random_bytes(16)),
            'admin_email' => 'admin@test.com',
            'start_date' => $param['start_date'] ?? null,
            'end_date' => $param['end_date'] ?? null,
            'max_applications' => $param['max_applications'] ?? null,
            'image_directory' => $param['image_directory'] ?? null,
            'css_filename' => $param['css_filename'] ?? null,
            'banner_filename' => $param['banner_filename'] ?? null,
            'publication_status' => $param['publication_status'] ?? 0,
            'created_by_admin' => $param['created_by_admin'] ?? null,
            'created_by_user' => $param['created_by_user'] ?? null,
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @param array $param
     * @return bool
     */
    public function update(FormSetting $form_setting, array $param)
    {
        return $form_setting->update($param);
    }

    /**
     * @param FormSetting $form_setting
     * @return bool
     */
    public function delete(FormSetting $form_setting): bool
    {
        return $this->form_setting_repository->delete($form_setting);
    }

}
