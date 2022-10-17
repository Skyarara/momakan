<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
/**
 * testUserfail
 * 
 * 1.Login Sebagai Admin
 * 2.Ke Halaman Utama
 * 3.Ke Halaman Manajemen User Dengan Menklik Side Menu "User"
 * 4.Menekan Tobol Icon Pensil
 * 5.Validasi Edit
 * 6.Clear Semua Field Dan Menekan Edit
 * 7.Hanya mengisi Field Nama Dan Menekan Edit
 * 8.Mengisi Field Nama dan nomor telpon Dan Menekan Edit
 * 9.Mengisi Field Email Dengan Format Yang Salah Dan Menekan Edit
 * 10.Mengisi Field Email Dengan Email Yang Sudah Ada Dan Menekan Edit
 * 11.Menutup modal edit user
 * 
 * testUsersuccess
 * 1.Login Sebagai Admin
 * 2.Ke Halaman Utama
 * 3.Ke Halaman Manajemen User Dengan Menklik Side Menu "User"
 * 4.Menekan Tobol Icon Pensil
 * 5.Mengubah Data user lama dan menganti data user dengan data baru
 * 6.Muncul Alert berhasil dan klik "OK"
 */
class UserTest extends DuskTestCase
{

    use withFaker;

      
    public function testUserfail()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
            //1.login
                ->loginAs(User::find(1))
            //2.Halaman Utama
                ->visit('/home')
            //3.Halaman User
                ->clickLink('User')
                ->assertPathIs('/user')->pause('3000')
                ->assertSee('Manajemen User');
            //4.edit user begin
            $user = User::latest()->value('id');
                $browser->press("#edit$user")->pause('3000') 
                ->whenAvailable('#edit' , function($edit_user){
                  $user = User::first()->value('email');
                    $edit_user->assertSee('Ubah User')
                //5.validasi field
                  //6.field kosong semua
                    ->clear('#name')->clear('#email')->clear('#phone_number')
                    ->press('#add')->pause('3000')
                    ->assertSee('Field name kosong')
                    ->AssertSee('Field email kosong')
                    ->AssertSee('Field nomor telpon Kosong')
                  //7.field nama diisi
                    ->type('#name', $this->faker->name)
                    ->press('#add')->pause('3000')
                    ->AssertSee('Field email kosong')
                    ->AssertSee('Field nomor telpon Kosong')
                  //8.field nomor telpon diisi
                    ->type('#phone_number', '08926744')
                    ->press('#add')->pause('3000') 
                    ->AssertSee('Field email kosong')
                  //9.field email diisi fotmat yang salah
                    ->type('#email', 'admindapurbundacom')
                    ->press('#add')->pause('3000')
                    ->AssertSee('Format Email Salah')
                  //10,field email diisin dengan user yang sudah ada
                    ->type('#email', "$user")
                    ->press('#add')->pause('3000')
                    ->AssertSee('Email sudah digunakan')
                  //11.After validasi Edit
                    ->press('.btn-secondary')->pause('3000');            
                });
        });
    }

    public function testUsersuccess()
    {
      $this->browse(function (Browser $browser) {
        $user = User::latest()->value('id');
        $browser->maximize()
        //1.login
            ->loginAs(User::find(1))
        //2.Halaman Utama
            ->visit('/home')
        //3.Halaman User
            ->clickLink('User')
            ->assertPathIs('/user')->pause('3000')
            ->assertSee('Manajemen User')
        //4.edit user begin
            ->press("#edit$user")->pause('3000') //perlu nomor
        //5.Edit dengan data yang benar
            ->whenAvailable('#edit' , function($edit_user)
            {
                $edit_user->assertSee('Ubah User')
                ->clear('#name')->clear('#email')->clear('#phone_number')
                ->type('#name', $this->faker->name)
                ->type('#phone_number', '08926744')
                ->type('#email', $this->faker->email)
                ->press('#add')->pause('3000');
            })
          //6..after edit    
              ->assertSee('Berhasil Mengubah Data')
              ->press('.swal-button--confirm');
    });
  }
}
