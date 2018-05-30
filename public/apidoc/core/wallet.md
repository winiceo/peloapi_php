# 钱包 API

 

## 用户资产

- 因为价格波动比较大，所以资产估值需要客户端定期抓取价格接口，进行计算

 
```
POST /wallet/assets
```

#### 响应

```
Status: 200 OK
```

```json5
{
    "status": 200,
    "message": "",
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "coin_id": 1,
            "balance": 100,
            "created_at": "2017-12-13 16:27:15",
            "updated_at": "2017-12-15 08:40:37",
            "name": "菠萝",
            "symbol": "Pelo",
            "decimals": 18,
            "withdraw_enable": 1,
            "fee": 10
        },
        {
            "id": 2,
            "user_id": 1,
            "coin_id": 2,
            "balance": 200,
            "created_at": "2017-12-13 16:27:15",
            "updated_at": "2017-12-13 16:27:15",
            "name": "菠菜",
            "symbol": "Boc",
            "decimals": 18,
            "withdraw_enable": 0,
            "fee": 2
        }
    ]
}
```
字段说明：

| 字段  | 类型 | 描述 |
|----|:----:|:----:|
| coin_type |  string | 币种  |
| 
| balance | int | 余额 |
| withdraw_enabled | boolean | 是否可提现 |
 
 

## 提现申请

```
POST /wallet/withdraw
```

### Input 

| 字段 | 必须 | 类型 | 描述 |
|----|:----:|:----:|:----:|
| coin_id | 是 | int | 提币币种 | 
| address | 是 | string | 提币地址 | 
| amount | 是 | float | 提币数量 | 
| captcha | 是 | string | 手机验证码 | 
 

```json5
{
	"coin_id":1,
	"address":"324234adfasdf",
	"amount":"333",
	"captcha":"a2b456"
}
```
  
##### Headers

```
Status: 200 Created
```

##### Body

```json5
{
    "status": 200,
    "message": "",
    "data": {
        "coin_id": 1,
        "address": "324234adfasdf",
        "amount": "333",
        "user_id": 1,
        "order_code": 1527585028,
        "type": 1,
        "updated_at": "2018-05-29 09:10:28",
        "created_at": "2018-05-29 09:10:28",
        "id": 11,
        "balance": 98335
    }
}
```
### 输出字段说明 

| 字段  | 类型 | 描述 |
|----|:----:|:----:|
| coin_id | int | 提币币种 | 
| address  | string | 提币地址 | 
| amount  | float | 提币数量 | 
| user_id  | string | 用户id | 
| type  | int | 类型 1为收入，2为提现 | 
 

 

## 提现历史

```
POST /wallet/withdraw/history
```
 
| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| page | Number | 页码|
 
```json
{ 
    "page":3
}

```
#### Headers

```
Status: 200 OK
```


### 输出

| 字段 | 必须 | 类型 | 描述 |
|----|:----:|:----:|:----:|
| id | 是 | int |  id | 
| coin_type | 是 | string | 提币币种 | 
| address | 是 | string | 提币地址 | 
| amount | 是 | number | 提币数量 | 
| txid | 是 | string | txid | 
| status | 是 | int | 状态：0 - 处理中，1 - 已审批，2 - 已完成 | 
| type | 是 | int | 1- 收入 2- 提现支出 | 
| created_at | 是 | int |  创建时间 |
 
#### Body

```json5
{
    "status": 200,
    "message": "",
    "data": {
        "data": [
            {
                "id": 34,
                "user_id": 1,
                "coin_id": 1,
                "amount": 333,
                "balance": 87013,
                "order_code": "1527587819",
                "address": "324234adfasdf",
                "finish_time": null,
                "status": 0,
                "created_at": "2018-05-29 09:56:59",
                "updated_at": "2018-05-29 09:56:59",
                "txid": null,
                "type": 2
            },
            ……
        ],
        "page": {
            "current_page": 1,
            "total_page": 2,
            "total": 33
        }
    }
}
```
