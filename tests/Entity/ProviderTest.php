<?php

namespace App\Tests\Entity;

use App\Entity\Provider;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    public function testSetName(): void
    {
        $provider = new Provider();
        $provider->setName('New Product Name');
        $this->assertEquals('New Product Name', $provider->getName());
    }
}
