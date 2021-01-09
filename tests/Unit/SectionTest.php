<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Section;
use App\Models\Project;

class SectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_section_belongs_to_a_project() {
        $section = Section::factory()->create();

        $this->assertInstanceOf(Project::class, $section->project);
    }

    /** @test **/
    public function it_has_a_path() {
        $section = Section::factory()->create();

        $this->assertEquals('/projects/' . $section->project->id . '/sections/' . $section->id, $section->path());
    }
}
