<?php

namespace App\DataFixtures\Data;

class SnowboardImageData
{
    public static array $data = [
        'https://cdn.pixabay.com/photo/2013/05/26/18/48/winter-113799_1280.jpg',
        'https://cdn.pixabay.com/photo/2013/12/12/21/28/snowboard-227541_1280.jpg',
        'https://cdn.pixabay.com/photo/2013/12/12/21/28/snowboard-227540_1280.jpg',
        'https://cdn.pixabay.com/photo/2014/12/02/14/12/snowboarding-554048_1280.jpg',
        'https://cdn.pixabay.com/photo/2015/03/26/10/01/man-690779_1280.jpg',
        'https://cdn.pixabay.com/photo/2015/02/01/05/51/bungee-jumping-619139_1280.jpg',
        'https://cdn.pixabay.com/photo/2016/03/27/18/40/big-air-1283525_1280.jpg',
        'https://cdn.pixabay.com/photo/2016/01/26/00/26/canazei-1161799_1280.jpg',
        'https://cdn.pixabay.com/photo/2017/02/01/17/28/snowboarding-2030851_1280.jpg',
        'https://cdn.pixabay.com/photo/2018/03/10/15/22/snow-3214256_1280.jpg',
        'https://cdn.pixabay.com/photo/2018/09/11/09/25/snowboard-3668972_1280.jpg',
        'https://cdn.pixabay.com/photo/2019/02/26/00/35/snowboard-4020919_1280.jpg',
        'https://cdn.pixabay.com/photo/2020/01/29/17/09/snow-4803054_1280.jpg',
        'https://cdn.pixabay.com/photo/2020/02/25/11/46/snowboarding-4878696_1280.jpg',
    ];

    /**
     * Return a random element from $data property.
     */
    public static function getRandomData(): string
    {
        return self::$data[array_rand(self::$data)];
    }
}
