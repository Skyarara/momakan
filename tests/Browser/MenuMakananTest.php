<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\MenuCategory;
use App\Vendor;
use App\Menu;
use App\MenuPackage;

class MenuMakananTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testMenuMakananSuccess()
    {
        Browser::macro('scrollToElement', function ($element = null) {
            $this->script("$('html, body').animate({ scrollTop: $('$element').offset().top }, 0);");
            return $this;
        });
        $this->browse(function (Browser $browser) {
            $category = MenuCategory::orderBy('name', 'desc')->value('name');
            $vendor = Vendor::first()->value('name');
            $browser->maximize()

                // 1. ke menu login
                ->visit('/')

                // 2. memasukan email dan password admin untuk login
                ->type('email', 'admin@dapurbunda.com')
                ->type('password', '123456')

                // 3. menekan tombol login
                ->press('Sign_In')

                // 4. dialihkan ke menu utama dapurbunda
                ->assertPathIs('/home')
                ->visit('/menu_makanan')->pause('2500')

                // 5. menambah makanan
                ->press('#tbh')->pause('2500')
                ->select2('#kategori_makanan', "$category")
                ->select2('#vendor', "$vendor")
                ->type('#nama_makanan', 'Makanan11111')
                ->type('#deskripsi', 'Rasanya enak')
                ->type('#harga', '15000')
                ->attach('gambar', public_path('images/dapurkota.png'))
                ->press('#tambah-btn')->pause('1250')
                ->assertSee('Sukses!')->pause('1250')
                ->press('.swal-button--confirm')->pause('2500');

            $menu = Menu::latest()->value('id');
            $category = MenuCategory::orderBy('name', 'desc')->value('name');
            $vendors = Vendor::first()->value('name');
            $browser

                // 6. mengedit makanan
                ->scrollToElement("#edit$menu")->pause('1250')
                ->press("#edit$menu")->pause('1250')
                ->select2('#kategori_makanan_edit', "$category")
                ->select2('#vendor_edit', "$vendor")
                ->type('#nama_makanan_edit', 'Makanan22222')
                ->type('#deskripsi_edit', 'Rasanya ENAAAAKKK SEKALI')
                ->type('#harga_edit', '20000')
                ->attach('gambar_edit', public_path('images/telur.jpg'))
                ->driver->executeScript('var el = $("#modal-default"); el.scrollTop( el.prop("scrollHeight") + -700 );');
            $browser
                ->press('#ubah-btn')->pause('1250')
                ->assertSee('Mengubah Data')->pause('1250')
                ->press('.swal-button--confirm')->pause('1250')
                ->assertSee('Sukses!')->pause('1250')
                ->press('.swal-button--confirm')->pause('1250')

                // 7. menghapus makanan
                ->scrollToElement("#delete$menu")->pause('1250')
                ->press("#delete$menu")->pause('1250')
                ->assertSee('Menghapus Data')->pause('1250')
                ->press('.swal-button--confirm')->pause('1250')
                ->assertSee('Sukses!')->pause('1250')
                ->press('.swal-button--confirm')->pause('2500');
        });
    }

    public function testMenuMakananFail()
    {
        $this->browse(function (Browser $browser) {
            $category = MenuCategory::orderBy('name', 'desc')->value('name');
            $vendor = Vendor::first()->value('name');
            $browser->maximize()

                // 1. dialihkan ke menu utama dapurbunda
                ->visit('/home')
                ->visit('/menu_makanan')->pause('1250')

                // 2. menambah makanan
                ->press('#tbh')->pause('1250')
                ->select2('#kategori_makanan', "$category")
                ->select2('#vendor', "$vendor")
                ->type('#nama_makanan', 'Makanan 1')
                ->type('#deskripsi', 'Rasanya enak')
                ->type('#harga', '15000')
                ->attach('gambar', public_path('images/S.jpg'))
                ->press('#tambah-btn')->pause('1250')
                ->assertSee('Sukses!')->pause('1250')
                ->press('.swal-button--confirm')->pause('1250');

            $menu = Menu::latest()->value('id');
            $category = MenuCategory::orderBy('name', 'desc')->value('name');
            $vendors = Vendor::first()->value('name');
            $paket = MenuPackage::first()->value('menu_id');
            $browser

                // 3. mengedit makanan
                ->press("#edit$menu")->pause('1250')
                ->select2('#kategori_makanan_edit', "$category")
                ->select2('#vendor_edit', "$vendor")
                ->type('#nama_makanan_edit', 'Makanan 1')
                ->type('#deskripsi_edit', 'Rasanya enak')
                ->type('#harga_edit', '15000')
                ->attach('gambar_edit', public_path('images/P.jpg'))
                ->press('#ubah-btn')->pause('1250')
                ->assertSee('Mengubah Data')->pause('1250')
                ->press('.swal-button--confirm')->pause('1250')
                ->assertSee('Sukses!')->pause('1250')
                ->press('.swal-button--confirm')->pause('1250')

                // 4. menghapus makanan gagal
                ->scrollToElement("#delete$paket")->pause('1250')
                ->press("#delete$paket")->pause('1250')
                ->assertSee('Menghapus Data')->pause('1250')
                ->press('.swal-button--confirm')->pause('1250')
                ->assertSee('Data Tidak Bisa Di Hapus')->pause('1250')
                ->press('.swal-button--confirm')->pause('2500');
        });
    }
}
