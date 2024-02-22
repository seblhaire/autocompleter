<?php

namespace Seblhaire\Autocompleter;

class AutocompleterService implements AutocompleterServiceContract {

    public function init($inputid, $labeltext, $url, $options = []) {
        return new Autocompleter($inputid, $labeltext, $url, $options);
    }
}
