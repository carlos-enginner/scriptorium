import axios from 'axios';

const instance = axios.create({
  baseURL: 'http://localhost:9501',
  timeout: 5000,
  headers: {
    'Content-Type': 'application/json',
    Authorization: 'Bearer your-token',
  },
});

export default instance;
