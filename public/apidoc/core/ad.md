# 矿机相关

## 获取全部矿机
```
POST /miner
```

#### 响应

```
Status: 200 OK

```
```json
{
    "status":200,
    "message":"",
    "data":{  
         "assets":{
            "yesterday_balance":2.5,
            "lrc_cny":10.6,
            "total_balance":2343
         },
         "data":[{
            "id":2,
            "miner_id": "miner312",
            "miner_alias":"我的小蜜蜂", 
            "status": "INACTIVE",
            "shares_1d": 7.52,
            "shares_1d_unit": "T",
            "earn_coin_1d": 8.99
	    }]
    ]
    }
    
}

 
```
#### 输出字段说明
 
| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| miner_alias | string | 矿机别名 |
| status | string | 矿机状态 |
| shares_1d | number | 矿机1天算力 |
| shares_1d_unit | string | 矿机1天算力单位 |
| earn_coin_1d | number | 1天收益 默认为Lrc |
| yesterday_balance | number | 昨日收益 默认为Lrc |
| lrc_cny | number | 昨日收益人民币估值 |
| total_balance | number | 累计总收益 |



## 获取单个矿机收益明细

```
POST /miner/{id}?page=1
```

#### 请求参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| id | int | 矿机id |
| page | int | 分页，默认为1 |


#### 响应

```
Status: 200 OK
```
```json
{
    "status": 200,
    "message": "",
    "data": {
        "assets": {
            "total_balance": 2343
        },
        "data": [
            {
                "id": 37,
                "miner_id": "miner21",
                "status": 0,
                "shares_1d": 2,
                "shares_1d_unit": "T",
                "earn_coin_1d": 2,
                "user_id": 39,
                "datetime": null
            },
            {
                "id": 38,
                "miner_id": "miner21",
                "status": 0,
                "shares_1d": 2,
                "shares_1d_unit": "T",
                "earn_coin_1d": 2,
                "user_id": 39,
                "datetime": null
            },
            {
                "id": 39,
                "miner_id": "miner312",
                "status": 0,
                "shares_1d": 2,
                "shares_1d_unit": "T",
                "earn_coin_1d": 2,
                "user_id": 39,
                "datetime": null
            }
        ],
        "page": {
            "current_page": 1,
            "total_page": 1,
            "total": 3
        }
    }
}
```
 #### 输出字段说明
  
 | 名称 | 类型 | 描述 |
 |:----:|:----:|----|
   
 | total_balance | number | 累计总收益 |
 
 
