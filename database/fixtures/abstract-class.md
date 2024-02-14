In my Laravel app, I wrote an abstract class that has a fair bit of stand-alone behaviour. I wanted to test this directly instead of via the implementations.

PHP has anonymous classes, so instantiating it isn’t very tricky to do in a unit test. But the constructor also has about a dozen dependencies its asking Laravel’s service container for, and I wanted \[almost\] all those to be injected for me.

Problem is, I don’t think PHP lets you create an anonymous class and assign it to a variable, without instantiating it at the same time.

Instead, I found a clever trick: I can implement my own constructor and use `App::call()` on `parent::__construct`.

```php
new class extends WorkflowFromJsonRepository {
    public function __construct()
    {
        App::call(parent::__construct(...), [
            'someParamToStub' => $stubbedThing,
        ]);
    }
};
```

If you need to pass stubs in, you can pick and choose by passing them in the second `call()` parameter. Ya’know, standard Laravel stuff at that point.

I am not sure if this trick would works on anything below PHP 8.1. The syntax with the three dots — `parent::__construct(...)` — is the [new first-class callable feature](https://www.php.net/manual/en/functions.first_class_callable_syntax.php). I’m not sure if you can get a handle on that using the older array-based syntax?
