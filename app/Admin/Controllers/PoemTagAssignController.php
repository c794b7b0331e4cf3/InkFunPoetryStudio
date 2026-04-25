<?php

namespace App\Admin\Controllers;

use App\Models\Poem;
use App\Models\PoemTag;
use App\Services\PoemTagAssignService;
use Slowlyo\OwlAdmin\Controllers\AdminController;

/**
 * 诗词标签分配
 *
 * @property PoemTagAssignService $service
 */
class PoemTagAssignController extends AdminController
{
    protected string $serviceName = PoemTagAssignService::class;

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
                amis()->TableColumn('poem_tag_id', '诗词标签')
                    ->type('link')
                    ->href('#/poem_tags/${tag.id}')
                    ->body('[${tag.id}] ${tag.name}'),
                amis()->TableColumn('poem_id', '诗词')
                    ->type('link')
                    ->href('#/poems/${poem.id}')
                    ->body('[${poem.id}] ${poem.title}'),
                amis()->TableColumn('created_at', admin_trans('admin.created_at'))->type('datetime')->sortable(),
                amis()->TableColumn('updated_at', admin_trans('admin.updated_at'))->type('datetime')->sortable(),
                $this->rowActions('dialog'),
            ]);

        return $this->baseList($crud);
    }

    public function form($isEdit = false)
    {
        return $this->baseForm()->data([
            'poems' => Poem::query()
                ->get()
                ->map(function (Poem $item) {
                    return [
                        'label' => '['.$item->id.'] '.$item->title,
                        'value' => $item->id,
                    ];
                }),
            'poemTags' => PoemTag::query()
                ->get()
                ->map(function (PoemTag $item) {
                    return [
                        'label' => '['.$item->id.'] '.$item->name,
                        'value' => $item->id,
                    ];
                }),
        ])->body([
            amis()->SelectControl('poem_tag_id', '诗词标签')
                ->source('${poemTags}'),
            amis()->SelectControl('poem_id', '诗词')
                ->source('${poems}'),
        ]);
    }

    public function detail()
    {
        return $this->baseDetail()->body([
            amis()->TextControl('id', 'ID')->static(),
            amis()->TextControl('poem_tag_id', '诗词标签 ID')->static(),
            amis()->TextControl('poem_id', '诗词 ID')->static(),
            amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
            amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
        ]);
    }
}
