# 币种及价格 API

 

## 币种及价格

 
```
POST /coin/price
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
             "name": "菠萝",
             "symbol": "Pelo",
             "decimals": 18,
             "withdraw_enable": 1,
             "fee": 10,
             "price": 0.2
         },
         {
             "id": 2,
             "name": "菠菜",
             "symbol": "Boc",
             "decimals": 18,
             "withdraw_enable": 0,
             "fee": 2,
             "price": 0.2
         }
     ]
 }
```
字段说明：

| 字段  | 类型 | 描述 |
|----|:----:|:----:|
| name |  string | 名称  |
| symbol |  string | 代币符号  |
| decimals |  int | 小数点后位数  | 
| withdraw_enable | boolean | 是否可提现 |
| fee | int | 提现手续费 |
  