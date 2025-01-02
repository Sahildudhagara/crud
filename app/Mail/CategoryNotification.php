<?php

namespace App\Mail;

use App\Models\Category;
use Illuminate\Mail\Mailable;

class CategoryNotification extends Mailable
{
    public $category;
    public $action;

    public function __construct(Category $category, $action)
    {
        $this->category = $category;
        $this->action = $action;
    }

    public function build()
    {
        return $this->view('emails.category_notification')
                    ->with([
                        'categoryName' => $this->category->name,
                        'categoryDescription' => $this->category->description,
                        'action' => $this->action,
                    ]);
    }
}
