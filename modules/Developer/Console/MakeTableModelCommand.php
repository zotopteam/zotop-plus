<?php

namespace Modules\Developer\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Modules\Developer\Support\Table;
use Module;

class MakeTableModelCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'module:make-table-model
                            {module : The name of module.}
                            {table : The name of table.}
                            {--f|force : Force create the model file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model for the specified module from a table.';

    /**
     * The module
     * 
     * @var object
     */
    private $module;

    /**
     * The table
     * @var object
     */
    private $table;

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
     * @return mixed
     */
    public function handle()
    {
        $this->module = Module::find($this->argument('module'));

        if (! $this->module) {
            return $this->error('The module: '.$this->argument('module').' does not exists');
        }        

        $this->table  = Table::find($this->argument('table'));

        if (! $this->table->exists()) {
            return $this->error('The table: '.$this->argument('table').' ('.$this->table->name(true).') does not exists');
        }

        // 模型文件
        $file = $this->getModelFilePath();

        // 检查文件是否已经存在
        if ($this->laravel['files']->exists($file) && !$this->option('force')) {
            return $this->error('The model file: '.$file.' already exists.');
        }

        $content = $this->getModelContent();

        if ($content) {
            $this->laravel['files']->put($file, $content);
            return $this->line('Created:'.$file);
        }
        
        return $this->error('Create Failed!');
    }

    /**
     * 获取模型名称
     * @return string
     */
    public function getModelName()
    {
        // 模型名称为表名的驼峰格式
        return studly_case($this->table->name());
    }

    /**
     * 获取模型文件路径
     * @return string
     */
    public function getModelFilePath()
    {
        return $this->module->getExtraPath('Models').'/'.$this->getModelName().'.php';
    }

    /**
     * 获取命名空间
     * @return string
     */
    private function getNamespace()
    {
        $namespace = $this->laravel['modules']->config('namespace');
        $namespace .= '\\' . $this->module->getStudlyName();
        $namespace .= '\\' . 'Models';

        return $namespace;
    }

    /**
     * 获取可填充字段
     * @return string
     */
    public function getFillable($columns)
    {
        $fillable = $columns->filter(function($item) {
            return !$item['increments'] && !in_array($item['name'], ['created_at', 'updated_at', 'deleted_at']);
        })->pluck('name')->all();

        if ($fillable) {
            return "['".implode("','", $fillable)."']";
        }

        return '[]';
    }

    /**
     * 获取不可填充字段
     * @return string
     */
    public function getGuarded($columns)
    {
        $guarded = $columns->filter(function($item) {
            return $item['increments'];
        })->pluck('name')->all();

        if ($guarded) {
            return "['".implode("','", $guarded)."']";
        }

        return '[]';        
    }

    /**
     * 获取模型文件路径
     * @return string
     */
    public function getModelContent()
    {
        $template = __DIR__.'/stubs/model.stub';

        $columns = $this->table->columns(true);

        $data = [
            'CLASS'     => $this->getModelName(),
            'NAMESPACE' => $this->getNamespace(),
            'TABLE'     => $this->table->name(),
            'FILLABLE'  => $this->getFillable($columns),
            'GUARDES'   => $this->getGuarded($columns)
        ];

        return $this->compile($template, $data);
    }

    /**
     * Compile the template using
     * the given data
     *
     * @param $template 模板路径
     * @param $data 替换数组
     * @return mixed
     */
    public function compile($template, $data)
    {
        $template = $this->laravel['files']->get($template);

        foreach($data as $key => $value) {
            $template = preg_replace("/\\$$key\\$/i", $value, $template);
        }

        return $template;
    }           
}
