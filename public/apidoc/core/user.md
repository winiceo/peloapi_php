## 用户登录

```
POST /account/login
```
-- 无须验证身份(免token)


```json
{
	"login":"admin",
	"password":"admin"
}
```
### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| login | 字符串 | **必须**，mobile||username |
| password | 字符串 | **必须** |

- login字段可以为用户名也可以为手机号


#### 响应

```
Status: 200
```
```json
{
    "status": 200,
    "message": "",
    "data": {
        "user": {
            "id": 10,
            "username": "张1三",
            "email": null,
            "mobile": "158100427221",
            "last_name": null,
            "first_name": null,
            "permissions": {
                "user.delete": 0
            },
            "last_login": {
                "date": "2018-05-29 02:24:32.254667",
                "timezone_type": 3,
                "timezone": "UTC"
            },
            "created_at": "2018-05-29 01:59:47",
            "updated_at": "2018-05-29 02:24:32"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9wZi5sb2NhbCIsImlhdCI6MTUyNzU2MDY3MiwibmJmIjoxNTI3NTYwNjcyLCJleHAiOjE1NTkwOTY2NzIsImRhdGEiOnsiaWQiOjEwLCJjcmVkZW50aWFscyI6eyJ1c2VybmFtZSI6Ilx1NWYyMDFcdTRlMDkiLCJwYXNzd29yZCI6IjEyMzQ1NiJ9fX0.uX1tVXkajf9ZbmBWO9tsEfMRWVVCiCIXlD0VWbNn-aA"
    }
}
```


## 用户注册

```
POST /account/register
```
-- 无须验证身份(免token)

```json

{
	"username":"admin",
	"password":"123456", 
	"mobile":"18028721507"
 
}
```
### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| username | 字符串 | **必须**，用户名 |
| password | 字符串 | **必须**，密码。 |
| captcha | 字符串或数字 | **必须**，用户收到的验证码。 |

#### 响应

```
Status: 200
```
```json
{
    "status": 200,
    "message": "",
    "data": {
        "user": {
            "username": "admin",
            "mobile": "18028721507",
            "permissions": {
                "user.delete": 0
            },
            "updated_at": "2018-05-29 02:36:53",
            "created_at": "2018-05-29 02:36:53",
            "id": 11
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9wZi5sb2NhbCIsImlhdCI6MTUyNzU2MTQxMywibmJmIjoxNTI3NTYxNDEzLCJleHAiOjE1NTkwOTc0MTMsImRhdGEiOnsiaWQiOjExLCJjcmVkZW50aWFscyI6InNhOTMyODM0M25kNzc0Nzg4ZGhkaGQtODg0NzQ3ampqOTkzODdqamhkLTA5In19.QbScLK39_0hsV8WC6lp7j8vpbsiJOZ3NwtdiNzGwJO0"
    }
}
```



## 忘记密码


POST /account/forget

-- 无须验证身份(免token)

### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| mobile | 字符串 | **必须** mobile |
| password | 字符串 | **必须**，密码。 |
| captcha | 字符串或数字 | **必须**，用户收到的验证码。 |




```
Status: 200
```
```json
{
    "status": 200,
    "message": "ok",
    "data":null
}
```


## 更新密码


POST /account/changePassword

### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----| 
| old_password | 字符串 | **必须**，旧密码。|
| password | 字符串 | **必须**，新密码。 |
 
 
 ```json
  
 {
 	"old_password":"56os.com1",
 	"password":"123456"  
 }
 ```
 
```
Status: 200
```
```json
{
    "status": 200,
    "message": "ok",
    "data":null
}
```


