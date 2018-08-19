'use strict';

const express = require('express'),
CORS = require('./middleware/cors.js'),
accountRouter = require('./routes/account-route.js');


// Constants
const PORT = 80;
const HOST = 'localhost';

// App
const app = express();

//CORS
app.use(CORS);



//First get
app.get('/', (req, res) => {
  res.send('poiiko\n');
});

//Routes
app.use('/v1', accountRouter);

app.listen(PORT, () => {
    console.log(`Running on http://${HOST}:${PORT}`);
});
