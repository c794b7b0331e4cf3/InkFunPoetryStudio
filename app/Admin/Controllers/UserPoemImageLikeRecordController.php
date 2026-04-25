<?php

namespace App\Admin\Controllers;

use App\Models\PoemImage;
use App\Models\User;
use App\Services\UserPoemImageLikeRecordService;
use Slowlyo\OwlAdmin\Controllers\AdminController;

/**
 * 用户诗词收藏记录
 *
 * @property UserPoemImageLikeRecordService $service
 */
class UserPoemImageLikeRecordController extends AdminController
{
    protected string $serviceName = UserPoemImageLikeRecordService::class;

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
                amis()->TableColumn('poem_image_id', '诗词图片')
                    ->type('link')
                    ->href('#/poem_images/${poem_image.id}')
                    ->body('${poem_image.id}'),
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
            'poem_images' => PoemImage::query()
                ->get()
                ->map(function (PoemImage $item) {
                    return [
                        'label' => $item->id,
                        'value' => $item->id,
                    ];
                }),
        ])->body([
            amis()->SelectControl('user_id', '用户')
                ->source('${users}'),
            amis()->SelectControl('poem_image_id', '诗词图片')
                ->source('${poem_images}'),
        ]);
    }

    public function detail()
    {
        return $this->baseDetail()->body([
            amis()->TextControl('id', 'ID')->static(),
            amis()->TextControl('user_id', '用户 ID')->static(),
            amis()->TextControl('poem_image_id', '诗词图片 ID')->static(),
            amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
            amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
        ]);
    }
}
