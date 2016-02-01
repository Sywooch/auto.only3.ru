<?php
namespace common\behaviors;

use dosamigos\transliterator\TransliteratorHelper;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class Slug extends Behavior
{
    public $in_attribute = 'name';
    public $out_attribute = 'slug_url';
    public $translit = true;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'getSlug'
        ];
    }

    public function getSlug( $event )
    {
        if ( empty( $this->owner->{$this->out_attribute} ) ) {
            $this->owner->{$this->out_attribute} = $this->generateSlug( $this->owner->{$this->in_attribute} );
        } else {
            $this->owner->{$this->out_attribute} = $this->generateSlug($this->owner->{$this->out_attribute} );
        }
    }

    private function generateSlug( $slug )
    {
        $slug = $this->slugify( $slug );

        if ( $this->checkUniqueSlug( $slug ) ) {
            return $slug;
        } else {
            for ( $suffix = 2; !$this->checkUniqueSlug( $new_slug = $slug . '_' . $suffix ); $suffix++ ) {}
            return $new_slug;
        }
    }

    private function slugify( $slug )
    {
        return $this->slug( $this->Transliterator($slug), '_', true );
    }

    static function slug( $string, $replacement = '_', $lowercase = true )
    {
        $string = preg_replace( '/[^\p{L}\p{Nd}]+/u', $replacement, $string );
        $string = trim( $string, $replacement );
        return $lowercase ? mb_strtolower($string, 'UTF-8') : $string;
    }

    private function checkUniqueSlug($slug)
    {
        $pk = $this->owner->primaryKey();
        $pk = $pk[0];

        $condition = $this->out_attribute . ' = :out_attribute';
        $params = [ ':out_attribute' => $slug ];
        if ( !$this->owner->isNewRecord ) {
            $condition .= ' and ' . $pk . ' != :pk';
            $params[':pk'] = $this->owner->{$pk};
        }

        return !$this->owner->find()
            ->where( $condition, $params )
            ->one();
    }

    static function CirilToLatin($slug)
    {

        $sLat = Array("e", "y", "u", "o", "p", "a", "k", "x", "c", "E", "T", "O", "P", "A", "H", "K", "X", "C", "B", "M");
        $sRus = Array("е", "у", "и", "о", "р", "а", "к", "х", "с", "Е", "Т", "О", "Р", "А", "Н", "К", "Х", "С", "В", "М");

        $res = str_replace($sRus, $sLat, $slug);
    }

    static function Transliterator($slug)
    {
        $arr_rus = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ь', 'ы', 'ъ', 'э', 'ю', 'я', ' ');
        $arr_eng = array('a', 'b', 'v', 'g', 'd', 'e', 'jo', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', '', 'y', '', 'e', 'ju', 'ja', '_');

        $res = str_replace($arr_rus, $arr_eng, mb_strtolower($slug, 'UTF-8'));
        return $res;

    }
}