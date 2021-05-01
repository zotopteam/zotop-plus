<?php


namespace Zotop\Hook\Commands;


use Illuminate\Console\Command;
use ReflectionFunction;
use Throwable;


class ListFilterCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hook:list-filter
                            {hook : The hook name of the filter.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays the list of specific filter hook';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $hook = $this->argument('hook');

        $this->showHookList($hook);
    }

    /**
     * 显示filter钩子列表
     *
     * @param string $hook
     * @author Chen Lei
     * @date 2021-03-19
     */
    private function showHookList(string $hook)
    {
        $number = 1;

        // 获取
        $listeners = $this->laravel['hook.filter']->listeners($hook)->transform(function ($listener) use (&$number) {

            $callback = $listener['callback'];

            if (is_string($callback) && !strpos($callback, '@')) {
                $callback = $callback . '@handle';
            }

            if (is_callable($callback)) {
                try {
                    $reflect = new ReflectionFunction($callback);
                    $callback = 'Closure (';
                    $callback .= $reflect->getClosureScopeClass()->getName();
                    $callback .= ' ' . $reflect->getStartLine() . ' to ' . $reflect->getEndLine();
                    $callback .= ')';
                } catch (Throwable $th) {
                    $callback = 'Closure';
                }
            }

            $item = [
                'number'   => $number,
                'callback' => $callback,
                'priority' => $listener['priority'],
            ];

            $number++;

            return $item;
        });

        $this->table(['Number', 'Callback', 'Priority'], $listeners->toArray());
    }
}
