<?php
namespace Tests\Contract;

trait FormValidationContractTest
{
    protected function attribute_is_required($routeName, $model, $attribute)
    {
        $this->withExceptionHandling();

        $this->signIn();

        $instance = make($model, [$attribute => null]);

        $response = $this->post(route($routeName, $instance->toArray()));

        $response->assertRedirect();
        $response->assertSessionHasErrors($attribute);
    }
}
