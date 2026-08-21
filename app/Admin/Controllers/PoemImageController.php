<?php

namespace App\Admin\Controllers;

use App\Models\File;
use App\Models\Poem;
use App\Models\User;
use App\Services\PoemImageService;
use Slowlyo\OwlAdmin\Controllers\AdminController;

/**
 * 诗词图片
 *
 * @property PoemImageService $service
 */
class PoemImageController extends AdminController
{
    protected string $serviceName = PoemImageService::class;

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
                amis()->TableColumn('poem', '诗词')
                    ->type('link')
                    ->href('#/poems/${poem.id}')
                    ->body('[${poem.id}] ${poem.title}'),
                amis()->TableColumn('file', '文件')
                    ->type('image')
                    ->src('${file.download_url}')
                    ->href('#/files/${file.id}'),
                amis()->TableColumn('prompt', '提示词'),
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
            'poems' => Poem::query()
                ->get()
                ->map(function (Poem $item) {
                    return [
                        'label' => '['.$item->id.'] '.$item->title,
                        'value' => $item->id,
                    ];
                }),
            'files' => File::query()
                ->get()
                ->map(function (File $item) {
                    return [
                        'label' => '['.$item->id.'] '.$item->disk.' -> '.$item->path,
                        'value' => $item->id,
                    ];
                }),
        ])->body([
            amis()->SelectControl('user_id', '用户')
                ->source('${users}'),
            amis()->SelectControl('poem_id', '诗词')
                ->source('${poems}'),
            amis()->SelectControl('file_id', '文件')
                ->source('${files}'),
            amis()->TextControl('prompt', '提示词'),
        ]);
    }

    public function detail()
    {
        return $this->baseDetail()->body([
            amis()->TextControl('id', 'ID')->static(),
            amis()->TextControl('user_id', '用户 ID')->static(),
            amis()->TextControl('poem_id', '诗词 ID')->static(),
            amis()->TextControl('file_id', '文件 ID')->static(),
            amis()->TextControl('prompt', '提示词')->static(),
            amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
            amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
        ]);
    }
}
