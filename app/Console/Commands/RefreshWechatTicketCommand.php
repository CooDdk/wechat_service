<?php

namespace App\Console\Commands;

use App\Help\WeChat;
use App\Models\Activity;
use App\Models\WechatApp;
use Illuminate\Console\Command;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/22
 * Time: 14:06
 */
class RefreshWechatTicketCommand extends Command
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'refresh_wechat_ticket {--wid=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '刷新微信ticket';

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
        //这里编写需要执行的动作
        WechatApp::query()->where('status', 'release')->get()->each(function ($item) {
            $app_id = $item->app_id;
            WeChat::getTicket($app_id, true);
        });

    }
}