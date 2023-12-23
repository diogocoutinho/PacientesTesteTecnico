<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientsTest extends TestCase
{
    use RefreshDatabase;

    public function testPatientsIndex()
    {
        $response = $this->get('/patients');

        $response->assertStatus(200);
    }

    public function testGetPatients()
    {
        $response = $this->get('/patients/all');

        $response->assertStatus(200);
    }

    public function testSearchCep()
    {
        $response = $this->get("/patients/search/{$cep}");

        $response->assertStatus(200);
    }

    public function testUploadPatientImage()
    {
        $response = $this->post('/patients/uploadPatientImage');

        $response->assertStatus(200);
    }

    public function testExportPatients()
    {
        $response = $this->post('/patients/export/csv');

        $response->assertStatus(200);
    }
}
