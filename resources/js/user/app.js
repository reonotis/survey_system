// import '../bootstrap';

import Alpine from 'alpinejs';
import '../../scss/user/app.scss';
import '../../scss/user/survey/survey_navigation.scss';
import { initSessionMessage } from './session-message.js';

window.Alpine = Alpine;

Alpine.start();

// セッションメッセージの初期化
initSessionMessage();
