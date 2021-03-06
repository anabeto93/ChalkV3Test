<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('bsText', 'components.form.text', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsTextArea', 'components.form.textarea', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsNumber', 'components.form.number', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsCheckbox', 'components.form.checkbox', ['name', 'value' => null, 'checked' =>
            null, 'attributes' => []]);
        Form::component('bsSelect', 'components.form.select', ['name', 'value' => null, 'default' => null, 'attributes'
        => []]);
        Form::component('bsFile', 'components.form.file', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsSubmit', 'components.form.submit', ['name', 'attributes' => []]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
