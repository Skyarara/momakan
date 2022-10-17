<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\MenuCategory;
use App\Menu;

/**
 * TestSuccescategory Flow
 * 
 * 1.Login Sebagai Admin
 * 2.Ke Halaman Utama
 * 3.Ke Halaman Makanan dengan Menklik Side Menu "Makanan"
 * 4.Ke Halaman Kategori dengan Menklik Tombol "Kategori"
 * 5.Menklik Tombol Tambah
 * 6.Muncul Alert berhasil dan klik "OK"
 * 7.Menekan Tombol Icon Pensil
 * 8.Mengisi field kategori dengan kategori baru
 * 9,Muncul alert berhasil dan klik "OK"
 * 10.Mengklik tombol Icon Keranjang sampah Delete kategori yang tidak berelasi
 * 11.Muncul alert berhasil dan klik "OK"
 * 
 * TestFailcategory Flow
 * 
 * 1.Login Sebagai Admin
 * 2.Ke Halaman Utama
 * 3.Ke Halaman Makanan dengan Menklik Side Menu "Makanan"
 * 4.Ke Halaman Kategori dengan Menklik Tombol "Kategori"
 * 5.Menklik Tombol Tambah
 * 6.Cek Validasi Tambah dengan langsung mengkilik Tambah 
 * 7.Muncul error dan tutup modal tambah
 * 8.Menekan Tombol Icon Pensil
 * 9.Menghapus isi field kategori dan klik ubah 
 * 10.muncul error dan tutup modal edit
 * 11.Mengklik tombol Icon Keranjang sampah di kategori yg berelasi
 * 12.Menghapus Kategori yang berelasi
 * 13.Muncul alert error dan klik "OK" 
 */

class CategoryTest extends DuskTestCase
{
    public function testSuccesscategory()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                //1.login
                ->loginAs(User::find(1))
                //2.halaman utama
                ->visit('/home')->pause('2000')
                //3.halaman makanan
                ->clickLink('Menu')->pause('2000')
                //4.halaman kategori
                ->clickLink('Kategori')->pause('2000')
                ->assertPathIs('/kategori')
                //5.tambah kategori begin
                ->ClickLink('Tambah Kategori')
                ->whenAvailable('#create', function ($tambah_kategori) {
                    $tambah_kategori->assertSee('Kategori')
                        ->type('name', 'Camilan')->pause('3000')
                        ->press('#add')->pause('3000');
                })
                //6.after tambah katagori
                ->assertSee('Berhasil Menambahkan Data')
                ->press('.swal-button--confirm')->pause('2000');
            //7.edit kategori begin
            $kategori = MenuCategory::orderBy('id', 'DESC')->value('id');
            $browser->press("#edit$kategori")->pause('3000') //perlu nomor
                ->whenAvailable('#show', function ($edit_kategori) {
                    $edit_kategori->assertSee('Ubah Kategori')
                        //8.real edit
                        ->type('#nm', 'Minuman')
                        ->press('#update')->pause('5000');
                })
                //9.after edit
                ->assertSee('Berhasil Mengubah Data')
                ->press('.swal-button--confirm')->pause('3000');
            //Query untuk menghapus data terbaru
            $kategori = MenuCategory::orderBy('id', 'DESC')->value('id');
            //10.delete kategori begin
            $browser->press("#delete$kategori")->pause('3000')
                ->whenAvailable('#show', function ($delete_kategori) {
                    $delete_kategori->assertSee('Hapus Kategori')
                        ->press('#update')->pause('3000');
                })
                //11.after delete
                ->assertSee('Berhasil Menghapus Data')
                ->press('.swal-button--confirm');
        });
    }
    public function testFailcategory()
    {
        $this->browse(function (Browser $browser) {
            //Query makanan mencari kategori yang berelasi
            $makanan = Menu::value('menu_category_id');
            $browser->maximize()
                //1.login
                ->loginAs(User::find(1))
                //2.halaman utama
                ->visit('/home')->pause('2000')
                //3.halaman makanan
                ->clickLink('Menu')->pause('2000')
                //4.halaman kategori
                ->clickLink('Kategori')->pause('2000')
                ->assertPathIs('/kategori')
                //5.Tambah Kategori
                ->ClickLink('Tambah Kategori')
                ->whenAvailable('#create', function ($tambah_kategori) {
                    //6.cek validasi tambah kategori
                    $tambah_kategori->assertSee('Kategori')
                        ->press('#add')->Pause('2000')
                        ->assertSee('Kategori Masih Kosong')
                        //7.After cek validasi tambah
                        ->press('.btn-secondary')->pause('2000');
                })
                //8.edit kategori begin
                ->press("#edit$makanan")->pause('3000') //perlu nomor
                ->whenAvailable('#show', function ($edit_kategori) {
                    $edit_kategori->assertSee('Ubah Kategori')
                        //9.cek validasi edit kategori
                        ->clear('#nm')
                        ->press('#update')
                        //10.after cek validasi delete kategori
                        ->assertSee('Nama Kategori Masih Kosong')
                        ->press('.brn-sm')->pause('2000');
                })
                //11.delete kategori begin
                ->press("#delete$makanan")->pause('3000') //perlu nomor
                ->whenAvailable('#show', function ($delete_kategori) {
                    //12.cek validasi delete kategori
                    $delete_kategori->assertSee('Hapus Kategori')
                        ->press('#update')->pause('3000');
                })
                //13.After cek validasi delete
                ->assertSee('Gagal Menghapus Data')
                ->press('.swal-button--confirm')->pause('3000');
        });
    }
}
