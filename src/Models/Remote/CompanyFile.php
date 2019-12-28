<?php

namespace Creativecurtis\Laramyob\Models\Remote;

use Creativecurtis\Laramyob\Models\BaseModel as Model;
use Creativecurtis\Laramyob\Models\Configuration\MyobConfig;

class CompanyFile extends Model
{
    //Base URL for company file is default so we override
    public $endpoint = '';

    public function __construct(MyobConfig $myobConfiguration) {
        parent::__construct($myobConfiguration);
        $this->baseurl = 'https://api.myob.com/accountright';
    }
}