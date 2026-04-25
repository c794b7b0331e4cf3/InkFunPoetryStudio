<?php

namespace App\Admin\Controllers;

use App\Enums\FileTypes;
use App\Models\User;
use App\Services\FileService;
use Slowlyo\OwlAdmin\Controllers\AdminController;

/**
 * 文件
 *
 * @property FileService $service
 */
class FileController extends AdminController
{
    protected string $serviceName = FileService::class;

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
                amis()->TableColumn('user', '用户')
                    ->type('link')
                    ->href('#/users/${user.id}')
                    ->body('[${user.id}] ${user.name}'),
                amis()->TableColumn('disk', '磁盘'),
                amis()->TableColumn('path', '路径'),
                amis()->TableColumn('original_filename', '源文件名'),
                amis()->TableColumn('mimetype', 'mime 类型'),
                amis()->TableColumn('size', '大小')->sortable(),
                amis()->TableColumn('ip', '上传 IP'),
                amis()->TableColumn('type_label', '类型'),
                amis()->TableColumn('metadata', '元数据')->type('json'),
                amis()->TableColumn('hash', '哈希'),
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
            'types' => FileTypes::options(),
        ])->body([
            amis()->SelectControl('user_id', '用户')
                ->source('${users}'),
            amis()->TextControl('disk', '磁盘'),
            amis()->TextControl('path', '路径'),
            amis()->TextControl('original_filename', '源文件名'),
            amis()->TextControl('mimetype', 'mime 类型'),
            amis()->NumberControl('size', '大小')->step(1),
            amis()->TextControl('ip', '上传 IP'),
            amis()->SelectControl('type', '类型')
                ->source('${types}'),
            amis()->TextareaControl('metadata', '元数据'),
            amis()->TextControl('hash', '哈希'),
        ]);
    }

    public function detail()
    {
        return $this->baseDetail()->body([
            amis()->TextControl('id', 'ID')->static(),
            amis()->TextControl('user_id', '用户 ID')->static(),
            amis()->TextControl('disk', '磁盘')->static(),
            amis()->TextControl('path', '路径')->static(),
            amis()->TextControl('original_filename', '源文件名')->static(),
            amis()->TextControl('mimetype', 'mime 类型')->static(),
            amis()->TextControl('size', '大小')->static(),
            amis()->TextControl('ip', '上传 IP')->static(),
            amis()->TextControl('type', '类型')->static(),
            amis()->TextControl('metadata', '元数据')->static(),
            amis()->TextControl('hash', '哈希')->static(),
            amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
            amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
        ]);
    }
}
