<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Contract;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class KontrakTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    // Menuju Halaman Login
                    ->visit('/')
                    // Menginput Email dan Password
                    ->type('email','admin@dapurbunda.com')
                    ->type('password','123456')
                    // Menekan Tombol "Sign In"
                    ->press('Sign_In')
                    // Setelah Login, di cek apakah "Dasbor" tersedia
                    ->assertSee('Dasbor')
                    // Jika tersedia maka ke url kontrak
                    ->visit('/kontrak')
                    // Setelah ke url laporan, di cek lagi apakah "Daftar Menu Kontrak" tersedia
                    ->assertSee('Daftar Menu Kontrak')->pause(500)
                    // Jika tersedia, maka klik button class "#btn-tambah"
                    ->click('#btn-tambah')
                    // Kemudian jika muncul modal dengan id "modal-tambah"
                    ->whenAvailable('#modal-tambah', function($mt){
                        // Setelah muncul modal, apakah terdeteksi text "Menambahkan Data Kontrak"
                        $mt->assertSee('Menambahkan Data Kontrak')
                           ->type('#tanggal_mulai', date('Y-m-d'))
                           ->type('#tanggal_berakhir', date('Y-m-d'))
                           ->click('#btn-tambah-action')
                           ->pause(1000);
                    });
                    if(Contract::get()->count() == 0)
                    {
                        $browser->pause(4000)
                                ->press('.swal-button--confirm')
                                ->pause(500)
                                ->script('ubah_terbaru();');
                    }
                    else {
                        $browser->whenAvailable('.swal-modal', function($tb){
                            $tb->assertSee('Menyalin Data Kontrak Sebelumnya')
                            ->press('.swal-button--confirm')
                            ->pause(3000);
                        })
                        ->pause(2500)
                        ->press('.swal-button--confirm')
                        ->pause(500)
                        ->script('ubah_terbaru();');;            
                    }
            
            $browser->pause(1500)
                    ->whenAvailable('#modal-ubah', function($mu){
                        $mu->assertSee('Mengubah Data Kontrak')
                           ->type('#tanggal_mulai_ubah', "")
                           ->type('#tanggal_berakhir_ubah', "")
                           ->type('#tanggal_mulai_ubah', date('Y-m-d'))
                           ->type('#tanggal_berakhir_ubah', date('Y-m-d'))
                           ->click('#btn-ubah-action')
                           ->pause(1000);
                    })
                    ->pause(2000)
                    ->press('.swal-button--confirm')
                    ->pause(500)
                    ->script('detail_terbaru();');                    
            
            // Kontrak Detail
            $browser->pause(1500)

                    // Tambah
                    ->whenAvailable('#modal-detail', function($md){
                        $md->assertSee('Menampilkan Detail Data Kontrak')
                           ->click('#btn-list-detail-kontrak')
                           ->pause(1500);
                    })
                    ->assertSee('Daftar Menu Detail Kontrak')
                    ->pause(500)
                    ->click('#btn-tambah')
                    ->pause(2500)
                    ->type('#budget', '2500000')
                    ->type('#name', 'Dapur Bunda')
                    ->script('pilih_makanan(3);');
                    
            $browser->pause(500)
                    ->click('#btn-tambah')
                    ->pause(4000)
                    ->assertSee('Daftar Menu Detail Kontrak')
                    ->pause(1000)
                    ->press('.btn-success')

                    // Detail
                    ->whenAvailable('#modal-detail', function($md){
                        $md->assertSee('Menampilkan Data Detail Kontrak')
                           ->pause(2000)
                           ->press('.btn-default');
                    })

                    // Ubah
                    ->pause(500)
                    ->press('.btn-warning')
                    ->pause(2000)
                    ->assertSee('Mengubah Data Detail Kontrak')
                    ->pause(500)
                    ->type('#budget', '3500000')
                    ->type('#name', 'Dapur Bunda - Diubah')
                    ->script('pilih_makanan(1);');

            $browser->pause(500)
                    ->click('#btn-ubah')
                    ->pause(4000)
                    ->assertSee('Daftar Menu Detail Kontrak')
                    ->pause(500)
                    
                    // Menuju ke Pegawai Contract
                    ->press('.btn-primary')
                    ->pause(2000)
                    ->assertSee('Daftar Menu Kontrak Pegawai')
                    ->pause(500)
                    ->click('#btn-tambah')
                    ->whenAvailable('#modal-tambah', function($mt){
                        $mt->assertSee('Menambahkan Data Kontrak Pegawai')
                           ->click('#btn-tambah-action')
                           ->pause(3500);
                    })
                    ->pause(500)
                    ->press('.swal-button--confirm')
                    ->pause(500)
                    
                    // Kembali Ke Halaman Kontrak Detail
                    ->script('kembali_ke_kontrak_detail();');

            // Detail Kontrak
            $browser->pause(2000)

                    // Hapus Data Detail Kontrak
                    ->press('.btn-danger')
                    ->pause(500)
                    ->press('.swal-button--confirm')
                    ->pause(4000)
                    ->press('.swal-button--confirm')
                    ->pause(500)

                    // Kembali Ke Halaman Kontrak
                    ->visit('/kontrak')
                    ->assertSee('Daftar Menu Kontrak')->pause(500);
            
            $browser->pause(2000)
                    // Hapus Kontrak
                    ->script('hapus_terbaru();');
            
            $browser->pause(500)
                    ->press('.swal-button--confirm')
                    ->pause(4000)
                    ->press('.swal-button--confirm')
                    ->pause(1500);
        });
    }
}
