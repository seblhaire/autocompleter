<?php
namespace Seblhaire\Autocompleter;

use Illuminate\Support\Facades\Facade;

class AutocompleterHelper extends Facade{
  /**
   * Builds a facade
   *
   * @return [type] [description]
   */
  protected static function getFacadeAccessor()
  {
      return 'AutocompleterService';
  }
}
