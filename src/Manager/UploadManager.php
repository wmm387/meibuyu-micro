<?php
/**
 * Created by PhpStorm.
 * User: Zero
 * Date: 2020/3/30
 * Time: 9:56
 */

declare(strict_types=1);

namespace Meibuyu\Micro\Manager;

use Hyperf\HttpMessage\Upload\UploadedFile;
use Meibuyu\Micro\Exceptions\HttpResponseException;

class UploadManager
{

    public static $pathPrefix = 'public/upload/';
    public static $options = [
        'path' => 'default', // 默认保存路径
        'maxSize' => 100000000, // 文件大小
        'temp' => false, // 是否为临时文件
        'mime' => ['jpeg', 'png', 'gif', 'jpg', 'svg', 'txt', 'pdf', 'xlsx', 'xls', 'doc', 'docx', 'rar', 'zip'], // 允许上传的文件类型
    ];

    /**
     * 图片上传方法
     * @param $image
     * @param array $options
     * @return string
     * @throws HttpResponseException
     */
    public static function uploadImage($image, $options = [])
    {
        $imgOptions = [
            'path' => 'images',
            'mime' => ['jpeg', 'png', 'gif', 'jpg', 'svg']
        ];
        $options = array_merge($imgOptions, $options);
        return self::uploadFile($image, $options);
    }

    /**
     * 表格上传方法
     * @param $excel
     * @param array $options
     * @return string
     * @throws HttpResponseException
     */
    public static function uploadExcel($excel, $options = [])
    {
        $excelOptions = [
            'path' => 'excel',
            'mime' => ['xlsx', 'xls']
        ];
        $options = array_merge($excelOptions, $options);
        return self::uploadFile($excel, $options);
    }

    /**
     * 文件上传方法
     * @param UploadedFile $file 上传的文件
     * @param array $options 配置参数
     * @return string
     * @throws HttpResponseException
     */
    public static function uploadFile($file, $options = [])
    {
        $options = self::parseOptions($options);
        if ($file->isValid()) {
            $extension = $file->getExtension();
            // 通过扩展名判断类型
            if (!in_array(strtolower($extension), $options['mime'])) {
                throw new HttpResponseException('文件类型不支持,目前只支持' . implode(',', $options['mime']));
            }
            // 判断文件大小
            if ($file->getSize() > $options['maxSize']) {
                throw new HttpResponseException('文件超出系统规定的大小,最大不能超过' . $options['maxSize']);
            }
            // 文件重命名,由当前日期时间 + 唯一ID + 扩展名
            $fileName = date('YmdHis') . uniqid() . '.' . $extension;
            $savePath = self::parsePath($options) . $fileName;
            $file->moveTo($savePath);
            if ($file->isMoved()) {
                return str_replace('public/', '', $savePath);
            } else {
                throw new HttpResponseException('文件保存失败');
            }
        } else {
            throw new HttpResponseException('文件无效');
        }
    }

    /**
     * 生成头像
     * @return string|string[]
     */
    public static function createAvatar()
    {
        $img = imagecreatetruecolor(180, 180);
        $bgColor = imagecolorallocate($img, 240, 240, 240);
        imagefill($img, 0, 0, $bgColor);
        $color = imagecolorallocate($img, rand(90, 230), rand(90, 230), rand(90, 230));

        for ($i = 0; $i < 90; $i++) {
            for ($y = 0; $y < 180; $y++) {
                $ad = rand(10, 50); //随机
                if ($ad % 3 == 0) {
                    for ($xx = $i; $xx < $i + 15; $xx++) {
                        for ($yy = $y; $yy < $y + 30; $yy++) {
                            imagesetpixel($img, $xx, $yy, $color);
                        }
                    }
                    $is = ((90 - $i) + 90) - 15; //计算偏移
                    for ($xx = $is; $xx < $is + 15; $xx++) {
                        for ($yy = $y; $yy < $y + 30; $yy++) {
                            imagesetpixel($img, $xx, $yy, $color);
                        }
                    }
                }
                $y += 14;
            }
            $i += 14;
        }

        $path = self::$pathPrefix . 'avatar/default/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $fileName = $path . date('YmdHis') . uniqid() . '.png';
        imagepng($img, $fileName);
        imagedestroy($img);//释放内存
        return str_replace('public/', '', $fileName);
    }

    /**
     * 处理保存路径
     * @param $options
     * @return string
     */
    public static function parsePath($options)
    {
        if (isset($options['temp']) && $options['temp'] && !isset($options['path'])) {
            // 如果是临时文件,且没有指定保存路径,修改保存路径为临时路径
            $options['path'] = 'temp';
        }
        $path = self::$pathPrefix . $options['path'] . '/' . date('Y-m-d');
        if (!is_dir($path)) {
            // 判断路径是否存在,不存在,则创建
            mkdir($path, 0777, true);
        }
        return $path . '/';
    }

    /**
     * 处理配置参数
     * @param array $options
     * @return array
     */
    public static function parseOptions($options = [])
    {
        if ($options == []) {
            return self::$options;
        } else {
            return array_merge(self::$options, $options);
        }
    }

}