# Meibuyu Library
美不语微服务官方接口库

### 1、如何使用
在使用的项目下的composer.json 加入以下内容
``` 
"repositories": {
    "meibuyu/micro": {
        "type": "path",
        "url": "path/to/micro",//本库的具体地址，随意找个地方git clone下来
        "options": {
            "symlink": true
        }
    },
}
```
然后在使用的项目下执行
``` 
composer require meibuyu/micro @dev
```

---

### 2、鉴权注解使用方法
> 使用时必须接入用户服务  
> 权限名会拼接env文件中的APP_NAME属性,请注意唯一性   
> 所有权限必须存在于用户服务的权限表中,若不存在,请联系管理员添加权限
##### 1、@AutoPerm
在控制器头部添加@AutoPerm注解,为该控制器下所有的方法添加鉴权功能,生成的权限名为`蛇形命名空间_蛇形控制名_蛇形方法名`
```
/**
 * @AutoPerm()
 */
class UserInfoController {}
```
参数: 
> 1. prefix, 前缀(字符串),默认为蛇形命名空间_蛇形控制名(user_info)
> 2. exclude, 要排除的方法名(字符串数组),默认为空
```
/**
 * @AutoPerm(prefix="user", exclude={"getUser"})
 */
class UserInfoController {}
```

##### 2、@Perm
在控制器中的方法头部添加@Perm注解,为当前方法添加鉴权功能,生成权限名为`蛇形命名空间_蛇形控制名_蛇形方法名`
```
/**
 * @Perm()
 */
function getUser {}
```
参数: 
> name, 前缀(字符串),默认为小写控制器名拼接方法名(user),如果填写指定的名称,会覆盖@AutoPerm的prefix和exclude
```
/**
 * @Perm("get_user")
 */
function getUser {}
```

