<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;

class VendorTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    use withFaker;

    // Berhasil
    public function testCrudvendor()
    {            
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    // Menuju Halaman Login
                    ->visit('/login')
                    // Menginput Email dan Password
                    ->type('email','admin@dapurbunda.com')
                    ->type('password','123456')
                    // Menekan Tombol "Sign In"
                    ->press('Sign_In')
                    // Setelah Login menuju halaman Vendor
                    ->visit('/vendoor')
                    // Menunggu Sampai Melihat "Penyedia"
                    ->assertSee('Penyedia')
                    ->pause(3000)
                    // Jika Sesudah melihat "Penyedia" menekan tombol dengan class ".create-modal"
                    ->click('.create-modal')
                    // Jika Muncul Modal Yang Ber-Id "create"
                    ->whenAvailable('#create', function($mc){
                        // Menunggu Sampai melihat "Tambah Penyedia" didalam modal
                        $mc->assertSee('Tambah Penyedia')
                           // Mengisi Atribut
                           ->type('#name', 'penyediaku')
                           ->type('#tagline', 'Aku adalah Penyedia')
                           ->type('#email', $this->faker->email)
                           ->type('#pass', '123456')
                           ->type('#pass2', '123456')
                           ->type('#phone_number', '0987654321')
                           ->type('#address', 'Jalanan Jalan Jalanna')
                           // Selesai mengisi atribut , akan menekan tombol Yang Ber-Id "add"
                           ->click('#add')
                           ->pause(3000);                        
                    })
                    // Menunggu sampai melihat Pesan "Berhasil Menambahkan Data"
                    ->assertSee('Berhasil Menambahkan Data')
                    ->pause(1000)
                    // Menekan Tombol "OK" dengan class ".swal-button--confirm"
                    ->press('.swal-button--confirm')
                    ->pause(1000)
                    // Menekan Tombol Edit dengan class ".edit-modal"
                    ->click('.edit-modal')
                    // Jika Muncul Modal Yang Ber-Id "create"
                    ->whenAvailable('#show', function($me){
                        // Menunggu Sampai melihat "Ubah Penyedia" didalam modal
                        $me->assertSee('Ubah Penyedia')
                           // Mengisi Atribut
                           ->type('#nm', '')
                           ->type('#tp', '')
                           ->type('#as', '')
                           ->type('#nm', $this->faker->userName)
                           ->type('#tp', '0987654321')
                           ->type('#as', 'Aku adalah penyedia')
                            // Selesai mengisi atribut , akan menekan tombol Yang Ber-Id "update"
                           ->click('#update')
                           ->pause(3000);
                    })        
                    // Menunggu sampai melihat Pesan "Berhasil Mengubah Data"            
                    ->assertSee('Berhasil Mengubah Data')
                    ->pause(1000)
                    // Menekan Tombol "OK" dengan class ".swal-button--confirm"
                    ->press('.swal-button--confirm')
                    ->pause(1000)
                    // Menekan Tombol Delete denan Class 
                    ->script('punchNewData()'); // Hanya tersedia untuk Dusk
                    
                    // Membuat Variable Baru, Karena sebelumnya saat di test error
                    $browser
                    ->pause(1000)
                    ->whenAvailable('#show', function($md){
                        $md->assertSee('Hapus Perusahaan')
                           ->click('#update')
                           ->pause(3000);
                    })                         
                    ->pause(1000)
                    ->press('.swal-button--confirm')
                    ->pause(2000);
        });
    }
    // Gagal
    public function testHapusvendorgagal()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()                    
                    // menuju halaman Vendor
                    ->visit('/vendoor')
                    // Menunggu Sampai Melihat "Penyedia"
                    ->assertSee('Penyedia')
                    ->pause(3000)
                    // Jika Sesudah melihat "Penyedia" menekan tombol dengan class ".create-modal"
                    ->click('.create-modal')
                    // Jika Muncul Modal Yang Ber-Id "create"
                    ->whenAvailable('#create', function($mc){
                        // Menunggu Sampai melihat "Tambah Penyedia" didalam modal
                        $mc->assertSee('Tambah Penyedia')
                           // Mengisi Atribut
                           ->type('#name', 'penyediaku')
                           ->type('#tagline', 'Aku adalah Penyedia')
                           ->type('#email', $this->faker->email)
                           ->type('#pass', '123456')
                           ->type('#pass2', '123456')
                           ->type('#phone_number', '0987654321')
                           ->type('#address', 'Jalanan Jalan Jalanna')
                           // Selesai mengisi atribut , akan menekan tombol Yang Ber-Id "add"
                           ->click('#add')
                           ->pause(3000);                        
                    })
                    // Menunggu sampai melihat Pesan "Berhasil Menambahkan Data"
                    ->assertSee('Berhasil Menambahkan Data')
                    ->pause(1000)
                    // Menekan Tombol "OK" dengan class ".swal-button--confirm"
                    ->press('.swal-button--confirm')
                    ->pause(1000)
                    // Menekan Tombol Edit dengan class ".edit-modal"
                    ->click('.edit-modal')
                    // Jika Muncul Modal Yang Ber-Id "create"
                    ->whenAvailable('#show', function($me){
                        // Menunggu Sampai melihat "Ubah Penyedia" didalam modal
                        $me->assertSee('Ubah Penyedia')
                           // Mengisi Atribut
                           ->type('#nm', '')
                           ->type('#tp', '')
                           ->type('#as', '')
                           ->type('#nm', $this->faker->userName)
                           ->type('#tp', '0987654321')
                           ->type('#as', 'Aku adalah penyedia')
                            // Selesai mengisi atribut , akan menekan tombol Yang Ber-Id "update"
                           ->click('#update')
                           ->pause(3000);
                    })        
                    // Menunggu sampai melihat Pesan "Berhasil Mengubah Data"            
                    ->assertSee('Berhasil Mengubah Data')
                    ->pause(1000)
                    // Menekan Tombol "OK" dengan class ".swal-button--confirm"
                    ->press('.swal-button--confirm')
                    ->pause(1000)
                    // Menekan Tombol Delete denan Class 
                    ->press('.delete-modal'); // Hanya tersedia untuk Dusk
                    
                    // Membuat Variable Baru, Karena sebelumnya saat di test error
                    $browser
                    ->pause(1000)
                    ->whenAvailable('#show', function($md){
                        $md->assertSee('Hapus Perusahaan')
                           ->click('#update')
                           ->pause(3000);
                    })                         
                    ->pause(1000)
                    ->press('.swal-button--confirm')
                    ->pause(2000);
        });
    }


}
