<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Models\FormSetting;

class FormLayout extends Component
{
    public FormSetting $form_setting;

    public function __construct(FormSetting $form_setting)
    {
        $this->form_setting = $form_setting;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.form.app');
    }
}
