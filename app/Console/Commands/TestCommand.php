<?php
namespace App\Console\Commands;
use App\Models\Activity;
use App\Models\WechatApp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/22
 * Time: 14:06
 */
class TestCommand extends Command
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'testd';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '本地代码测试';

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

        $a = Cache::put("hello",1,111);
        dd($a);
        //这里编写需要执行的动作
        WechatApp::query()->limit(2)->get()->each(function ($item){
            dd(222);
        });

    }
}