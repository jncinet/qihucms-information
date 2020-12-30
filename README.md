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
+ 好友验证 `information/friend-policies`

## 使用

### 路由能参数说明

#### 我的关系

```php
route('api.invite.my')
请求：GET
地址：/invite/my
返回值：
{
    'user_id' => 会员ID号,
    'parent' => {师父信息},
    'grandfather' => {师祖信息},
    'son_count' => 徒弟数,
    'grandson_count' => 徒孙数,
}

```

#### 我的徒弟列表

```php
route('api.invite.td')
请求：GET
地址：/invite/td
参数：
int $limit （选填）显示条数
返回值：
{
    data: [
        {
            user_id: 用户ID号
            user：{会员信息}
        },
        ...
    ],
    links:{},
    meta:{}
}
```

#### 我的徒孙列表

```php
route('api.invite.ts')
请求：GET
地址：/invite/ts
参数：
int $limit （选填）显示条数
返回值：
{
    data: [
        {
            user_id: 用户ID号
            user：{会员信息}
        },
        ...
    ],
    links:{},
    meta:{}
}
```

### 事件调用

```php
// 创建推荐关系
Qihucms\Invite\Events\Invited
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
