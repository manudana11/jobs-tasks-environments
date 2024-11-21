<?php

namespace Tests\Unit;

use App\Models\Employer;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Tests\TestCase as TestsTestCase;

class JobTest extends TestsTestCase
{
    use RefreshDatabase;

    /** @test */
     public function it_belongs_to_an_employer()
    {
        $employer = Employer::factory()->create();
        $job = Job::factory()->create([
            'employer_id' => $employer->id,
        ]);

        $this->assertTrue($job->employer->is($employer));
    }

    /** @test */
    public function it_can_have_tags()
    {
        $job = Job::factory()->create();
        $job->tag('Frontend');

        $this->assertCount(1, $job->tags);
    }
}
