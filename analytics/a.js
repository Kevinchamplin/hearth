/* Hearth Analytics beacon — anonymous, first-party, content-free.
   Sends usage events to /analytics/collect.php. Never sends conversation text. */
(function () {
  function uuid() {
    try { return crypto.randomUUID(); }
    catch (e) { return 'x' + Date.now().toString(36) + Math.random().toString(36).slice(2); }
  }
  function id(key, store) {
    try { var v = store.getItem(key); if (!v) { v = uuid(); store.setItem(key, v); } return v; }
    catch (e) { return uuid(); }
  }
  var visitor = id('h_vid', window.localStorage);   // stable across visits (this browser only)
  var session = id('h_sid', window.sessionStorage);  // resets each tab session

  function base() {
    var tz = '';
    try { tz = Intl.DateTimeFormat().resolvedOptions().timeZone || ''; } catch (e) {}
    return {
      visitor: visitor,
      session: session,
      page: location.pathname.indexOf('/app') === 0 ? 'app' : 'landing',
      screen: (screen.width || 0) + 'x' + (screen.height || 0),
      viewport: (window.innerWidth || 0) + 'x' + (window.innerHeight || 0),
      dpr: window.devicePixelRatio || 1,
      locale: navigator.language || '',
      tz: tz,
      referrer: document.referrer || '',
      webgpu: !!navigator.gpu
    };
  }
  function track(event, props) {
    try {
      var payload = base();
      payload.event = event;
      if (props) for (var k in props) payload[k] = props[k];
      var body = JSON.stringify(payload);
      if (navigator.sendBeacon) {
        navigator.sendBeacon('/analytics/collect.php', new Blob([body], { type: 'text/plain' }));
      } else {
        fetch('/analytics/collect.php', { method: 'POST', body: body, keepalive: true });
      }
    } catch (e) {}
  }

  window.hearth = window.hearth || {};
  window.hearth.track = track;

  // Auto pageview on load.
  track('pageview');

  // Auto-track clicks on anything tagged with data-ev="name".
  document.addEventListener('click', function (e) {
    var el = e.target && e.target.closest ? e.target.closest('[data-ev]') : null;
    if (el) track('cta_click', { value: el.getAttribute('data-ev') });
  }, true);
})();
