<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Services\UserBadgeRecordService;
use Slowlyo\OwlAdmin\Controllers\AdminController;

/**
 * 用户勋章记录
 *
 * @property UserBadgeRecordService $service
 */
class UserBadgeRecordController extends AdminController
{
    protected string $serviceName = UserBadgeRecordService::class;

    public function list()
    {
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
            ->headerToolbar([
                $this->createButton('dialog'),
                ...$this->baseHeaderToolBar(),
            ])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('user_id', '用户')
                    ->type('link')
                    ->href('#/users/${user.id}')
                    ->body('[${user.id}] ${user.name}'),
                amis()->TableColumn('key', '键'),
                amis()->TableColumn('is_new', '新获得'),
                amis()->TableColumn('created_at', admin_trans('admin.created_at'))->type('datetime')->sortable(),
                amis()->TableColumn('updated_at', admin_trans('admin.updated_at'))->type('datetime')->sortable(),
                $this->rowActions('dialog'),
            ]);

        return $this->baseList($crud);
    }

    public function form($isEdit = false)
    {
        return $this->baseForm()->data([
            'users' => User::query()
                ->get()
                ->map(function (User $item) {
                    return [
                        'label' => '['.$item->id.'] '.$item->name,
                        'value' => $item->id,
                    ];
                }),
        ])->body([
            amis()->SelectControl('user_id', '用户')
                ->source('${users}'),
            amis()->TextControl('key', '键'),
            amis()->SwitchControl('is_new', '新获得'),
        ]);
    }

    public function detail()
    {
        return $this->baseDetail()->body([
            amis()->TextControl('id', 'ID')->static(),
            amis()->TextControl('user_id', '用户 ID')->static(),
            amis()->TextControl('key', '键')->static(),
            amis()->TextControl('is_new', '新获得')->static(),
            amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
            amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
        ]);
    }
}
