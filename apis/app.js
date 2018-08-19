'use strict';

const express = require('express'),
CORS = require('./middleware/cors.js'),
accountRouter = require('./routes/account-route.js');


// Constants
const PORT = 80,
HOST = 'localhost';

// App
const app = express();

//CORS
app.use(CORS);


//Routes
app.use('/', accountRouter);

app.listen(PORT, () => {
    console.log(`Running on http://${HOST}:${PORT}`);
});
