<?php

namespace frontend\models;

class Only3Model extends \yii\db\ActiveRecord{

    const MARKER_NAME_BEGIN = '{%';
    const MARKER_NAME_END   = '%}';

    protected $markerName = 'Only3Model';

    public function getModelMarkerList(){
        $arr = $this->attributeLabels();
        unset($arr['id']);

        return $arr;
    }

    public function getMarkersList(){

        $arr = $this->getModelMarkerList();

        $resAr = [];
        foreach($arr as $name => $label){
            $resAr[$this->markerName.'.'.$name] = $label;
        }

        return $resAr;
    }

    public function getMarkersListValue(){

        $markers = $this->getMarkersList();

        $resArray = [];
        foreach($markers as $name => $label){
            $valueName = str_replace($this->markerName.'.', '', $name);
            $resArray[$name] = $this->$valueName;
        }

        return $resArray;
    }

}
