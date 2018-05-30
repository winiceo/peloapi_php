# Pelo api使用说明



测试环境域名(apiurl): https://pelo.71an.com/api

所有接口需在headers部份加上
key             value
Content-Type   application/json 

> api请求路径 如：Post {{apiurl}}/order/detail/{id}
替换路径中的变量:id为请求参数


> api返回类型

```json5
{
    "status": 200,
    "message": "",
    "data": {
    }
}
```
status:为状态码 成功为200,非200的为异常类型
message:为异常信息
data:为json    

>需要验证个人身份的请求

header部分加上
Authorization:
值为 Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9wZi5sb2NhbCIsImlhdCI6MTUxMzEyNjkyOCwibmJmIjoxNTEzMTI2OTI4LCJleHAiOjE1NDQ2NjI5MjgsImRhdGEiOnsiaWQiOjEsInVzZXJuYW1lIjpudWxsLCJwYXNzd29yZCI6IiQyeSQxMCRUNXkxeE8xaU1vOFhDXC9pZzNXcUM5T1wvbVg1b2llNEhnQXlCeVhJcFMzZm1yMFVmd2M3RXF5Iiwicm9sZXMiOm51bGx9fQ.AdwkVbdFSH302e6kTz1zMKDoc3oM3sKmTS8QNVrx2So

>除特殊标注不需要身份验证的接口，其它接口默认加上Authorization

 -- 
 
