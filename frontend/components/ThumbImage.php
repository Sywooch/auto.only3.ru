<?
namespace frontend\components;

use Yii;
use yii\base\Object;
use yii\imagine\Image;

class ThumbImage extends Object
{
    static function getThumbImage($img, $w, $h)
    {
        if (!empty($img)) {

            $thumbUrl = '/images/_thumbs/' . $w . '/' . $h . $img;
            $thumbPath = Yii::getAlias('@webroot' . $thumbUrl);

            if (is_file($thumbPath)) {
                return $thumbUrl;
            }

            $dir = dirname($thumbPath);

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            try {
                Image::thumbnail('@webroot' . $img, $w, $h)
                    ->save($thumbPath, ['quality' => 80]);
            } catch (\Exception $e) {

            }

            return $thumbUrl;
        }
    }
}

?>