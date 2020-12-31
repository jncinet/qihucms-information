<h1 align="center">会员好友、消息</h1>

## 安装
```shell
$ composer require jncinet/qihucms-information
```

## 开始
### 数据迁移
```shell
$ php artisan migrate
```

### 发布资源
```shell
$ php artisan vendor:publish --provider="Qihucms\Information\InformationServiceProvider"
```

### 定时删除一个月前的消息
```shell
$ php artisan information:checkMessage
```

## 后台菜单
+ 会员消息 `information/messages`
+ 会员好友 `information/friends`
+ 好友验证问题 `information/friend-policies`

## 使用

### 路由参数说明

#### 好友列表

```php
请求：GET
地址：information/friends?status=1,好友状态&limit=15每页条数&page=页码
返回值：
{
    data: [
        {
            'id' => 1,
            'user_id' => 2,
            'friend' => {好友会员资料},
            'friend_name' => "好友1", // 好友备注名
            'status' => 1, // 好友状态
            'information_messages_count' => 0, // 消息总数
            'information_messages_first' => "你好", // 最新消息
            'created_at' => "1秒前",
        },
        ...
    ],
    links:{},
    meta:{}
}
```

#### 添加好友

> 如果会员设置了加好友规则，则须通过验证才能成为好友。

```php
请求：POST
地址：information/friends
参数：
{
    friend_id: 要添加会员ID,
    password: 好友验证密码,
    answer: 好友验证问题答案
}
返回值：
{
            'id' => 1,
            'user_id' => 2,
            'friend' => {好友会员资料},
            'friend_name' => "好友1", // 好友备注名
            'status' => 1, // 好友状态
            'information_messages_count' => 0, // 消息总数
            'information_messages_first' => "你好", // 最新消息
            'created_at' => "1秒前",
}

```

#### 审核好友申请

```php
请求：GET
地址：information/friends/{id=好友ID}?status={有此参数无论何值均为不通过，无此参数即为通过}
返回值：
{
    status: SUCCESS,
    result: {
        friend_id: 好友ID,
        status: 1, // ['待确认', '已通过', '已拒绝', '已删除']
    }
}
```

#### 修改好友备注名称

```php
请求：PATCH|PUT
地址：information/friends/{id=好友ID}
参数：{
    friend_name: '备注名'
}
返回值：
{
    status: SUCCESS,
    result: {
        friend_id: 好友ID,
        friend_name: '备注名'
    }
}
```

#### 删除好友

```php
请求：DELETE
地址：information/friends/{id=好友ID}
返回值：
{
    status: SUCCESS,
    result: {
        friend_id: 好友ID,
    }
}
```

#### 有消息的好友列表

```php
请求：GET
地址：information/messages/{id=好友关系ID}
返回值：
{
    data: [
        {
            'id' => 1,
            'user_id' => 2,
            'information_friend' => {好友会员资料},
            'friend_name' => "好友1", // 好友备注名
            'status' => 1, // 好友状态
            'information_messages_count' => 0, // 消息总数
            'information_messages_first' => "你好", // 最新消息
            'created_at' => "1秒前",
        },
        ...
    ],
    links:{},
    meta:{}
}
```

#### 好友消息列表

```php
请求：GET
地址：information/messages?limit=15每页条数&page=页码
返回值：
{
    data: [
        {
            'id' => 1,
            'user_id' => 2,
            'information_friend' => {
                'id' => 1,
                'user_id' => 2,
                'friend' => {好友会员资料},
                'friend_name' => "好友1", // 好友备注名
                'status' => 1, // 好友状态
                'information_messages_count' => 0, // 消息总数
                'information_messages_first' => "你好", // 最新消息
                'created_at' => "1秒前",
            },
            'type' => 0, // ['文本', '图片', '视频', '音频', '分享连接']
            'message' => "消息内容",
            'status' => 1, // 消息状态
            'created_at' => "1秒前",
        },
        ...
    ],
    links:{},
    meta:{}
}
```

#### 发布消息

```
请求：POST
地址：information/messages
参数：{
    'information_friend_id' => '好友关系ID',
    'type' => 0, // '消息类型'
    'message' => '消息内容',
}
返回：{
    'id' => 1,
    'user_id' => 2,
    'information_friend' => {
        'id' => 1,
        'user_id' => 2,
        'friend' => {好友会员资料},
        'friend_name' => "好友1", // 好友备注名
        'status' => 1, // 好友状态
        'information_messages_count' => 0, // 消息总数
        'information_messages_first' => "你好", // 最新消息
        'created_at' => "1秒前",
    },
    'type' => 0, // ['文本', '图片', '视频', '音频', '分享连接']
    'message' => "消息内容",
    'status' => 1, // 消息状态
    'created_at' => "1秒前",
}
```

#### 更新消息阅读状态

```
请求：PATCH|PUT
地址：information/messages/{id=好友关系ID}
返回：{
    "id": 1 // 好友关系ID
}
```

#### 清空消息

+ 请求方式：DELETE
+ 请求地址：information/messages/{id=好友关系ID}?all=true
+ 返回值
```
{
    "id": 1 // 好友关系ID
    "all": true
}
```

#### 批量删除消息

+ 请求方式：DELETE
+ 请求地址：information/messages/{id=好友关系ID}?ids=1,2,3
+ 返回值
```
{
    "id": 1 // 好友关系ID
    "ids": [1,2,3]
}
```

#### 删除消息一条消息

+ 请求方式：DELETE
+ 请求地址：information/messages/{id=消息ID}
+ 返回值
```
{
    "id": 1 // 消息ID
}
```

### 事件调用

```php
// 添加好友
Qihucms\Information\Events\AddFriend;
// 发送消息
Qihucms\Information\Events\SendMessage;
```

# 数据库

### 好友策略表：information_friend_policies

| Field             | Type      | Length    | AllowNull | Default   | Comment       |
| :----             | :----     | :----     | :----     | :----     | :----         |
| id                | bigint    |           |           |           | 会员ID         |
| user_id           | bigint    |           |           |           | 会员ID         |
| question          | varchar   | 66        | Y         | NULL      | 问题           |
| answer            | varchar   | 255       | Y         | NULL      | 答案           |
| password          | varchar   | 255       | Y         | NULL      | 密码           |
| created_at        | timestamp |           | Y         | NULL      | 创建时间        |
| updated_at        | timestamp |           | Y         | NULL      | 更新时间        |

### 好友关系表：information_friends

| Field             | Type      | Length    | AllowNull | Default   | Comment       |
| :----             | :----     | :----     | :----     | :----     | :----         |
| id                | bigint    |           |           |           |               |
| user_id           | bigint    |           |           |           | 会员ID         |
| friend_id         | bigint    |           |           |           | 好友ID         |
| friend_name       | varchar   | 66        | Y         | NULL      | 好友备注名称    |
| status            | tinyint   |           |           | 0         | 状态           |
| created_at        | timestamp |           | Y         | NULL      | 创建时间        |
| updated_at        | timestamp |           | Y         | NULL      | 更新时间        |


### 好友消息表：information_messages

| Field             | Type      | Length    | AllowNull | Default   | Comment       |
| :----             | :----     | :----     | :----     | :----     | :----         |
| id                | bigint    |           |           |           |               |
| user_id           | bigint    |           |           |           | 会员ID         |
| information_friend_id | bigint |          |           |           | 好友关系       |
| message           | text      |           |           |           | 好友备注名称    |
| type              | tinyint   |           |           | 0         | 信息类型       |
| status            | tinyint   |           |           | 0         | 接收状态        |
| created_at        | timestamp |           | Y         | NULL      | 创建时间        |
| updated_at        | timestamp |           | Y         | NULL      | 更新时间        |
