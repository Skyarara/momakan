<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Promo;
use PHPUnit\Framework\Assert;

class PromoTest extends DuskTestCase
{
    public function testPromoSuccess()
    {
        Browser::macro('scrollToElement', function ($element = null) {
            $this->script("$('html, body').animate({ scrollTop: $('$element').offset().top }, 0);");
            return $this;
        });
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                ->loginAs(User::find(1))
                ->visit('/home')
                ->AssertSee('Dapur Bunda')
                ->ClickLink('Menu')->pause('500')
                ->ClickLink('Promo')->assertPathIs('/promo')->pause('1000')
                //Tambah Begin
                ->Press('#new')
                ->whenAvailable('#create', function ($tambah) {
                    $tambah->assertSee('Promo Baru')
                        ->type('title', 'Camilan')
                        ->type('deskripsi', 'Kripik')
                        ->attach('gambar', public_path('images/dapurkota.png'))
                        ->press('#add')->pause('1000');
                });
            $promo = Promo::latest()->value('id');
            $browser->assertSee('Berhasil Menambahkan Data')
                ->press('.swal-button--confirm')->pause('1000')
                //Edit Begin
                ->scrollToElement("#delete$promo")
                ->press("@$promo")
                ->whenAvailable('#show', function ($edit) {
                    $edit->AssertSee('Ubah Promo')
                        ->clear('edit_title')->clear('edit_deskripsi')
                        ->type('edit_title', 'Telur')
                        ->type('edit_deskripsi', 'Telur Goreng')
                        ->attach('#gambar_baru', public_path('images/telur.jpg'))
                        ->press('#update')->pause('1000');
                })
                ->assertSee('Berhasil Mengubah Data')
                ->press('.swal-button--confirm')->pause('500')
                //delete
                ->scrollToElement("#delete$promo")->pause('500')
                ->press("#delete$promo")
                ->press('.swal-button--confirm')->pause('500')
                ->assertSee('Data Berhasil di hapus')
                ->press('.swal-button--confirm')->pause('3000');
        });
    }

    public function testPromoFail()
    {
        Browser::macro('scrollToElement', function ($element = null) {
            $this->script("$('html, body').animate({ scrollTop: $('$element').offset().top }, 0);");
            return $this;
        });
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                ->loginAs(User::find(1))
                ->visit('/home')
                ->AssertSee('Dapur Bunda')
                ->ClickLink('Menu')->pause('500')
                ->ClickLink('Promo')->assertPathIs('/promo')->pause('1000')
                //Tambah Begin
                ->Press('#new')
                ->whenAvailable('#create', function ($tambah) {
                    $tambah->assertSee('Promo Baru')
                        ->press('#add')
                        ->assertSee('Nama Kosong')->assertSee('Deskripsi Kosong')->assertSee('Gambar Kosong')
                        ->press('.btn-secondary')->pause('1000');
                })
                //Edit Begin
                ->press('.btn-warning')
                ->whenAvailable('#show', function ($edit) {
                    $edit->AssertSee('Ubah Promo')
                        ->clear('edit_title')->clear('edit_deskripsi')
                        ->press('#update')->pause('1000');
                })
                ->assertSee('Gagal Mengubah Data')
                ->press('.swal-button--confirm')->pause('3000');
        });
    }
}
