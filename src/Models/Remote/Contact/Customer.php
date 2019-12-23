<?php

namespace Creativecurtis\Laramyob\Models\Remote\Contact;

use Creativecurtis\Laramyob\Models\BaseModel as Model;

class Customer extends Model
{
    public $endpoint = '/Contact/Customer';
    public $paginated = true;

}