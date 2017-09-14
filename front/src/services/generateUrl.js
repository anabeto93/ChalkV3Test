export default function generateUrl(path, params = {}) {
  let url = path;

  for (let key in params) {
    url = url.replace(key, params[key]);
  }

  return url;
}
