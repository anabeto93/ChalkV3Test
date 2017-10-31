import checkUpdates from './checkUpdates';

export default function() {
  // Loop every 5 minutes
  const loopCheckUpdates = setInterval(checkUpdates, 300000);

  // Check updates when app start
  const firstCheckUpdates = setTimeout(checkUpdates, 5000);

  if (module.hot) {
    module.hot.accept();
    module.hot.dispose(() => {
      clearInterval(loopCheckUpdates);
      clearTimeout(firstCheckUpdates);
    });
  }
}
