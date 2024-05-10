<?php


require 'vendor/autoload.php';

use Lessmore92\Ethereum\Token;

$owner_private = 'a8877005528c1853f2b05af838f643eb6722216cf962bb6d21827b76b89db83e';
$owner_address = '0x91ebe1163b6576a0ebc8Bf01b2DAEC0DD72769Db';

$to_address = '0x8dc53847EcebBEB7AE9AB5158D4C425B6AE6Dd02';
// $contractAddress, $url, $ownerPrivate, $ownerAddress, $toAddress 参数可以取自配置文件
// 
function erc20Transfer($contractAddress, $url, $ownerPrivate, $ownerAddress, $toAddress) {
    $token = new \Lessmore92\Ethereum\Token($contractAddress, $url);

    $transferTx = $token->transfer($ownerAddress, $toAddress, 10);
    $transferTxId = $transferTx->sign($ownerPrivate)->send();
    $eth = $token->getWeb3()->eth;
    var_dump($transferTxId);
    sleep(3);
    $eth->getTransactionReceipt($transferTxId, function ($err, $transaction) {
        if ($err !== null) {
            throw new Exception($err->getMessage());
            var_dump($err->getMessage());
            //return $this->fail();
        }
        var_dump($transaction);

        if ($transaction->status != "0x1") {
            //throw new Exception($err->getMessage());
            var_dump("transfer failed", $transaction->transactionHash);
        }
    });

}

erc20Transfer("0xE0568fc4049c064B5FA51F70D7e163e850FBCBDF", "https://public.stackup.sh/api/v1/node/bsc-testnet", "01b0837811be36c99a7afbca16f013f317020302843335ebc580514473518a9a", "0x8dc53847EcebBEB7AE9AB5158D4C425B6AE6Dd02", "0x91ebe1163b6576a0ebc8Bf01b2DAEC0DD72769Db");