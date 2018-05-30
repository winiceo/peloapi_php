# 钱包 API

 

## 用户钱包资产

 
```
POST /income/yesterday
```

#### 响应

```
Status: 200 OK
```

```json5
{
    "status":200,
    "message":"",
    "data":
        [
            {
               
                "coin_type": "lrc",
                "balance": "123",
                "withdraw_enabled":true
                
            },
            {
                
                "coin_type": "nas",
                "balance": "44",
                "withdraw_enabled":true
            }
        ]
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
| coin_type | 是 | String | 提币币种 | 
| address | 是 | string | 提币地址 | 
| amount | 是 | float | 提币数量 | 
| captcha | 是 | string | 提币邮箱验证码 | 
 

```json5
{
	"coin_type":1,
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
    "status":200,
    "message":""
    "data":null
}
```

 

## 提现历史

```
POST /wallet/withdraw/history
```


| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| page | Number | 页码|
 

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
| created_at | 是 | int |  创建时间 |
 
#### Body

```json5
{
    "status":200,
    "message":"",
    "data": {
        "data":[
                     {
                         "id": 23, 
                         "coin_type": "lrc",
                         "address": "0xf4897b18a8626e58ce49985122d520c4462d4cecdc0259d7",
                         "amount": 333.3,
                         "txid": "0xf4897b18a8626e58ce49985122d520c44697b3d52ebeeac8e62d4cecdc0259d7",
                         "finish_time": null,
                         "status": 0,
                         "created_at": 1522119459,
                        
                     },
                     ……
         ],
         "page":{
             "current_page": 1, 
             "total_page": 4
         
         }
    
    }
   
}
```
