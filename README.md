# [Laradmin]

📦 一个 PHP 后端基础功能接口。

[![Test Status](https://github.com/w7corp/easywechat/workflows/Test/badge.svg)](https://github.com/zhx12345/laradmin/actions/new)
[![Lint Status](https://github.com/w7corp/easywechat/workflows/Lint/badge.svg)](https://github.com/zhx12345/laradmin)
[![Total Downloads](https://poser.pugx.org/w7corp/easywechat/downloads)](https://github.com/zhx12345/laradmin)
[![License](https://poser.pugx.org/w7corp/easywechat/license)](https://github.com/zhx12345/laradmin)

## 环境需求

- PHP >= 8.0.2
- [Composer](https://getcomposer.org/) >= 2.0

## 安装

```bash
composer require zhxlan/laradmin
```

## 使用示例

基本使用:


```php
'providers' => ServiceProvider::defaultProviders()->merge([
    ...
    \Zhxlan\Laradmin\LaradminServiceProvider::class
])->toArray(),
```

## License

MIT
