<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Vendor;
use App\Menu;
use App\MenuPackage;
use App\ContractDetailMenu;
use PHPUnit\Framework\Assert;

class Makanan_PaketTest extends DuskTestCase
{

    public function testMakanan_Paket()
    {
        Browser::macro('scrollToElement', function ($element = null) {
            $this->script("$('html, body').animate({ scrollTop: $('$element').offset().top }, 0);");
            return $this;
        });
        $this->browse(function (Browser $browser) {
            $vendor = Vendor::first()->name;
            $vendorN = Vendor::latest()->value('name');
            $menu_package = MenuPackage::pluck('parent_id');
            $menu = Menu::whereNotIn('id',  $menu_package)->latest()->value('id');
            $menuN = Menu::whereNotIn('id',  $menu_package)->first()->id;
            $browser->maximize()
                // login
                ->loginAs(User::find(1))
                ->visit('/home')
                ->AssertSee('Dapur Bunda')
                ->ClickLink('Menu')->pause('500')
                ->ClickLink('Makanan Paket')->assertPathIs('/makanan_paket')
                //Tambah Begin
                ->clickLink('Tambah')
                ->pause('1000')
                ->AssertPathIs('/makanan_paket/tambah')
                ->type('#food', 'Paket Combo')
                ->type('deskripsi', 'Paket Combo')
                ->type('#harga', '30000')
                ->scrollToElement('.btn-primary')
                ->select2('#vendor', "$vendor")
                ->attach('gambar', public_path('images/dapurkota.png'))
                ->check("#mk$menu")
                ->Press('.btn-primary')->pause('1000')
                ->AssertSee('Berhasil Menambahkan Makanan')
                ->Press('.swal-button--confirm')->pause('1000');
            $menu_packageN = MenuPackage::latest()->value('parent_id');
            //Edit Begin
            $browser
                ->scrollToElement("#edit$menu_packageN")
                ->Press("#edit$menu_packageN")
                ->AssertSee('Edit Makanan Paket')
                ->clear('name')->clear('deskripsi')->clear('#harga')
                ->scrollToElement('.btn-primary')
                ->uncheck("#mk$menu")
                ->scrollToElement('#food')
                ->type('#food', 'Telur Matasapi')
                ->type('deskripsi', 'Telur Goreng')
                ->type('#harga', '3000')
                ->scrollToElement('.btn-primary')
                ->select2('#vendor', "$vendorN")
                ->attach('gambar_edit', public_path('images/telur.jpg'))
                ->check("#mk$menuN")
                ->Press('.btn-primary')->pause('1000')
                ->AssertSee('Berhasil Mengubah Makanan')
                ->Press('.swal-button--confirm')->pause('1000')
                //delete begin
                ->scrollToElement("#delete$menu_packageN")
                ->Press("#delete$menu_packageN")
                ->press('.swal-button--danger')->pause('1000')
                ->AssertSee('Data Berhasil Dihapus')
                ->Press('.swal-button--confirm')->pause('3000');
        });
    }

    protected function uncheck($element)
    {
        return $this->storeInput($element, false);
    }

    public function testMakanan_PaketFail()
    {

        Browser::macro('scrollToElement', function ($element = null) {
            $this->script("$('html, body').animate({ scrollTop: $('$element').offset().top }, 0);");
            return $this;
        });
        $this->browse(function (Browser $browser) {
            $menu_package = MenuPackage::pluck('parent_id');
            $cdm = ContractDetailMenu::whereIn('menu_id',  $menu_package)->first()->value('menu_id');
            $browser->maximize()
                ->loginAs(User::find(1))
                ->visit('/home')
                ->AssertSee('Dapur Bunda')
                ->ClickLink('Menu')->pause('500')
                ->ClickLink('Makanan Paket')->assertPathIs('/makanan_paket')
                //Tambah Begin
                ->clickLink('Tambah')
                ->pause('1000')
                ->AssertPathIs('/makanan_paket/tambah')
                ->scrollToElement('.btn-primary')
                ->Press('.btn-primary')
                ->scrollToElement('#food')
                ->type('#food', 'Telur Matasapi')
                ->scrollToElement('.btn-primary')
                ->Press('.btn-primary')
                ->type('deskripsi', 'Telur Goreng')
                ->Press('.btn-primary')
                ->type('#harga', '3000')
                ->Press('.btn-primary')
                ->attach('gambar', public_path('images/telur.jpg'))
                ->Press('.btn-primary')->pause('3000')
                ->assertSee('Gagal')
                ->Press('.swal-button--confirm')->pause('1000')
                ->Press('.btn-warning')->pause('1000')
                ->clear('name')->clear('deskripsi')->clear('#harga')
                ->scrollToElement('.btn-primary')
                ->press('.btn-primary')
                ->scrollToElement('#food')
                ->type('#food', 'Telur Matasapi')
                ->scrollToElement('.btn-primary')
                ->Press('.btn-primary')
                ->type('deskripsi', 'Telur Goreng')
                ->scrollToElement('.btn-primary')
                ->Press('.btn-primary')
                ->type('#harga', '3000')
                ->uncheck("checked")
                ->scrollToElement('.btn-primary')
                ->Press('.btn-primary')->pause('1000')
                ->assertSee('Gagal')
                ->Press('.swal-button--confirm')->pause('1000')
                //delete begin
                ->scrollToElement("#delete$cdm")
                ->Press("#delete$cdm")
                ->press('.swal-button--danger')->pause('1000')
                ->AssertSee('Data Gagal di Hapus')
                ->Press('.swal-button--confirm')->pause('3000');
        });
    }
}
