<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class PersonTest extends TestCase
{
    public function testPerson()
    {
        $person = new Person();
        $person->first_name = 'Ahmad';
        $person->last_name = 'Fauzi';
        $person->save();

        assertEquals('AHMAD Fauzi', $person->fullName);

        $person->fullName = 'Joko Moro';
        $person->save();

        assertEquals('JOKO', $person->first_name);
        assertEquals('Moro', $person->last_name);
    }

    public function testAttributeCasting()
    {
        $person = new Person();
        $person->first_name = 'Ahmad';
        $person->last_name = 'Fauzi';
        $person->save();

        assertNotNull($person->created_at);
        assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
    }

    public function testCustomCasts()
    {
        $person = new Person();
        $person->first_name = 'Ahmad';
        $person->last_name = 'Fauzi';
        $person->address = new Address('Jalan Belum Jadi', 'Jakarta', 'Indonesia', '11111');
        $person->save();

        assertNotNull($person->created_at);
        assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
        assertEquals('Jalan Belum Jadi', $person->address->street);
        assertEquals('Jakarta', $person->address->city);
        assertEquals('Indonesia', $person->address->country);
        assertEquals('11111', $person->address->postalCode);
    }
}
