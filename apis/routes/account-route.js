//For account route
const router = require('express').Router(),
controller = require('../controllers/account-controller.js');

router.get('/', controller.getStats);

router.get('/accounts/:schema?', controller.getAccounts);
router.get('/filter/:filters', controller.getFilter);
router.get('/account/:id/:schema?', controller.getAccount);
//router.get('/partition_labels' , controller.getPartitionLabels);

// router.post('/filter', controller.filter);

module.exports = router;