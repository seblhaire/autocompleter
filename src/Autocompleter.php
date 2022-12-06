<?php
namespace Seblhaire\Autocompleter;

class Autocompleter{
  protected $inputid;
  protected $label;
  protected $url;
  protected $options = [];

  public function __construct($inputid, $label = '', $url, $options = []){
    $this->inputid = $inputid;
    $this->label = $label;
    $this->url = $url;
    $this->options = array_merge(array_replace(config('autocompleter'), $options), [
        'csrf' => csrf_token()
    ]);
  }

  public function printDiv(){
    $str = '<div id="' . $this->inputid . '_maindiv" class="' . $this->options['divclass'] . '">' . PHP_EOL;
    $str .= $this->printInsideDiv() .  $this->printHelp() . '</div>' . PHP_EOL;
    return $str;
  }

  public function printInsideDiv(){
    $str  = '<div class="' . $this->options['divinsideclass'] . '">' . PHP_EOL;
    $str .= $this->printLabel() . $this->printInput() . $this->printResultDiv() . '</div>' . PHP_EOL;
    return $str;
  }

  public function printHelp(){
    $str = '';
    if ($this->options['helptext'] != ''){
      $str .= '<p id="' . $this->inputid . '_help" class="' . $this->options['helpclass'] .'">' .
        $this->translateOrPrint($this->options['helptext']) . '</p>' . PHP_EOL;
    }
    return $str;
  }

  public function printResultDiv(){
    $str  = '<div id="' . $this->inputid . '_res_div"';
    $str  .= ' class="' . $this->options['resultdivclass'] . '">' . PHP_EOL;
    $str  .= '<ul id="' . $this->inputid . '_res_list" class="' . $this->options['resultlistclass'] . '"></ul>' . PHP_EOL;
    $str .= '</div>' . PHP_EOL;
    return $str;
  }

  public function printLabel(){
    if ($this->label != ''){
      return '<label class="' . $this->options['labelclass'] . '" for="' . $this->inputid .'">' . $this->label . '</label>' . PHP_EOL;
    }
    return '';
  }

  public function printInput(){
    $help = '';
    if ($this->options['helptext'] != ''){
       $help = ' aria-describedby="' . $this->inputid . '_help"';
    }
    $str = '<input id="' . $this->inputid .'" name="' . $this->inputid .'" type="text" autocomplete="off" class="' .
        $this->options['inputclass'] . '"' . $help . '/>' . PHP_EOL;
    return $str;
  }

  public function printJsInit(){
    $str = '   jQuery(\'#' . $this->inputid  . '\').sebautocompleter(\'' . $this->url . '\', {'. PHP_EOL;
    $str .= '     resultlistclassitem: \'' . $this->options['resultlistclassitem'] . '\',' . PHP_EOL;
    $str .= '     activeitem: \'' . $this->options['activeitem'] . '\',' . PHP_EOL;
    $str .= '     encoding: \'' . $this->options['encoding'] . '\',' . PHP_EOL;
    $str .= '     maxresults: ' . $this->options['maxresults'] .  ',' . PHP_EOL;
    $str .= '     minsearchstr: ' .  $this->options['minsearchstr'] .  ',' . PHP_EOL;
    $str .= '     id_field:  \'' . $this->options['id_field'] . '\',' . PHP_EOL;
    $str .= '     list_field:  \'' . $this->options['list_field'] . '\',' . PHP_EOL;
    $str .= '     id_included: ' .  ($this->options['id_included'] ? 'true,' : 'false,') . PHP_EOL;
    if (!is_null($this->options['callback'])){
        $str .= '     callback: ' . $this->options['callback'] . ',' . PHP_EOL;
    }
    $str .= '     csrf: \'' . $this->options['csrf'] . '\'' . PHP_EOL;
    $str .= '  });' . PHP_EOL;
    return $str;
  }

	public function output(){
    $str = $this->printDiv();
    $str .= '<script type="text/javascript">' . PHP_EOL;
    $str .= ' jQuery(document).ready(function() {'. PHP_EOL;
    $str .= $this->printJsInit();
    $str .= ' });' . PHP_EOL;
    $str .= '</script>' . PHP_EOL;
    return $str;
  }

  public function __toString(){
      return $this->output();
  }

  /**
   * returns a string or passes translation key to translation function
   *
   * @param string $key
   *            normal string or translation key surrounded by #
   * @return string text to display
   */
  private function translateOrPrint($key)
  {
      if (preg_match('/^\#(.+)\#$/', $key, $matches)) {
          return addslashes(__($matches[1]));
      }
      return $key;
  }
}
