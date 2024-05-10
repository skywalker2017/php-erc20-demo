<?php


require 'vendor/autoload.php';

use Lessmore92\Ethereum\Token;

// $contractAddress, $url, $ownerPrivate, $ownerAddress 参数可以取自配置文件
// $contractAddress: 合约地址，上线时被调用方提供
// $url: 区块链节点url， 被调用方提供
// $ownerAddress: 调用方钱包地址， 参考： https://zhuanlan.zhihu.com/p/112285438?from_voters_page=true
// $ownerPrivate: 调用方钱包私钥，参考： https://support.metamask.io/managing-my-wallet/secret-recovery-phrase-and-private-keys/how-to-export-an-accounts-private-key/
// $toAddress: 用户钱包地址， 用户提供

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