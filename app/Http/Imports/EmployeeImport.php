<?php

namespace App\Imports;

use App\User;
use App\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;

class EmployeeImport implements ToModel
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        dd($row);
        $user = new User([
            'name'     => $row[0],
            'phone_number'    => $row[1], 
            'password' => \Hash::make('123456'),
            'role_id' => 2,

        ]);

        $employe = new Employee([
            'user_id'     => $user->id,
            'corporate_id'    => $row[3], 
            'role_id' => 2, 

        ]);
        
        return true;
    }
}
