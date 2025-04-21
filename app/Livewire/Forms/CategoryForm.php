<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryForm extends Form
{
    protected $rules = [
        'name' => ['required', 'string', 'max:50'],
        'type' => ['required', 'string', 'in:income,expense'],
    ];

    public ?string $name;
    public ?string $type = 'expense';

    #[Locked]
    public ?Category $category;


    public function setCategory(Category $category)
    {
        $this->category = $category;

        $this->name = $category->name;
        $this->type = $category->type;
    }


    public function save()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'type' => $this->type,
            'user_id' => Auth::user()->id
        ]);

        $this->reset();
    }
}
