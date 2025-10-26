(function () {
  function applyAppearance(settings) {
    if (!settings) return;
    const root = document.documentElement;
    if (settings.fontSize) root.style.setProperty('--faq-font-size', settings.fontSize + 'px');
    if (settings.fontFamily) root.style.setProperty('--faq-font-family', settings.fontFamily);
    if (settings.questionTextColor) root.style.setProperty('--faq-question-color', settings.questionTextColor);
    if (settings.questionBgColor) root.style.setProperty('--faq-question-bg', settings.questionBgColor);
    if (settings.answerTextColor) root.style.setProperty('--faq-answer-color', settings.answerTextColor);
    if (settings.answerBgColor) root.style.setProperty('--faq-answer-bg', settings.answerBgColor);
    if (settings.answerPadding !== undefined) root.style.setProperty('--faq-answer-padding', settings.answerPadding + 'px');
  }

  // read query param `appearance`
  try {
    const params = new URLSearchParams(window.location.search);
    const b64 = params.get('appearance');
    if (b64) {
      const parsed = JSON.parse(decodeURIComponent(escape(atob(b64))));
      applyAppearance(parsed);
    }
  } catch (e) {
    // ignore parse errors
  }

  // listen for postMessage updates
  window.addEventListener('message', function (ev) {
    if (!ev.data || ev.data.type !== 'appearance:update') return;
    applyAppearance(ev.data.payload);
  }, false);
})();
