<?php

class ProfileController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function getCustomerProfile($tenTaiKhoan) {
        $customerInfo = $this->model->getCustomerInfoByTenTaiKhoan($tenTaiKhoan);
      
        return $customerInfo;
    }
}

?>
