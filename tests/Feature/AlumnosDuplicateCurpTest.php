<?php

namespace Tests\Feature;

use App\Models\alumno;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlumnosDuplicateCurpTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_blocks_registration_when_curp_already_exists_and_shows_the_existing_folio(): void
    {
        Storage::fake('public');

        $alumnoExistente = alumno::create([
            'folio' => '00001|2026|H|A',
            'nombre' => 'Juan',
            'paterno' => 'Pérez',
            'materno' => 'López',
            'curp' => 'ABC1234567890',
            'estatus' => 1,
            'deshabilitado' => 0,
        ]);

        $response = $this->post(route('alumnos.registroalumnosria.store'), [
            'telefono' => '1234567890',
            'correo' => 'test@example.com',
            'curp' => 'abc1234567890',
            'image1' => UploadedFile::fake()->image('rostro.jpg'),
            'image2' => UploadedFile::fake()->image('inefrente.jpg'),
            'image3' => UploadedFile::fake()->image('inereverso.jpg'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('swal', function (array $swal) use ($alumnoExistente): bool {
            return $swal['icon'] === 'error'
                && str_contains($swal['text'], 'Ya existe un alumno')
                && str_contains($swal['text'], $alumnoExistente->folio);
        });

        $this->assertSame(1, alumno::count());
    }
}
