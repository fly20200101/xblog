<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class AdminLogModel extends BaseModel
{
    const LEVEL_EMERGENCY = 8;
    const LEVEL_ALERT = 7;
    const LEVEL_CRITICAL = 6;
    const LEVEL_ERROR = 5;
    const LEVEL_WARNING = 4;
    const LEVEL_NOTICE = 3;
    const LEVEL_INFO = 2;
    const LEVEL_DEBUG = 1;


    /** @var int 公告 */
    const RECORD_MODULE_ANNOUNCEMENT = 3;
    /** @var int 系统 */
    const RECORD_MODULE_SYSTEM = 4;

    /** @var int 权限 */

    /** @var int 创建 */
    const OPERATE_TYPE_CREATE = 1;
    /** @var int 查询 */
    const OPERATE_TYPE_READ = 2;
    /** @var int 更新 */
    const OPERATE_TYPE_UPDATE = 3;
    /** @var int 删除 */
    const OPERATE_TYPE_DELETE = 4;
    /** @var int 登陆 */
    const OPERATE_TYPE_LOGIN = 5;
    /** @var int 注销 */
    const OPERATE_TYPE_LOGOUT = 6;
    /** @var int 打开 */
    const OPERATE_TYPE_OPEN = 7;
    /** @var int 关闭 */
    const OPERATE_TYPE_CLOSE = 8;
    /**
     * 模型的连接名称
     *
     * @var string
     */
    protected $connection = 'mongodb';

    protected $collection = 'adminlog';
    protected $primaryKey = 'id';    //设置id

    public function writeLog($data, $before = [], $after = [], $except = []){
        $before = Arr::except($before, $except);
        $after = Arr::except($after, $except);
        $time = time();
        array_walk($data, function (&$v, $k) {
            if (is_array($v) || is_object($v)) {
                $v = var_export($v, true);
            }
        });
        $set = [
            "id" => (int)$this->getInsertId(),//流水号，自增id
            "op_type_id" => (int)Arr::get($data, 'op_type_id', 0),       //操作类型 ，增加，删除， 修改，登录
            "op_user_id" => (int)Arr::get($data, 'op_user_id', 0), //操作者id
            "op_user_name" => (string)Arr::get($data, 'op_user_name', ''),//操作者账号
            "be_object_id" => (int)Arr::get($data, 'be_object_id', 0),//操作对象id
            "be_object_name" => (string)Arr::get($data, 'be_object_name', ''), //被操作对象名字或者简称
            "level" => (int)Arr::get($data, 'level', 0), //日志级别
            "title" => (string)Arr::get($data, 'title', ''),//日志标题
            "desc" => (string)Arr::get($data, 'desc', ''), //日志描述
            "create_time" => (int)$time,//日志创建时间
            "op_time" => (int)Arr::get($data, 'op_time', $time),//事件执行时间
            "op_ip" => (int)Arr::get($data, 'op_ip', ip2long(request()->ip())),//日志操作者ip,
            "op_url" => (string)Arr::get($data, 'op_url', ''), //访问url
        ];
        $desc = "<font color='green'>{$set['op_user_name']}</font>,{$set['title']}-><font color='red'>{$set['be_object_name']}</font><br>";
        if ($input = Arr::get($data, 'input')) {
            $desc .= "->{$input}<br>";
        }
        $desc .= PayLogAdminHelper::formatDesc($before, $after);
        $set['desc'] = $desc . $set['desc'];
        return $this->insert($set);
    }

}
