<?php

namespace spec\ArgumentResolver;

use ArgumentResolver\ArgumentResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArgumentResolverFactorySpec extends ObjectBehavior
{
    function it_creates_arguments_resolver()
    {
        $this->create()->shouldReturnAnInstanceOf(ArgumentResolver::class);
    }
}
