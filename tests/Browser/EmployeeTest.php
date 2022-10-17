<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EmployeeTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testEmployee()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
            
                    // 1. ke menu login
                    ->visit('/')

                    // 2. memasukan email dan password admin untuk login
                    ->type('email','admin@dapurbunda.com')
                    ->type('password','123456')

                    // 3. menekan tombol login
                    ->press('Sign_In')

                    // 4. dialihkan ke menu utama dapurbunda
                    ->assertPathIs('/home')

                    // 5. ke menu perusahaan lalu ke menu pegawai perusahaan
                    ->visit('/home/corporate')
                    ->visit('/employee/data/2')->pause('1250')

                    // 6. menekan tombol "Tambah" di menu pegawai untuk memeunculkan modal tambah pegawai
                    ->press('#tbh')->pause('1250')

                    // 7. menginput informasi pegawai untuk di tambahkan
                    ->type('#name', 'Nasrullah')
                    ->type('#phone_number', '05413200')
                    ->type('#email', 'NsL791@gmail.com')
                    ->type('#password', '1')
                    ->type('#password2', '1')

                    // 8. menekan tombol "Tambah" di modal tambah pegawai
                    ->press('#add')->pause('2500')

                    // 9. memunculkan swal "Berhasil Menambahkan Data"
                    ->assertSee('Berhasil Menambahkan Data')->pause('1250')

                    // 10. dialihkan kembali ke menu pegawai
                    ->visit('/employee/data/2')->pause('2500')


                    // -- Gagal Mengubah Data Pegawai -- //


                    // 11. menekan tombol edit pegawai berdasarkan script punchNewData
                    ->script('punchNewDatas()');

                    // 12. mengosongkan field data pegawai
                    $browser
                    ->pause('1250')
                    ->clear('#nm')
                    ->clear('#tp')
                    ->clear('#as')
                    
                    // 13. menekan tombol "ubah" di modal Ubah Karyawan
                    ->click('#update')->pause('2500')

                    // 14. memunculkan swal "Gagal Mengubah Data" dikarenakan terdapat field yang kosong
                    ->assertSee('Email tidak valid! Harap coba lagi')->pause('1250')

                    // 15. kembali ke menu pegawai
                    ->visit('/employee/data/2')->pause('2500')


                    // -- Berhasil Mengubah Data Pegawai
                    

                    // 16. menekan tombol edit yang ber ikon pensil
                    ->script('punchNewDatas()');

                    // 17. mengosongkan field data pegawai
                    $browser
                    ->pause('1250')
                    ->clear('#nm')
                    ->clear('#tp')
                    ->clear('#as')

                    // 18. mengisi kembali field data pegawai dengan data yang mau diubah
                    ->type('#nm', 'Muhammad Nasrullah')
                    ->type('#tp', '082256529935')
                    ->type('#as', 'Nasrullah@gmail.com')

                    // 19. menekan tombol "Ubah" di modal Ubah Karyawan
                    ->click('#update')->pause('2500')

                    // 20. memunculkan swal "Berhasil Mengubah Data"
                    ->assertSee('Berhasil Mengubah Data')->pause('2500')

                    // 21. dialihkan kembali ke menu pegawai 
                    ->visit('/employee/data/2')->pause('1250')


                    // -- Menghapus Data Pegawai -- //


                    // 22. menekan tombol delete yang ber ikon tong sampah
                    ->script('punchNewData()');

                    // 23. menekan tombol "Hapus" di modal Hapus Karyawan
                    $browser
                    ->pause('1250')
                    ->click('#update')->pause('1250')

                    // 24. dialihkan kembali ke menu pegawai
                    ->visit('/employee/data/2')->pause('2500');
        });
    }
}
