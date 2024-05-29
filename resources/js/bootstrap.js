import axios from 'axios';
import QRCode from 'qrcode'
window.axios = axios;
window.qrcode = QRCode;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

