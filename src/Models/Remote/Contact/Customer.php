<?php

namespace Creativecurtis\Laramyob\Models\Remote\Contact;

use Creativecurtis\Laramyob\Models\BaseModel as Model;

class Customer extends Model
{
    public $endpoint = '/Contact/Customer';
    public $paginated = true;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }
    public function whereEmail($value) 
    {
        $this->endpoint = $this->endpoint."/?\$filter=Addresses/any(x: x/Email eq '".$value."')";
        return $this;
    }
}