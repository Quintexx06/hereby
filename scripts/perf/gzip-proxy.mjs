/**
 * A tiny gzip reverse proxy for performance checks (roadmap 1.13).
 * `php artisan serve` sends everything uncompressed; production does not, so
 * Lighthouse would over-count every byte. Node built-ins only, no dependency.
 *
 *   node scripts/perf/gzip-proxy.mjs [listenPort=8099] [targetPort=8000]
 */
import http from 'node:http';
import zlib from 'node:zlib';

const listen = Number(process.argv[2] ?? 8099);
const target = Number(process.argv[3] ?? 8000);
const compressible = /text|javascript|json|css|svg|xml/;

http.createServer((request, response) => {
    const upstream = http.request(
        {
            host: '127.0.0.1',
            port: target,
            path: request.url,
            method: request.method,
            headers: { ...request.headers, 'accept-encoding': 'identity' },
        },
        (reply) => {
            const type = String(reply.headers['content-type'] ?? '');
            const gzip =
                compressible.test(type) &&
                /gzip/.test(String(request.headers['accept-encoding'] ?? ''));
            const headers = { ...reply.headers };

            if (gzip) {
                delete headers['content-length'];
                headers['content-encoding'] = 'gzip';
                headers.vary = 'Accept-Encoding';
            }

            if (request.url?.startsWith('/build/assets/')) {
                headers['cache-control'] =
                    'public, max-age=31536000, immutable';
            }

            response.writeHead(reply.statusCode ?? 502, headers);
            (gzip ? reply.pipe(zlib.createGzip({ level: 6 })) : reply).pipe(
                response,
            );
        },
    );
    upstream.on('error', () => response.writeHead(502).end());
    request.pipe(upstream);
}).listen(listen, () => console.log(`gzip proxy :${listen} -> :${target}`));
