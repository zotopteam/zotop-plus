<?php

namespace Zotop\Command\Traits;


use Closure;
use Illuminate\Database\Eloquent\Builder;

trait ChunkBuilderWithProgress
{
    /**
     * 分块处理单个模型并显示进度
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Closure $callback 单条数据回调
     * @param int $chunkSize
     * @return int 处理数据总条数
     * @author Chen Lei
     * @date 2020-12-24
     */
    protected function chunkBuilderModelWithProgress(Builder $builder, Closure $callback, int $chunkSize = 500)
    {
        $count = $builder->count();

        if (empty($count)) {
            $this->info('No data to process！');
            return 0;
        }

        $this->info('Starting……');

        // 进度条
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        // 分块处理
        $builder->chunk($chunkSize, function ($items) use ($bar, $callback) {

            $items->each(function ($item) use ($bar, $callback) {
                // 处理单条数据
                $callback($item);

                // 进度条增加
                $bar->advance();
            });
        });

        // 进度条完成
        $bar->finish();

        $this->info(PHP_EOL . 'Completed!');

        return $count;
    }

    /**
     * 分块处理模型集合并显示进度
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Closure $callback 分块的模型集合
     * @param int $chunkSize
     * @return int 处理数据总条数
     * @author Chen Lei
     * @date 2020-12-24
     */
    protected function chunkBuilderModelCollectionWithProgress(Builder $builder, Closure $callback, int $chunkSize = 500)
    {
        $count = $builder->count();

        if (empty($count)) {
            $this->info('No data to process！');
            return 0;
        }

        $this->info('Starting……');

        // 进度条
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        // 分块处理
        $builder->chunk($chunkSize, function ($items) use ($bar, $callback) {

            $callback($items);

            $bar->advance($items->count());

        });

        // 进度条完成
        $bar->finish();

        $this->info(PHP_EOL . 'Completed!');

        return $count;
    }
}
