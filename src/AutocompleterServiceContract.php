<?php

namespace Seblhaire\Autocompleter;

interface AutocompleterServiceContract {

    public function init($inputid, $labeltext, $url, $options = []);
}
