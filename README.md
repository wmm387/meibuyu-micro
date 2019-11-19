# Meibuyu Library
美不语微服务官方接口库

### 1、如何使用
在使用的项目下的composer.json 加入以下内容
``` "repositories": {
    "meibuyu/micro": {
        "type": "path",
        "url": "d:/workspace/biubiubiu",#本库的具体地址，随意找个地方git clone下来
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
