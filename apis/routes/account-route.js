//For account route
const router = require('express').Router(),
controller = require('../controllers/account-controller.js');



router.get('/accounts/:schema?', controller.getAccounts);



router.get('/account/:id/:schema?', controller.getAccount);


module.exports = router;
