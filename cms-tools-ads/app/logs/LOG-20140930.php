<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

LOG - 2014-09-30 12:20:19 - [INFO]  --> [REQUEST : {"header":{"enc_type":301,"enc_value":{"authentication":{"enc_type":301,"enc_value":{"agentCode":"ZING"}},"version":"1.0","agentVersion":"1.0","agentTransactionId":"201409301220189155","agentTerminalId":"00","agentTimeStamp":"2014-09-30T12:20:18.9151","languageCode":"en"}}}] [RESPONSE : {"header":{"transactionId":"412024050375725","resultCode":"0","resultDescription":"Success"},"sessionToken":"710805C32FBA0F4FE332"}] 
LOG - 2014-09-30 12:20:41 - [INFO]  --> [REQUEST : {"method":"walletTransaction","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"","merchant":"gudangapps","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301220404032","transactionId":"4544","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":100,"allocatedBalance":-50,"availableBalance":150,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 12:21:08 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"COMMIT","value":"50","reasonCode":"","comments":"","agentTransReference":"201409301220404032","merchant":"gudangapps","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301221071727","transactionId":"4545","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":150,"allocatedBalance":0,"availableBalance":150,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 12:29:34 - [INFO]  --> [REQUEST : {"method":"walletTransaction","wallet":"novatti","msisdn":"628569229374","walletTransType":"DEBIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"","merchant":"gudangapps","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301229330839","transactionId":"4546","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":150,"allocatedBalance":50,"availableBalance":100,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 12:30:05 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"DEBIT","action":"COMMIT","value":"50.0","reasonCode":"","comments":"","agentTransReference":"201409301229330839","merchant":"gudangapps","wallet_id":1,"merchant_id":"3","registration_id":"201409081254131262"}] [RESPONSE : {"header":{"agentTransactionId":"201409301230046541","transactionId":"4548","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":100,"allocatedBalance":-50,"availableBalance":150,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 12:30:06 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"DEBIT","action":"COMMIT","value":"50.0","reasonCode":"","comments":"","agentTransReference":"201409301229330839","merchant":"gudangapps","wallet_id":1,"merchant_id":"3","registration_id":"201409081254131262"}] [RESPONSE : {"header":{"agentTransactionId":"201409301230056733","transactionId":"4549","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":150,"allocatedBalance":0,"availableBalance":150,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 12:30:06 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"DEBIT","action":"COMMIT","value":"50.0","reasonCode":"","comments":"","agentTransReference":"201409301229330839","merchant":"gudangapps","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301230032967","transactionId":"4547","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":100,"allocatedBalance":0,"availableBalance":100,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 13:08:33 - [INFO]  --> [REQUEST : {"method":"walletTransaction","wallet":"novatti","msisdn":"628569229374","walletTransType":"DEBIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"","merchant":"gudangapps","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301308322911","transactionId":"4550","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":150,"allocatedBalance":50,"availableBalance":100,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 13:08:49 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"DEBIT","action":"COMMIT","value":"50","reasonCode":"","comments":"","agentTransReference":"201409301308322911","merchant":"gudangapps","wallet_id":1,"merchant_id":"3","registration_id":"201409081254131262"}] [RESPONSE : {"header":{"agentTransactionId":"201409301308483305","transactionId":"4552","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":100,"allocatedBalance":-50,"availableBalance":150,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 13:08:50 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"DEBIT","action":"COMMIT","value":"50","reasonCode":"","comments":"","agentTransReference":"201409301308322911","merchant":"gudangapps","wallet_id":1,"merchant_id":"3","registration_id":"201409081254131262"}] [RESPONSE : {"header":{"agentTransactionId":"201409301308493116","transactionId":"4553","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":150,"allocatedBalance":0,"availableBalance":150,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 13:08:50 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"DEBIT","action":"COMMIT","value":"50","reasonCode":"","comments":"","agentTransReference":"201409301308322911","merchant":"gudangapps","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301308474622","transactionId":"4551","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":100,"allocatedBalance":0,"availableBalance":100,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 15:46:40 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"status":-1}] 
LOG - 2014-09-30 15:46:57 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"status":-1}] 
LOG - 2014-09-30 15:48:56 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"20140930154855873","transactionId":"4554","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":100,"allocatedBalance":50,"availableBalance":50,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 15:48:58 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301548569361","transactionId":"4555","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":50,"allocatedBalance":0,"availableBalance":50,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 15:48:58 - [INFO]  --> [REQUEST : {"msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps"}] [RESPONSE : {"status":-10}] 
LOG - 2014-09-30 16:09:40 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301609394611","transactionId":"4556","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":50,"allocatedBalance":50,"availableBalance":0,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:09:41 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301609404634","transactionId":"4557","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":0,"allocatedBalance":0,"availableBalance":0,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:09:42 - [INFO]  --> [REQUEST : {"msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301609417733","transactionId":"4558","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":150,"allocatedBalance":-50,"availableBalance":200,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:10:21 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"COMMIT","value":"100.0","reasonCode":"","comments":"","agentTransReference":"201409301609417733","merchant":"gudangapps","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301610205644","transactionId":"4559","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":200,"allocatedBalance":0,"availableBalance":200,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:13:17 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301613170456","transactionId":"4560","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":0,"allocatedBalance":50,"availableBalance":-50,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:13:18 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301613180836","transactionId":"4561","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":-50,"allocatedBalance":0,"availableBalance":-50,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:13:20 - [INFO]  --> [REQUEST : {"msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301613190657","transactionId":"4562","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":200,"allocatedBalance":-50,"availableBalance":250,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:15:10 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"COMMIT","value":"100.0","reasonCode":"","comments":"","agentTransReference":"201409301613190657","merchant":"gudangapps","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301615092555","transactionId":"4563","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":250,"allocatedBalance":0,"availableBalance":250,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:19:33 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301619325517","transactionId":"4564","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":-50,"allocatedBalance":50,"availableBalance":-100,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:19:34 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301619335153","transactionId":"4565","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":-100,"allocatedBalance":0,"availableBalance":-100,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:19:35 - [INFO]  --> [REQUEST : {"msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301619345177","transactionId":"4566","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":250,"allocatedBalance":-50,"availableBalance":300,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:20:06 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"COMMIT","value":"50.0","reasonCode":"","comments":"","agentTransReference":"201409301619345177","merchant":"gudangapps","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301620055757","transactionId":"4567","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":300,"allocatedBalance":0,"availableBalance":300,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:28:45 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301628448893","transactionId":"4568","resultCode":"4004","resultDescription":"Expired Session Token"}}] 
LOG - 2014-09-30 16:29:45 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301629450953","transactionId":"4569","resultCode":"4003","resultDescription":"Invalid Session Token"}}] 
LOG - 2014-09-30 16:32:12 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301632112661","transactionId":"4570","resultCode":"4003","resultDescription":"Invalid Session Token"}}] 
LOG - 2014-09-30 16:37:11 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301637107839","transactionId":"4571","resultCode":"4003","resultDescription":"Invalid Session Token"}}] 
LOG - 2014-09-30 16:38:11 - [INFO]  --> [REQUEST : {"header":{"enc_type":301,"enc_value":{"authentication":{"enc_type":301,"enc_value":{"agentCode":"ZING"}},"version":"1.0","agentVersion":"1.0","agentTransactionId":"20140930163810675","agentTerminalId":"00","agentTimeStamp":"2014-09-30T16:38:10.6747","languageCode":"en"}}}] [RESPONSE : {"header":{"transactionId":"412039521495241","resultCode":"0","resultDescription":"Success"},"sessionToken":"517294626DF40B915D24"}] 
LOG - 2014-09-30 16:38:18 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301638178655","transactionId":"4572","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":-100,"allocatedBalance":50,"availableBalance":-150,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:38:19 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301638189264","transactionId":"4573","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":-150,"allocatedBalance":0,"availableBalance":-150,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:38:20 - [INFO]  --> [REQUEST : {"msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301638200058","transactionId":"4574","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":300,"allocatedBalance":-50,"availableBalance":350,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:40:17 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"COMMIT","value":"50.0","reasonCode":"","comments":"","agentTransReference":"201409301638200058","merchant":"gudangapps","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301640158514","transactionId":"4575","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":350,"allocatedBalance":0,"availableBalance":350,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:42:47 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301642468118","transactionId":"4576","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":-150,"allocatedBalance":50,"availableBalance":-200,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:42:48 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301642477702","transactionId":"4577","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":-200,"allocatedBalance":0,"availableBalance":-200,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 16:42:49 - [INFO]  --> [REQUEST : {"msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50","reasonCode":"","comments":"Refund FROM 201409301308474622","merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301642486417","transactionId":"4578","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":350,"allocatedBalance":-50,"availableBalance":400,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 18:47:18 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50.00","reasonCode":"","comments":"refund nih","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301847177391","transactionId":"4579","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":-200,"allocatedBalance":50,"availableBalance":-250,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 18:47:19 - [INFO]  --> [REQUEST : {"method":"walletTransactionRefund","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50.00","reasonCode":"","comments":"refund nih","merchant":"gudangapps","ref_transaction_id":"201409301308474622","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"20140930184718757","transactionId":"4580","resultCode":"0","resultDescription":"Success"},"wallet":{"id":21,"type":"CURINR","balance":-250,"allocatedBalance":0,"availableBalance":-250,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 18:47:20 - [INFO]  --> [REQUEST : {"msisdn":"628569229374","walletTransType":"CREDIT","action":"ALLOCATE","value":"50.00","reasonCode":"","comments":"refund nih","merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301847198475","transactionId":"4581","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":350,"allocatedBalance":-100,"availableBalance":450,"currencyId":22,"currencyCode":"IDR"}}] 
LOG - 2014-09-30 18:47:21 - [INFO]  --> [REQUEST : {"method":"walletTransactionConfirm","wallet":"novatti","msisdn":"628569229374","walletTransType":"CREDIT","action":"COMMIT","value":"50.00","reasonCode":"","comments":"refund nih","merchant":"gudangapps","agentTransReference":"201409301847198475","wallet_id":1,"merchant_id":"3"}] [RESPONSE : {"header":{"agentTransactionId":"201409301847206525","transactionId":"4582","resultCode":"0","resultDescription":"Success"},"wallet":{"id":33,"type":"CURINR","balance":400,"allocatedBalance":-50,"availableBalance":450,"currencyId":22,"currencyCode":"IDR"}}] 
