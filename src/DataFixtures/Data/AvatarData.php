<?php

namespace App\DataFixtures\Data;

class AvatarImageData
{
    public static array $data = [
        'https://cdn.pixabay.com/photo/2018/05/04/16/50/cat-3374422_1280.jpg',
        'https://cdn.pixabay.com/photo/2015/07/09/19/32/dog-838281_1280.jpg',
        'https://cdn.pixabay.com/photo/2015/07/09/19/32/dog-838281_1280.jpg',
        'https://cdn.pixabay.com/photo/2018/05/07/10/48/husky-3380548_1280.jpg',
        'https://cdn.pixabay.com/photo/2014/05/26/14/01/beauty-354565_1280.jpg',
        'https://cdn.pixabay.com/photo/2019/11/05/16/03/man-4603859_1280.jpg',
        'https://cdn.pixabay.com/photo/2018/05/07/10/49/husky-3380550_1280.jpg',
        'https://cdn.pixabay.com/photo/2016/12/27/22/54/lizard-1935081_1280.jpg',
        'https://cdn.pixabay.com/photo/2019/05/30/14/19/cat-4239970_1280.jpg',
        'https://cdn.pixabay.com/photo/2019/07/03/10/16/pug-4314106_1280.jpg',
        'https://cdn.pixabay.com/photo/2015/11/13/12/08/person-1041904_1280.jpg',
        'https://cdn.pixabay.com/photo/2013/09/07/08/29/cat-179842_1280.jpg',
        'https://cdn.pixabay.com/photo/2015/07/28/19/21/person-864804_1280.jpg',
        'https://cdn.pixabay.com/photo/2018/04/13/19/10/fantasy-3317298_1280.jpg',
        'https://cdn.pixabay.com/photo/2014/03/14/20/07/painting-287403_1280.jpg',
    ];

    /**
     * Return a random element from $data property.
     */
    public static function getRandomData(): string
    {
        return self::$data[array_rand(self::$data)];
    }
}
