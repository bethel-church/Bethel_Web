<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
*/
class CommonHelper extends Helper
{
    function getItemPic($imageName = null)
    {
        if ($imageName != '') {
            if(file_exists(WWW_ROOT . "img/items/thumb/" . $imageName)) {
                return "items/thumb/" . $imageName;
            } else {
            return "items/thumb/" . "no_image.png";
            }
        } else {
            return "items/thumb/" . "no_image.png";
        }
    }
    function getItemPicThumb($imageName = null)
    {
        if ($imageName != '') {
            if(file_exists(WWW_ROOT . "img/items/thumb_small/" . $imageName)) {
                return "items/thumb_small/" . $imageName;
            } else {
            return "items/thumb_small/" . "no_image.png";
            }
        } else {
            return "items/thumb_small/" . "no_image.png";
        }
    }
    function getUserPicThumb($imageName = null,$thumb_folder =null)
    {
        if ($imageName != '') {
            if(file_exists(WWW_ROOT . "img/users/".$thumb_folder."/" . $imageName)) {
                return "users/".$thumb_folder."/" . $imageName;
            } else {
            return "users/".$thumb_folder."/" . "no_image.png";
            }
        } else {
            return "users/".$thumb_folder."/" ."no_image.png";
        }
    }
    function getCompanyLogoThumb($imageName = null,$thumb_folder =null)
    {
        if ($imageName != '') {
            if(file_exists(WWW_ROOT . "img/logo/".$thumb_folder."/" . $imageName)) {
                return "logo/".$thumb_folder."/" . $imageName;
            } else {
            return "logo/".$thumb_folder."/" . "no_pic.png";
            }
        } else {
            return "logo/".$thumb_folder."/" ."no_pic.png";
        }
    }
    function getShowroomPicThumb($imageName = null,$thumb_folder =null)
    {
        if ($imageName != '') {
            if(file_exists(WWW_ROOT . "img/showrooms/".$thumb_folder."/" . $imageName)) {
                return "showrooms/".$thumb_folder."/" . $imageName;
            } else {
            return "showrooms/".$thumb_folder."/" . "no_image.png";
            }
        } else {
            return "showrooms/".$thumb_folder."/" ."no_image.png";
        }
    }
    function getSize($sizeId , $sizeArr){
        foreach($sizeArr as $key =>$val){
            if($key == $sizeId){
                return $key;
            }
        }        
    }
    
    function stringConvertSpaceToUscore($getName = null)
    {
        $getName = strtolower(str_replace(' ','_',$getName));
        return $getName;
    }
    /*
     show humanTiming
     measure time difference between current time and offer time in FORMAT ()
     //10Days: 14 hours 37 minutes 21 seconds
    */
   //function getCategoryCount($parentCategoryID){
   //     App::import("Model", "Category");  
   //     $model = new Category();  
   //     $count  = $model->find("count",array('conditions'=>array('Category.parent_id'=>$parentCategoryID)));
   //     return $count;
   //}
   //function getCategory($parentCategoryID){
   //     App::import("Model", "Category");  
   //     $model = new Category(); 
   //     $count  = $model->find("list",array('conditions'=>array('Category.parent_id'=>$parentCategoryID),'fields'=>array('id','category')));
   //    
   //     return $count;
   //}
   //function getCategoryName($categoryId){
   //     App::import("Model", "Category");  
   //     $model = new Category();
   //     $cat = $model->find('first',array('conditions'=>array('Category.id'=>$categoryId),'fields'=>array('category')));
   //     return $cat['Category']['category'];
   //}
function humanTiming ($postingDate)
{
$time = strtotime($postingDate);
    $time = time() - $time; // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':' ago');
    }

}

    
}