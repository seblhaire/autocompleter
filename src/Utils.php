<?php
namespace Seblhaire\Autocompleter;

use Stringy\Stringy as S;
// use Barryvdh\Debugbar\Facade as DebugBar;

class Utils{
  static public function highlite($string, $search, $classes){
    $lowersrch = S::create($search)->toLowerCase();
    $lowerstr = S::create($string)->toLowerCase();
    $len = $lowerstr->length() + 1;
    $srchlen = $lowersrch->length();
    $idx = [];
    $pos = $lowerstr->indexOfLast($lowersrch);
    while ($pos !== false){
      $idx[] = $pos;
      if ($pos == 0){
        $pos = false;
      }else{
        $offset = - $len + $pos;
        $pos = $lowerstr->indexOfLast($lowersrch, $offset);
      }
    }
    $str = S::create($string);
    foreach($idx as $i){
      $str = $str->insert("</span>", $i + $srchlen)->insert('<span class="' . $classes . '">', $i);
    }
    return $str->__toString();
  }
}
