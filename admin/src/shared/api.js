let axios = require('axios');

let axiosInstance = axios.create({
  baseURL: process.env.VUE_APP_API_URL,
  /* other custom settings */
});

module.exports = axiosInstance;
