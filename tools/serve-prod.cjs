/**
 * Local production-like server for auditing.
 *
 * `php artisan serve` is single-threaded and returned text/html for static
 * assets under Lighthouse's concurrency, which made every timing number
 * meaningless. This proxies PHP for pages but serves /assets and other static
 * files directly, with correct MIME types, gzip and long cache headers - i.e.
 * roughly what the real host does.
 */
const http = require('http');
const fs = require('fs');
const path = require('path');
const zlib = require('zlib');

const PHP_ORIGIN = process.env.PHP_ORIGIN || 'http://127.0.0.1:8899';
const PORT = Number(process.env.PORT || 8900);
const ROOT = path.resolve('public');

const TYPES = {
  '.css': 'text/css; charset=utf-8', '.js': 'text/javascript; charset=utf-8',
  '.mjs': 'text/javascript; charset=utf-8', '.json': 'application/json',
  '.svg': 'image/svg+xml', '.webp': 'image/webp', '.avif': 'image/avif',
  '.png': 'image/png', '.jpg': 'image/jpeg', '.jpeg': 'image/jpeg',
  '.gif': 'image/gif', '.ico': 'image/x-icon', '.woff': 'font/woff',
  '.woff2': 'font/woff2', '.ttf': 'font/ttf', '.eot': 'application/vnd.ms-fontobject',
  '.txt': 'text/plain; charset=utf-8', '.xml': 'application/xml',
  '.webmanifest': 'application/manifest+json', '.map': 'application/json',
};
const COMPRESSIBLE = new Set(['.css', '.js', '.mjs', '.json', '.svg', '.txt', '.xml', '.webmanifest', '.map']);

http.createServer(async (req, res) => {
  const url = new URL(req.url, 'http://x');
  const rel = decodeURIComponent(url.pathname).replace(/^\/+/, '');
  const file = path.join(ROOT, rel);

  if (rel && file.startsWith(ROOT) && fs.existsSync(file) && fs.statSync(file).isFile()) {
    const ext = path.extname(file).toLowerCase();
    const headers = {
      'Content-Type': TYPES[ext] || 'application/octet-stream',
      'Cache-Control': 'public, max-age=31536000, immutable',
      'X-Content-Type-Options': 'nosniff',
    };
    let body = fs.readFileSync(file);
    if (COMPRESSIBLE.has(ext) && /\bgzip\b/.test(req.headers['accept-encoding'] || '')) {
      body = zlib.gzipSync(body, { level: 6 });
      headers['Content-Encoding'] = 'gzip';
      headers['Vary'] = 'Accept-Encoding';
    }
    headers['Content-Length'] = body.length;
    res.writeHead(200, headers);
    return res.end(body);
  }

  // Everything else -> PHP. The CLI server drops connections under parallel
  // load, so retry a few times rather than surfacing a spurious 502 that would
  // invalidate a whole Lighthouse run.
  try {
    let upstream, lastErr;
    for (let attempt = 0; attempt < 4; attempt++) {
      try {
        upstream = await fetch(PHP_ORIGIN + req.url, {
          method: req.method,
          headers: { ...req.headers, host: new URL(PHP_ORIGIN).host, 'accept-encoding': 'identity' },
          redirect: 'manual',
        });
        break;
      } catch (e) {
        lastErr = e;
        await new Promise(r => setTimeout(r, 60 * (attempt + 1)));
      }
    }
    if (!upstream) throw lastErr;
    let buf = Buffer.from(await upstream.arrayBuffer());
    const headers = {};
    upstream.headers.forEach((v, k) => {
      if (!['content-encoding', 'content-length', 'transfer-encoding'].includes(k)) headers[k] = v;
    });
    if (/\bgzip\b/.test(req.headers['accept-encoding'] || '') &&
        /text|json|javascript|xml/.test(headers['content-type'] || '')) {
      buf = zlib.gzipSync(buf, { level: 6 });
      headers['content-encoding'] = 'gzip';
      headers['vary'] = 'Accept-Encoding';
    }
    headers['content-length'] = buf.length;
    res.writeHead(upstream.status, headers);
    res.end(buf);
  } catch (e) {
    res.writeHead(502, { 'Content-Type': 'text/plain' });
    res.end('upstream error: ' + e.message);
  }
}).listen(PORT, () => console.log('prod-like server on http://127.0.0.1:' + PORT));
