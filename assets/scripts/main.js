/**
 * Public scripts.
 */

import '../styles/main.scss';

window.addEventListener('CookiebotOnTagsExecuted', e => {
    const hiddenClass = 'cookiebot-helper--hidden';
    const placeholders = document.querySelectorAll(`.${hiddenClass}`);
    placeholders.forEach(placeholder => {
        placeholder.classList.remove(hiddenClass);
    });
});
