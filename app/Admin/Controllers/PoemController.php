<?php

namespace App\Admin\Controllers;

use App\Enums\DisplayStatuses;
use App\Enums\PoemSourceTypes;
use App\Models\User;
use App\Services\PoemService;
use Slowlyo\OwlAdmin\Controllers\AdminController;

/**
 * 诗词
 *
 * @property PoemService $service
 */
class PoemController extends AdminController
{
    protected string $serviceName = PoemService::class;

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
                amis()->TableColumn('title', '标题'),
                amis()->TableColumn('author', '作者'),
                amis()->TableColumn('dynasty', '朝代'),
                amis()->TableColumn('content', '内容')->type('multiline-text'),
                amis()->TableColumn('source_type_label', '来源类型'),
                amis()->TableColumn('display_status_label', '展示状态'),
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
            'sourceTypes' => PoemSourceTypes::options(),
            'displayStatuses' => DisplayStatuses::options(),
        ])->body([
            amis()->SelectControl('user_id', '用户')
                ->source('${users}'),
            amis()->TextControl('title', '标题'),
            amis()->TextControl('author', '作者'),
            amis()->TextControl('dynasty', '朝代'),
            amis()->TextareaControl('content', '内容'),
            amis()->SelectControl('source_type', '来源类型')
                ->source('${sourceTypes}'),
            amis()->SelectControl('display_status', '展示状态')
                ->source('${displayStatuses}'),
        ]);
    }

    public function detail()
    {
        return $this->baseDetail()->body([
            amis()->TextControl('id', 'ID')->static(),
            amis()->TextControl('user_id', '用户 ID')->static(),
            amis()->TextControl('title', '标题')->static(),
            amis()->TextControl('author', '作者')->static(),
            amis()->TextControl('dynasty', '朝代')->static(),
            amis()->TextControl('content', '内容')->static(),
            amis()->TextControl('source_type', '来源类型')->static(),
            amis()->TextControl('display_status', '展示状态')->static(),
            amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
            amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
        ]);
    }
}
