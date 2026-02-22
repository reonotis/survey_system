// import '../bootstrap';

import Alpine from 'alpinejs';
import '../../scss/user/app.scss';
import { initSessionMessage } from './session-message.js';

window.Alpine = Alpine;

Alpine.start();

// セッションメッセージの初期化
initSessionMessage();
