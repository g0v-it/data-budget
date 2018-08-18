'use strict';

const express = require('express');

// Constants
const PORT = 80;
const HOST = 'localhost';

// App
const app = express();

//CORS
app.use(function(req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    next();
});

//First get
app.get('/', (req, res) => {
  res.send('poiiko\n');
});

app.listen(PORT, () => {
    console.log(`Running on http://${HOST}:${PORT}`);
});
