<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use App\Contract;
use App\Corporate;
use App\User;

class CorporateTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */

    use withFaker;

    public function testCorporateSuccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()

                // login
                ->loginAs(User::find(1))

                // dialihkan ke menu perusahaan
                ->visit('/home/corporate')
                ->assertPathIs('/home/corporate')->pause('1250')

                // menekan tombol "Tambah" di menu perusahaan untuk memunculkan modal tambah perusahaan
                ->press('#tbh')->pause('2500')

                // mengecek validasi saat menambahkan data perusahaan
                ->press('#add')->pause('1250')

                // memasukkan informasi perusahaan agar dapat di tambahkan
                ->type('#name', 'Diskimonfo')
                ->type('#telp', '0821000000')
                ->type('#address', 'Samarinda')
                ->type('#email', $this->faker->email)
                ->type('#pass', '1')
                ->type('#pass2', '1')

                // menekan tombol "+Tambah" di modal tambah perusahaan
                ->press('#add')->pause('2500')

                // memunculkan swal "Berhasil Menambahkan Data"
                ->assertSee('Berhasil Menambahkan Data')->pause('1250')

                // dialihkan kembali ke menu perusahaan
                ->visit('/home/corporate')->pause('2500');

            // menekan tombol edit perusahaan yang ber ikon pensil
            $corporate = Corporate::latest()->value('id');
            $browser
                ->press("#edit$corporate")->pause('2500')

                // mengosongkan semua field data perusahaan
                ->clear('#nm')
                ->clear('#tp')
                ->clear('#as')

                // menginput data yang akan di ubah untuk perusahaan
                ->type('#nm', 'Diskominfo')
                ->type('#tp', '0821507230')
                ->type('#as', 'Samarinda Kota')

                // menekan tombol "Ubah" di modal edit perusahaan
                ->press('#update')->pause('2500')

                // memunculkan swal "Berhasil Mengubah Data"
                ->assertSee('Berhasil Mengubah Data')->pause('1250')

                // dialihkan kembali ke menu perusahan
                ->visit('/home/corporate')->pause('2500')

                // menekan tombol delete yang ber ikon tong sampah
                ->press("#delete$corporate")->pause('1250')

                // menekan tombol "Delete" di modal Delete Corporate
                ->press('#update')->pause('2500')

                // memunculkan swal "Berhasil Menghapus Data". data berhasil dihapus
                ->assertSee('Berhasil Menghapus Data')->pause('1250')

                // menekan tombol "OK"
                ->press('.swal-button--confirm')->pause('2500');
        });
    }

    public function testCorporateFail()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()

                ->loginAs(User::find(1))
                // dialihkan ke menu utama dapurbunda
                ->visit('/home')

                // dialihkan ke menu perusahaan
                ->visit('/home/corporate')
                ->assertPathIs('/home/corporate')->pause('1250')

                // menekan tombol "Tambah" di menu perusahaan untuk memunculkan modal tambah perusahaan
                ->press('#tbh')->pause('2500')

                // mengecek validasi saat menambahkan data perusahaan
                ->press('#add')->pause('1250')

                // memasukkan informasi perusahaan agar dapat di tambahkan
                ->type('#name', 'Diskimonfo')
                ->type('#telp', '0821000000')
                ->type('#address', 'Samarinda')
                ->type('#email', $this->faker->email)
                ->type('#pass', '1')
                ->type('#pass2', '1')

                // menekan tombol "+Tambah" di modal tambah perusahaan
                ->press('#add')->pause('2500')

                // memunculkan swal "Berhasil Menambahkan Data"
                ->assertSee('Berhasil Menambahkan Data')->pause('1250')

                // dialihkan kembali ke menu perusahaan
                ->visit('/home/corporate')->pause('2500');

            // menekan tombol edit perusahaan yang ber ikon pensil
            $corporate = Corporate::latest()->value('id');
            $browser
                ->press("#edit$corporate")->pause('2500')

                // mengosongkan semua field data perusahaan
                ->clear('#nm')
                ->clear('#tp')
                ->clear('#as')

                // menekan tombol "Ubah" di modal edit perusahaan
                ->press('#update')->pause('2500')

                // memunculkan swal "Gagal Mengubah Data"
                ->assertSee('Gagal Mengubah Data')->pause('1250')

                // dialihkan kembali ke menu perusahan
                ->visit('/home/corporate')->pause('2500');

            // menekan tombol delete yang ber ikon tong sampah
            $contract = Contract::latest()->value('corporate_id');
            $browser
                ->pause('2500')
                ->press("#delete$contract")->pause('1250')

                // menekan tombol "Delete" di modal Delete Corporate
                ->press('#update')->pause('2500')

                // memunculkan swal "Gagal Menghapus Data". perusahaan tersebut
                // tidak dapat dihapus dikarenakan masih memiliki kontrak yang aktif
                ->assertSee('Gagal Menghapus Data')->pause('1250')

                // menekan tombol "OK" untuk lanjut
                ->press('.swal-button--confirm')->pause('1250');
        });
    }
}
