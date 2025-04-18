<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class IncomeForm extends Form
{
    public string $name = '';
    public string $amount = '';

    #[Validate(['required', 'string'])]
    public string $type = '';

    public function submit()
    {
        $this->validate();
        //save to database
    }
}