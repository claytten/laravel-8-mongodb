<?php

namespace Tests\Unit;

use App\Models\Kendaraan;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;

class KendaraanApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Sanctum::actingAs($this->user, ['*']);
    }

    // test creating mobil
    public function testCreatingMobil()
    {
        $response = $this->postJson('/api/kendaraan/store', [
            'tahun_keluaran' => 2010,
            'warna' => 'merah',
            'harga' => 100000000,
            'type_request' => 'mobil',
            'mesin' => 'mesin',
            'kapasitas_penumpang' => 4,
            'tipe' => 'sedan',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2010,
            'warna' => 'merah',
            'harga' => 100000000,
            'status' => 'available'
        ]);
        $this->assertDatabaseHas('mobils', [
            'mesin' => 'mesin',
            'kapasitas_penumpang' => 4,
            'tipe' => 'sedan'
        ]);
    }

    // test creating motor
    public function testCreatingMotor()
    {
        $response = $this->postJson('/api/kendaraan/store', [
            'tahun_keluaran' => 2010,
            'warna' => 'merah',
            'harga' => 100000000,
            'type_request' => 'motor',
            'mesin' => 'mesin',
            'suspensi' => 'suspensi',
            'transmisi' => 'est',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2010,
            'warna' => 'merah',
            'harga' => 100000000,
            'status' => 'available'
        ]);
        $this->assertDatabaseHas('motors', [
            'mesin' => 'mesin',
            'suspensi' => 'suspensi',
            'transmisi' => 'est'
        ]);
    }

    // test updating kendaraan
    public function testUpdatingKendaraan()
    {
        $response = $this->postJson('/api/kendaraan/store', [
            'tahun_keluaran' => 2010,
            'warna' => 'merah',
            'harga' => 100000000,
            'type_request' => 'motor',
            'mesin' => 'mesin',
            'suspensi' => 'suspensi',
            'transmisi' => 'est',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2010,
            'warna' => 'merah',
            'harga' => 100000000,
            'status' => 'available'
        ]);
        $this->assertDatabaseHas('motors', [
            'mesin' => 'mesin',
            'suspensi' => 'suspensi',
            'transmisi' => 'est'
        ]);

        $id = $response->json()['data']['_id'];
        $response = $this->putJson('/api/kendaraan/update/product/'.$id, [
            'tahun_keluaran' => 2011,
            'warna' => 'merah',
            'harga' => 200000000,
            'type' => 'kendaraan'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2011,
            'warna' => 'merah',
            'harga' => 200000000,
            'status' => 'available'
        ]);
        $this->assertDatabaseHas('motors', [
            'mesin' => 'mesin',
            'suspensi' => 'suspensi',
            'transmisi' => 'est'
        ]);
    }

    // test updating mobil
    public function testUpdatingMobil()
    {
        $response = $this->postJson('/api/kendaraan/store', [
            'tahun_keluaran' => 2010,
            'warna' => 'merah',
            'harga' => 100000000,
            'type_request' => 'mobil',
            'mesin' => 'mesin',
            'kapasitas_penumpang' => 4,
            'tipe' => 'sedan',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2010,
            'warna' => 'merah',
            'harga' => 100000000,
            'status' => 'available'
        ]);
        $this->assertDatabaseHas('mobils', [
            'mesin' => 'mesin',
            'kapasitas_penumpang' => 4,
            'tipe' => 'sedan'
        ]);

        $id = $response->json()['data']['_id'];
        $response = $this->putJson('/api/kendaraan/update/product/'.$id, [
            'mesin' => 'mesin',
            'kapasitas_penumpang' => 6,
            'tipe' => 'toyota',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2010,
            'warna' => 'merah',
            'harga' => 100000000,
            'status' => 'available'
        ]);
        $this->assertDatabaseHas('mobils', [
            'mesin' => 'mesin',
            'kapasitas_penumpang' => 6,
            'tipe' => 'toyota',
        ]);
    }

    // test updating motor
    public function testUpdatingMotor()
    {
        $response = $this->postJson('/api/kendaraan/store', [
            'tahun_keluaran' => 2015,
            'warna' => 'ungu',
            'harga' => 100000000,
            'type_request' => 'motor',
            'mesin' => 'mesin',
            'suspensi' => "suspensi",
            'transmisi' => '2gigi',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2015,
            'warna' => 'ungu',
            'harga' => 100000000,
        ]);
        $this->assertDatabaseHas('motors', [
            'mesin' => 'mesin',
            'suspensi' => "suspensi",
            'transmisi' => '2gigi',
        ]);

        $id = $response->json()['data']['_id'];
        $response = $this->putJson('/api/kendaraan/update/product/'.$id, [
            'mesin' => 'mesin',
            'suspensi' => "suspensibaru",
            'transmisi' => '4gigi',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2015,
            'warna' => 'ungu',
            'harga' => 100000000,
            'status' => 'available'
        ]);
        $this->assertDatabaseHas('motors', [
            'mesin' => 'mesin',
            'suspensi' => "suspensibaru",
            'transmisi' => '4gigi',
        ]);
    }

    // test updating status kendaraan
    public function testUpdatingStatusKendaraan()
    {
        $response = $this->postJson('/api/kendaraan/store', [
            'tahun_keluaran' => 2015,
            'warna' => 'ungu',
            'harga' => 100000000,
            'type_request' => 'motor',
            'mesin' => 'mesin',
            'suspensi' => "suspensi",
            'transmisi' => '2gigi',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2015,
            'warna' => 'ungu',
            'harga' => 100000000,
        ]);
        $this->assertDatabaseHas('motors', [
            'mesin' => 'mesin',
            'suspensi' => "suspensi",
            'transmisi' => '2gigi',
        ]);

        $id = $response->json()['data']['_id'];
        $response = $this->putJson('/api/kendaraan/update/status/'.$id, [
            'status' => 'sold',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2015,
            'warna' => 'ungu',
            'harga' => 100000000,
            'status' => 'sold'
        ]);
        $this->assertDatabaseHas('motors', [
            'mesin' => 'mesin',
            'suspensi' => "suspensi",
            'transmisi' => '2gigi',
        ]);
    }

    // test deleting kendaraan implementing softdelete that column deleted_at not null
    public function testDeletingKendaraan()
    {
        $response = $this->postJson('/api/kendaraan/store', [
            'tahun_keluaran' => 2015,
            'warna' => 'ungu',
            'harga' => 100000000,
            'type_request' => 'motor',
            'mesin' => 'mesin',
            'suspensi' => "suspensi",
            'transmisi' => '2gigi',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2015,
            'warna' => 'ungu',
            'harga' => 100000000,
        ]);
        $this->assertDatabaseHas('motors', [
            'mesin' => 'mesin',
            'suspensi' => "suspensi",
            'transmisi' => '2gigi',
        ]);

        $id = $response->json()['data']['_id'];
        $response = $this->deleteJson('/api/kendaraan/delete/'.$id);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kendaraans', [
            'tahun_keluaran' => 2015,
            'warna' => 'ungu',
            'harga' => 100000000,
        ])->assertNotNull('deleted_at');
        $this->assertDatabaseHas('motors', [
            'mesin' => 'mesin',
            'suspensi' => "suspensi",
            'transmisi' => '2gigi',
        ]);
    }

    // test index kendaraan create 10 kendaraan
    public function testIndexKendaraan()
    {
        Kendaraan::factory(10)->create();
        $response = $this->getJson('/api/kendaraan/index');

        $response->assertStatus(200);
        $this->assertCount(10, $response->json()['data']);
    }

    // test report kendaraan with two input start date and end date. the format input using dd-mm-yyyy
    public function testReportKendaraan()
    {
        Kendaraan::factory(10)->create();
        //create two variable, start is now but minus 1 day and end now but plus 1 day.
        $start = Carbon::now()->subDay()->format('d-m-Y');
        $end = Carbon::now()->addDay()->format('d-m-Y');
        $response = $this->getJson('/api/kendaraan/report/'.$start.'/'.$end);

        $response->assertStatus(200);
        $this->assertDatabaseCount('kendaraans', 10);
    }
}
