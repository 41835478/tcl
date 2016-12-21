<?php
namespace App\Repositories\Chance;
 
interface ChanceRepositoryContract
{
    
    public function find($id);
    
    public function getAllChances($requestData, $is_self = false);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    public function isRepeat($requestData);
}
