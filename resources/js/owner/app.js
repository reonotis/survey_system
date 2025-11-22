// import '../bootstrap';

import Alpine from 'alpinejs';
import '../../scss/owner/app.scss';
import '../../scss/owner/survey/survey_navigation.scss';
import { initSessionMessage } from './session-message.js';

window.Alpine = Alpine;

Alpine.start();

// セッションメッセージの初期化
initSessionMessage();
