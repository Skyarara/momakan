<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Menu;
use App\EmployeeMenu;

class EmployeeMenuTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testEmployeeMenu()
    {
        $this->browse(function (Browser $browser) {
            $menu = Menu::first()->value('name');
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

                    // 6. menekan tombol list employee menu
                    ->press("#employee-menu1")->pause('1250')

                    // 7. menambah data employee menu
                    ->press('#tbh')->pause('2500')
                    ->select2('#menu', "$menu")
                    ->type('#qty', '1')
                    ->type('#notes', 'Sushiii')
                    ->press('#tambah-btn')->pause('1250')
                    ->assertSee('Sukses!')->pause('1250')
                    ->press('.swal-button--confirm')->pause('1250');

                    $empmenu = EmployeeMenu::latest()->value('id');
                    $menus = Menu::latest()->value('name');
                    $browser

                    // 8. mengubah data employee menu
                    ->press("#edit$empmenu")->pause('2500')
                    ->select2('select2-menu_edit-container', "$menus")
                    ->type('#qty_edit', '2')
                    ->type('#notes_edit', 'Tadi 1 diganti jadi 2')
                    ->press('#ubah-btn')
                    ->assertSee('Mengubah Data')->pause('1250')
                    ->press('.swal-button--confirm')->pause('1250')
                    ->assertSee('Sukses!')->pause('1250')
                    ->press('.swal-button--confirm')->pause('1250')

                    // 9. menghapus data employee menu
                    ->press("#delete$empmenu")->pause('1250')
                    ->assertSee('Menghapus Data')->pause('1250')
                    ->press('.swal-button--confirm')->pause('1250')
                    ->assertSee('Data Berhasil di hapus')->pause('1250')
                    ->press('.swal-button--confirm')->pause('2500');
        });
    }
}
