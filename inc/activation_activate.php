<?php
// Activision function
function activate_subsactivations_cplgn(){
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $subsactivations__v2 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}subsactivations__v2` (
        `ID` INT NOT NULL AUTO_INCREMENT,
        `user_id` INT NOT NULL,
        `username` VARCHAR(255) NOT NULL,
        `account_number` INT NOT NULL,
        `pos` INT NOT NULL,
        PRIMARY KEY (`ID`)) ENGINE = InnoDB";
        dbDelta($subsactivations__v2);

    $lic_activations = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}lic_activations` (
        `ID` INT NOT NULL AUTO_INCREMENT,
        `Orderno` INT NOT NULL,
        `Mtid` INT NOT NULL,
        `Userid` INT NOT NULL,
        `UserName` VARCHAR(255) NOT NULL,
        `Prodcode` VARCHAR(255) NOT NULL,
        `Product_id` INT NOT NULL,
        `Status` INT NOT NULL,
        `Comment` VARCHAR(50) NOT NULL,
        `Editable` BOOLEAN NOT NULL,
        `Modifydate` DATETIME NOT NULL,
        `Expirytime` DATETIME NOT NULL,
        PRIMARY KEY (`ID`)) ENGINE = InnoDB";
        dbDelta($lic_activations);


    $lic_produce_info = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}lic_produce_info` (
        `ID` INT NOT NULL AUTO_INCREMENT,
        `product_id` INT NOT NULL,
        `of_License` INT NOT NULL,
        `product_code` VARCHAR(255) NOT NULL,
        `comment` VARCHAR(55) NOT NULL,
        PRIMARY KEY (`ID`)) ENGINE = InnoDB";
        dbDelta($lic_produce_info);
}